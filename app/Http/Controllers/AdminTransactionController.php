<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransactionController extends Controller
{
    protected $notchPay;

    public function __construct(\App\Services\NotchPayPaymentProvider $notchPay)
    {
        $this->notchPay = $notchPay;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $search  = $request->get('q');
        $type    = $request->get('type');
        $status  = $request->get('status');

        $query = Transaction::with(['user:id,phone,level,withdrawal_account,withdrawal_name,withdrawal_country,email,currency'])
            ->select('transactions.*')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id');

        // Recherche
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transactions.reference', 'like', "%{$search}%")
                  ->orWhere('users.phone', 'like', "%{$search}%");
            });
        }

        // Filtres
        if ($type) {
            if ($type === 'bonus_all') {
                $query->where('transactions.type', 'LIKE', 'bonus%');
            } else {
                $query->where('transactions.type', $type);
            }
        }

        if ($status) {
            $query->where('transactions.status', $status);
        }

        // Tri par défaut
        $transactions = $query->orderByDesc('transactions.created_at')
                              ->paginate($perPage)
                              ->withQueryString();

        // Stats rapides pour la page
        $stats = [
            'total'            => Transaction::count(),
            'pending_retraits' => Transaction::where('type', 'retrait')->where('status', 'pending')->count(),
            'today_completed'  => Transaction::whereDate('created_at', today())->where('status', 'completed')->count(),
            'total_amount' => Transaction::where('status', 'completed')->sum('montant'),
        ];

        return view('admin.transaction-index', compact('transactions', 'stats'));
    }

    public function approve(Transaction $transaction)
    {
        if ($transaction->type !== 'retrait' || $transaction->status !== 'pending') {
            return back()->with('error', 'Cette transaction ne peut pas être approuvée.');
        }

        $user = $transaction->user;

        // MODE SIMULATION
        $isSimulation = app()->environment(['local', 'testing']) || config('notchpay.sandbox', false);

        if ($isSimulation) {
            $transaction->update(['status' => 'completed']);
            return back()->with('success', 'Retrait approuvé et simulé avec succès.');
        }

        try {
            $withdrawalCountry = strtoupper($user->withdrawal_country ?? 'CM');
            $phonePrefix = config('notchpay.country_phone_codes.' . $withdrawalCountry, '237');

            $phone = $user->withdrawal_account;
            if (!str_starts_with($phone, '+')) {
                $phone = '+' . $phonePrefix . ltrim($phone, '0');
            }

            $amountFCFA = $transaction->montant;
            $amountNetFCFA = (int) round($amountFCFA * 0.90);

            $beneficiaryChannel = config('notchpay.beneficiary_channels.' . $withdrawalCountry, 'cm.mobile');

            $beneficiary = $this->notchPay->createBeneficiary(
                name: $user->withdrawal_name ?? 'Investisseur',
                phone: $phone,
                email: $user->email ?? 'no-reply@bioenergy.cm',
                channel: $beneficiaryChannel,
                country: strtolower($withdrawalCountry)
            );

            if (!$beneficiary['success']) {
                return back()->with('error', 'Erreur bénéficiaire: ' . $beneficiary['message']);
            }

            $transfer = $this->notchPay->transfer(
                amountFCFA: $amountNetFCFA,
                beneficiaryId: $beneficiary['beneficiary_id'],
                description: "Retrait BioEnergy Validé",
                reference: $transaction->reference,
                currency: $user->currency
            );

            if (!$transfer['success']) {
                return back()->with('error', 'Échec du transfert: ' . $transfer['message']);
            }

            $status = ($transfer['status'] === 'complete') ? 'completed' : 'pending';
            $transaction->update([
                'status' => $status,
                'gateway_reference' => $transfer['notch_reference'] ?? null,
            ]);

            return back()->with('success', 'Le retrait a été validé et envoyé vers Notch Pay.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur technique lors du transfert: ' . $e->getMessage());
        }
    }

    public function reject(Transaction $transaction)
    {
        if ($transaction->type !== 'retrait' || $transaction->status !== 'pending') {
            return back()->with('error', 'Cette transaction ne peut pas être rejetée.');
        }

        DB::beginTransaction();
        try {
            $transaction->update(['status' => 'failed']);
            $transaction->user->increment('account_balance', $transaction->montant);
            DB::commit();

            return back()->with('success', 'Le retrait a été rejeté. Les fonds ont été restitués.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'annulation.');
        }
    }
}