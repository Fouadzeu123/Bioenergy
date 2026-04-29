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
use App\Services\NotchPayPaymentProvider;

class TransactionController extends Controller
{
    protected NotchPayPaymentProvider $notchPay;

    public function __construct(NotchPayPaymentProvider $notchPay)
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
        $minDepot = $user->role === 'admin' ? 0 : 5;

        // Déterminer le pays de l'utilisateur depuis l'indicatif téléphonique
        $userCountry = ($user->country_code === '225') ? 'CI' : 'CM';
        $allowedOperators = ($userCountry === 'CI') ? ['MTN', 'ORANGE', 'MOOV'] : ['MTN', 'ORANGE'];

        $request->validate([
            'amount'         => "required|numeric|min:{$minDepot}|max:100000",
            'payment_method' => ['required', \Illuminate\Validation\Rule::in($allowedOperators)],
        ]);

        $amount = (float) $request->amount;
        $operator = strtoupper($request->payment_method);
        $amountFCFA = $amount;

        $internalRef = 'DEP-' . $user->id . '-' . time() . '-' . Str::random(4);

        // Formatage du téléphone selon le pays
        $phonePrefix = config('notchpay.country_phone_codes.' . $userCountry, '237');
        $phone = $user->phone;
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phonePrefix . ltrim($phone, '0');
        }

        // Canal NotchPay
        $channel = config("notchpay.channels.{$userCountry}.{$operator}",
            $userCountry === 'CI' ? 'ci.mtn' : 'cm.mtn'
        );

        // Création de la transaction en statut "pending"
        $transaction = Transaction::create([
            'user_id'      => $user->id,
            'type'         => 'depot',
            'montant'      => $amount,
            'montant_fcfa' => $amountFCFA,
            'status'       => 'pending',
            'reference'    => $internalRef,
            'operator'     => $operator,
            'gateway'      => 'notchpay',
            'description'  => "Dépôt de " . number_format($amount, 0, '.', ' ') . " " . $user->currency . " via {$operator} (" . config('notchpay.country_names.' . $userCountry, $userCountry) . ")",
        ]);

        // ── MODE SIMULATION / TEST ─────────────────────────────────────────────
        if (config('notchpay.sandbox', false)) {
            $transaction->update(['status' => 'completed']);
            $user->increment('account_balance', $amount);

            return redirect()->route('depot.success', ['reference' => $transaction->reference]);
        }

        // ── PRODUCTION : Appel direct Notch Pay ───────────────────────────────
        try {
            $result = $this->notchPay->charge($transaction, [
                'phone'    => $phone,
                'provider' => $operator,
                'country'  => strtolower($userCountry),
            ]);

            if (!$result->success && !($result->is_pending ?? false)) {
                $transaction->update(['status' => 'failed']);
                return back()->with('error', 'Erreur de paiement : ' . ($result->message ?? 'Inconnu'));
            }

            // Stocker la référence Notch Pay pour le rapprochement
            $transaction->update([
                'gateway_reference' => $result->reference ?? null,
            ]);

            // Si le statut est déjà "success" (cas rare ou sandbox immédiat)
            if ($result->success) {
                $updated = Transaction::where('id', $transaction->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'completed']);

                if ($updated) {
                    $user->increment('account_balance', $amount);
                    return redirect()->route('depot.success', ['reference' => $transaction->reference]);
                }
            }

            // Statut "pending" ou "incomplete" → l'utilisateur doit valider le push USSD
            return redirect()->route('depot.waiting', ['reference' => $transaction->reference]);
        } catch (Exception $e) {
            Log::error('NotchPay Dépôt: exception', [
                'user_id' => $user->id,
                'country' => $userCountry,
                'error'   => $e->getMessage(),
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

        if ($transaction->status === 'pending' && !$transaction->gateway_reference) {
             Log::warning("Polling: La transaction {$reference} n'a pas de gateway_reference. Le polling NotchPay est impossible.");
        }

        if ($transaction->status === 'pending' && $transaction->gateway_reference) {
            try {
                $check = $this->notchPay->verify($transaction->gateway_reference);

                if ($check->success) {
                    // Paiement confirmé !
                    // Idempotence : on n'augmente le solde que si la transaction était encore en 'pending'
                    $updated = Transaction::where('id', $transaction->id)
                        ->where('status', 'pending')
                        ->update(['status' => 'completed']);

                    if ($updated) {
                        Auth::user()->increment('account_balance', $transaction->montant);
                        return response()->json(['status' => 'completed']);
                    } else {
                        // Déjà complétée par le webhook par exemple
                        return response()->json(['status' => 'completed']);
                    }
                }

                if (!$check->is_pending) {
                    // C'est un échec (failed, expired, canceled, etc.)
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

        $totalRetraits = $retraits->where('status','completed')
        ->sum('montant');

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
        $minRetrait   = $user->role === 'admin' ? 1 : 5;

        $request->validate([
            'amount'              => "required|numeric|min:1000|max:1000000",
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
            'description' => "Retrait de " . number_format($amount, 0, '.', ' ') . " " . $user->currency . " via " . strtoupper($user->withdrawal_method),
        ]);

        // Déduction immédiate du solde pour verrouiller les fonds
        $user->decrement('account_balance', $amount);

        return back()->with('success', "Votre demande de retrait de " . number_format($amount, 0, '.', ' ') . " " . $user->currency . " a été enregistrée avec succès.");
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
