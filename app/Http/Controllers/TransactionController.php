<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\NotchPayService;

class TransactionController extends Controller
{
    protected NotchPayService $notchPay;

    public function __construct(NotchPayService $notchPay)
    {
        $this->notchPay = $notchPay;
    }

    // ====================================================================
    // DÉPÔT
    // ====================================================================

    public function deposit()
    {
        $user = Auth::user();
        $depots = Transaction::where('user_id', $user->id)
            ->where('type', 'depot')
            ->take(5)
            ->orderByDesc('created_at')
            ->get();

        $totalDepots = Transaction::where('user_id', $user->id)
            ->where('type', 'depot')
            ->where('status', 'completed')
            ->sum('montant');

        return view('deposit', compact('depots', 'totalDepots'));
    }

    public function storeDepot(Request $request)
    {
        $user = Auth::user();
        $minDepot = strtolower($user->username ?? '') === 'boris' ? 1 : 10;

        $request->validate([
            'amount'         => "required|numeric|min:{$minDepot}|max:100000",
            'payment_method' => 'required|in:MTN,ORANGE',
        ]);

        $amount = (float) $request->amount;
        $operator = strtoupper($request->payment_method);
        $amountXAF = $this->notchPay->usdToXaf($amount);

        $internalRef = 'DEP-' . $user->id . '-' . time() . '-' . Str::random(4);

        // Création de la transaction en statut "pending"
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'depot',
            'montant' => $amount,
            'montant_fcfa' => $amountXAF,
            'status' => 'pending',
            'reference' => $internalRef,
            'operator' => $operator,
            'gateway' => 'notchpay',
            'description' => "Dépôt de {$amount}$ via {$operator}",
        ]);

        // ── MODE SIMULATION / TEST ─────────────────────────────────────────────
        if (config('notchpay.sandbox', false)) {
            $transaction->update(['status' => 'completed']);
            $user->increment('account_balance', $amount);

            return redirect()->route('depot.success', ['reference' => $transaction->reference]);
        }

        // ── PRODUCTION : Appel direct Notch Pay ───────────────────────────────
        try {
            $phone = $user->phone;

            // On formate le numéro en format international si nécessaire
            if (!str_starts_with($phone, '+')) {
                $phone = '+237' . ltrim($phone, '0');
            }

            $result = $this->notchPay->initializePayment(
                amountXAF: $amountXAF,
                email: $user->email ?? 'no-reply@bioenergy.cm',
                phone: $phone,
                reference: $internalRef,
                description: "Dépôt de {$amount}$ via {$operator} – BioEnergy",
                operator: $operator
            );

            if (!$result['success']) {
                $transaction->update(['status' => 'failed']);
                return back()->with('error', 'Erreur de paiement : ' . ($result['message'] ?? 'Inconnu'));
            }

            // Stocker la référence Notch Pay pour le rapprochement webhook
            $transaction->update([
                'gateway_reference' => $result['notch_reference'] ?? null,
            ]);

            // Si le statut est déjà "complete" (paiement instantané)
            if (($result['status'] ?? '') === 'complete') {
                $transaction->update(['status' => 'completed']);
                $user->increment('account_balance', $amount);

                return redirect()->route('depot.success', ['reference' => $transaction->reference]);
            }

            // Statut "pending" → l'utilisateur doit valider le push USSD sur son téléphone
            return redirect()->route('depot.waiting', ['reference' => $transaction->reference]);
        } catch (Exception $e) {
            Log::error('NotchPay Dépôt: exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            $transaction->update(['status' => 'failed']);
            return back()->with('error', 'Erreur lors de la connexion au service de paiement. Réessayez plus tard.');
        }
    }

    public function checkStatus($reference)
    {
        $transaction = Transaction::where('reference', $reference)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaction) {
            return response()->json(['status' => 'not_found'], 404);
        }

        if ($transaction->status === 'pending' && $transaction->gateway_reference) {
            try {
                $check = $this->notchPay->verifyPayment($transaction->gateway_reference);

                if (($check['status'] ?? '') === 'complete') {
                    // Paiement confirmé par l'API !
                    $transaction->update(['status' => 'completed']);
                    Auth::user()->increment('account_balance', $transaction->montant);

                    Log::info('checkStatus: Paiement confirmé par polling direct', [
                        'ref' => $transaction->reference,
                        'notch_ref' => $transaction->gateway_reference
                    ]);

                    return response()->json(['status' => 'completed']);
                }

                if (in_array($check['status'] ?? '', ['failed', 'canceled', 'rejected', 'expired'])) {
                    $transaction->update(['status' => 'failed']);
                    return response()->json(['status' => 'failed']);
                }
            } catch (Exception $e) {
                Log::error('checkStatus: erreur polling direct', ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['status' => $transaction->status]);
    }

    public function waitingDepot($reference)
    {
        $transaction = Transaction::where('reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('depot-waiting', compact('transaction'));
    }

    public function successDepot($reference)
    {
        $transaction = Transaction::where('reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('depot-success', compact('transaction'));
    }

    public function failedDepot($reference)
    {
        $transaction = Transaction::where('reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('depot-failed', compact('transaction'));
    }



    // ====================================================================
    // RETRAIT
    // ====================================================================

    public function retrait()
    {
        $user = Auth::user();

        // Vérification infos de retrait
        if (!$user->withdrawal_method || !$user->withdrawal_account || !$user->withdrawal_name) {
            return redirect()->route('withdraw_info')
                ->with('error', 'Veuillez configurer vos informations de retrait avant de continuer.');
        }

        // Vérification qu'il a déjà investi
        $hasInvested = Transaction::where('user_id', $user->id)
            ->where('type', 'invest')
            ->where('status', 'completed')
            ->exists();

        if (!$hasInvested) {
            return redirect()->route('products')
                ->with('error', 'Les retraits sont réservés aux investisseurs actifs.');
        }

        $retraits = Transaction::where('user_id', $user->id)
            ->where('type', 'retrait')
            ->latest()
            ->get();

        $totalRetraits = $retraits->sum('montant');

        return view('retrait', compact('retraits', 'totalRetraits'));
    }

    // === ÉTAPE 1 : Aperçu du retrait (juste pour afficher le modal) ===
    public function previewRetrait(Request $request)
    {
        return back();
    }

    // === ÉTAPE 2 : Confirmation finale avec mot de passe ===
    public function storeRetrait(Request $request)
    {
        $user         = Auth::user();
        $minRetrait   = strtolower($user->username ?? '') === 'boris' ? 1 : 10;

        $request->validate([
            'amount'              => "required|numeric|min:{$minRetrait}|max:10000",
            'withdrawal_password' => 'required|string',
        ]);

        $amount = (float) $request->amount;

        // =============================================
        // 1. VÉRIFICATION HORAIRES RETRAIT : LUNDI-VENDREDI 9H-18H
        // =============================================
        $now = now();

        $dayOfWeek = $now->dayOfWeekIso; // 1 = lundi, 5 = vendredi, 7 = dimanche
        $hour = $now->hour;

        if ($dayOfWeek < 1 || $dayOfWeek > 5 || $hour < 9 || $hour >= 18) {
            $message = "Les retraits sont uniquement possibles du <strong>lundi au vendredi de 9h00 à 18h00</strong>.";

            if ($dayOfWeek < 1 || $dayOfWeek > 5) {
                $message .= " Aujourd'hui nous sommes " . $now->translatedFormat('l') . ".";
            } else {
                $message .= " Il est actuellement {$now->format('H\\hi')}.";
            }

            return back()->with('error', $message);
        }

        // =============================================
        // 2. Vérification mot de passe de retrait
        // =============================================
        if (empty($user->withdrawal_password) || !Hash::check($request->withdrawal_password, $user->withdrawal_password)) {
            return back()->with('error', 'Mot de passe de retrait incorrect.');
        }

        // =============================================
        // 3. Vérification solde
        // =============================================
        if ($amount > $user->account_balance) {
            return back()->with('error', 'Solde insuffisant pour effectuer ce retrait.');
        }

        // =============================================
        // 4. Éviter les doublons
        // =============================================
        $recent = Transaction::where('user_id', $user->id)
            ->where('type', 'retrait')
            ->where('montant', $amount)
            ->where('created_at', '>', now()->subMinutes(2))
            ->exists();

        if ($recent) {
            return back()->with('error', 'Vous avez déjà soumis ce retrait. Veuillez patienter.');
        }

        // =============================================
        // 5. Création de la transaction
        // =============================================
        $reference = 'WDR-' . $user->id . '-' . time() . '-' . rand(10, 99);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'retrait',
            'montant' => $amount,
            'status' => 'pending',
            'reference' => $reference,
            'operator' => strtoupper($user->withdrawal_method),
            'gateway' => 'notchpay',
            'description' => "Retrait de {$amount}$ via " . strtoupper($user->withdrawal_method),
        ]);

        // =============================================
        // 6. MODE SIMULATION (local/testing)
        // =============================================
        $isSimulation = app()->environment(['local', 'testing']) || config('notchpay.sandbox', false);

        if ($isSimulation) {
            $user->decrement('account_balance', $amount);
            $transaction->update(['status' => 'completed']);

            return back()->with('success', "Retrait de {$amount}$ SIMULÉ avec succès ! (mode test)");
        }

        // =============================================
        // 7. MODE PRODUCTION → Transfert/Payout via Notch Pay
        // =============================================
        try {
            // Nettoyage du numéro de téléphone
            $phone = $user->withdrawal_account;
            if (!str_starts_with($phone, '+')) {
                $phone = '+237' . ltrim($phone, '0');
            }

            $amountXAF = $this->notchPay->usdToXaf($amount);
            $amountNetXAF = (int) round($amountXAF * 0.90); // 10% de frais appliqués

            // Étape 1 : Créer le bénéficiaire
            $beneficiary = $this->notchPay->createBeneficiary(
                name: $user->withdrawal_name ?? 'Investisseur BioEnergy',
                phone: $phone,
                email: $user->email ?? 'no-reply@bioenergy.cm',
                country: 'CM'
            );

            if (!$beneficiary['success']) {
                $transaction->update(['status' => 'failed']);
                return back()->with('error', 'Erreur création bénéficiaire : ' . $beneficiary['message']);
            }

            // Étape 2 : Initier le transfert (Payout)
            $transfer = $this->notchPay->transfer(
                amountXAF: $amountNetXAF,
                beneficiaryId: $beneficiary['beneficiary_id'],
                description: "Retrait BioEnergy",
                reference: $reference
            );

            if (!$transfer['success']) {
                $transaction->update(['status' => 'failed']);
                return back()->with('error', 'Échec du transfert : ' . $transfer['message']);
            }

            // Succès de l'initiation du transfert
            $user->decrement('account_balance', $amount);

            // Le statut reste 'pending' si le transfert n'est pas finalisé immédiatement.
            // Notch Pay webhooks renverront l'événement "transfer.complete"
            $status = ($transfer['status'] === 'complete') ? 'completed' : 'pending';

            $transaction->update([
                'status' => $status,
                'gateway_reference' => $transfer['notch_reference'] ?? null,
            ]);

            Log::info('NotchPay: Retrait API initié', [
                'user_id' => $user->id,
                'amount_usd' => $amount,
                'amount_xaf' => $amountNetXAF,
                'ref' => $reference,
            ]);

            return back()->with('success', "Votre retrait de {$amount}$ a été pris en compte. Il est en cours d'envoi vers votre compte Mobile Money.");

        } catch (Exception $e) {
            Log::error('Retrait NotchPay: exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            $transaction->update(['status' => 'failed']);
            return back()->with('error', 'Erreur technique lors du transfert. Veuillez contacter le support.');
        }
    }

    // ====================================================================
    // HISTORIQUE
    // ====================================================================
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $totalDepots = $transactions->where('type', 'depot')->sum('montant');
        $totalRetraits = $transactions->where('type', 'retrait')->sum('montant');

        return view('transactions', compact('transactions', 'totalDepots', 'totalRetraits'));
    }
}
