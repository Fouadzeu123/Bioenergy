<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Jobs\ProcessMesombDeposit;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Hachther\MeSomb\Operation\Payment\Collect;
use Hachther\MeSomb\Operation\Payment\Deposit;

class TransactionController extends Controller
{
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
    $request->validate([
        'amount'         => 'required|numeric|min:10|max:100000',
        'payment_method' => 'required|in:MTN,ORANGE',
    ]);

    $user     = Auth::user();
    $amount   = (float) $request->amount;
    $operator = strtoupper($request->payment_method);
    $amountXAF = (int) round($amount * config('mesomb.usd_to_xaf', 600));

    $internalRef = 'DEP-' . $user->id . '-' . time() . '-' . Str::random(4);

    // Création de la transaction
    $transaction = Transaction::create([
        'user_id'       => $user->id,
        'type'          => 'depot',
        'montant'       => $amount,
        'montant_fcfa'  => $amountXAF,
        'status'        => 'pending',
        'reference'     => $internalRef,
        'operator'      => $operator,
        'gateway'       => 'mesomb',
        'description'   => "Dépôt de {$amount}$ via {$operator}",
    ]);

    // MODE SIMULATION / TEST (Simpler check)
    if (config('mesomb.test', false)) {
        $transaction->update(['status' => 'completed']);
        $user->increment('account_balance', $amount);

        return back()->with('success', "Dépôt de {$amount}$ SIMULÉ avec succès ! (Mode Test)");
    }

    // PRODUCTION : Appel direct synchrone
    try {
        $collect = new Collect(
            $user->phone, // Assumes user phone is correct for payment
            $amountXAF,
            $operator,
            'CM'
        );

        $response = $collect->pay();

        if ($response->status === 'SUCCESS') {
            $transaction->update([
                'status'            => 'completed',
                'gateway_reference' => $response->transaction->reference ?? null,
            ]);
            $user->increment('account_balance', $amount);
            
            return back()->with('success', "Dépôt de {$amount}$ réussi et crédité !");
        } 
        
        if ($response->status === 'FAILED') {
            $transaction->update(['status' => 'failed']);
            return back()->with('error', "Le paiement a échoué : " . ($response->message ?? 'Inconnu'));
        }

        // Sinon, reste en 'pending' (l'utilisateur devra valider le push USSD)
        return back()->with('success', "Dépôt initié. Veuillez valider le message sur votre téléphone pour confirmer.");

    } catch (\Exception $e) {
        Log::error('Erreur MeSomb Direct', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la connexion au service de paiement. Réessayez plus tard.');
    }
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
    // Cette route est appelée quand l'utilisateur clique sur "Continuer"
    // On ne fait rien ici → tout est géré en JavaScript dans la vue
    return back();
}

// === ÉTAPE 2 : Confirmation finale avec mot de passe ===
public function storeRetrait(Request $request)
{
    $request->validate([
        'amount'              => 'required|numeric|min:10|max:10000',
        'withdrawal_password' => 'required|string',
    ]);

    $user   = Auth::user();
    $amount = (float) $request->amount;

    // =============================================
    // 1. VÉRIFICATION HORAIRES RETRAIT : JEUDI 9H-18H
    // =============================================
    $now = now(); // Heure du serveur (configure ton timezone dans config/app.php → 'timezone' => 'Africa/Douala')

    $dayOfWeek = $now->dayOfWeekIso; // 1 = lundi, 4 = jeudi, 7 = dimanche
    $hour      = $now->hour;        // 0 à 23

    if ($dayOfWeek !== 4 || $hour < 9 || $hour >= 18) {
        $message = "Les retraits sont uniquement possibles le <strong>jeudi de 9h00 à 18h00</strong>.";
        
        if ($dayOfWeek !== 4) {
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
        'user_id'     => $user->id,
        'type'        => 'retrait',
        'montant'     => $amount,
        'status'      => 'pending',
        'reference'   => $reference,
        'operator'    => strtoupper($user->withdrawal_method),
        'gateway'     => 'mesomb',
        'description' => "Retrait de {$amount}$ via " . strtoupper($user->withdrawal_method),
    ]);

    // =============================================
    // 6. MODE SIMULATION (local/testing)
    // =============================================
    $isSimulation = app()->environment(['local', 'testing']) || config('mesomb.test', false);

    if ($isSimulation) {
        $user->decrement('account_balance', $amount);
        $transaction->update(['status' => 'completed']);

        return back()->with('success', "Retrait de {$amount}$ SIMULÉ avec succès ! (mode test)");
    }

    // =============================================
    // 7. MODE PRODUCTION → Paiement via MeSomb
    // =============================================
    try {
        $country   = $user->country_code == 225 ? 'CI' : 'CM';
        $amountXAF = (int) round($amount * config('mesomb.usd_to_xaf', 600) * 100);

        $deposit  = new Deposit($user->withdrawal_account, $amountXAF, strtoupper($user->withdrawal_method), $country);
        $response = $deposit->pay();

        $user->decrement('account_balance', $amount);
        $transaction->update(['status' => 'completed']);

        Log::info('Retrait réussi via MeSomb', [
            'user_id'   => $user->id,
            'amount_usd'=> $amount,
            'amount_xaf'=> $amountXAF / 100,
            'phone'     => $user->withdrawal_account,
            'ref'       => $reference,
        ]);

        return back()->with('success', "Votre retrait de {$amount}$ a été soumis avec succès ! Crédit sous 3h maximum.");

    } catch (\Exception $e) {
        Log::error('Erreur retrait MeSomb', [
            'user_id' => $user->id,
            'error'   => $e->getMessage(),
        ]);

        $transaction->update(['status' => 'failed']);
        $user->increment('account_balance', $amount);

        return back()->with('error', 'Erreur technique lors du traitement. Réessayez plus tard ou contactez le support.');
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

        $totalDepots   = $transactions->where('type', 'depot')->sum('montant');
        $totalRetraits = $transactions->where('type', 'retrait')->sum('montant');

        return view('transactions', compact('transactions', 'totalDepots', 'totalRetraits'));
    }
}