<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BonusCode;
use App\Models\Transaction;

class BonusController extends Controller
{
    public function index()
    {
        $historique = Transaction::where('user_id', Auth::id())
            ->where('type', 'cadeau')
            ->orderByDesc('created_at')
            ->get();
            
        return view('bonus', compact('historique'));
    }

public function reclamer(Request $request)
{
    $request->validate([
        'code' => 'required|string',
    ]);

    $code = strtoupper(trim($request->code));
    $user = Auth::user();

    $bonus = BonusCode::where('code', $code)->where('is_active', true)->first();

    if (!$bonus) {
        return back()->with('error', 'Oops! Ce code bonus est invalide ou expiré ❌');
    }

    // Vérifie si l’utilisateur a déjà utilisé ce code
    if ($bonus->users()->where('user_id', $user->id)->exists()) {
        return back()->with('error', 'Désolé! Vous avez déjà utilisé ce code bonus ❌');
    }

    // Vérifie la limite d’usage dynamique
    $nombreUtilisations = $bonus->users()->count();
    if ($nombreUtilisations >= $bonus->max_usage) {
        return back()->with('error', "Oops! Ce code bonus a atteint sa limite ❌");
    }

    // ✅ Vérifie si l’utilisateur a acheté au moins un produit énergétique
    $aAcheteProduitEnergetique = Transaction::where('user_id', $user->id)
        ->where('type', 'invest')
        ->exists();

    if (!$aAcheteProduitEnergetique) {
        return back()->with('error', "⚡Ce bonus est réservé aux clients ayant acheté un produit énergétique ❌");
    }

    // Crédite le bonus
    $user->increment('account_balance', $bonus->montant);

    // Enregistre la transaction
    Transaction::create([
        'user_id'     => $user->id,
        'type'        => 'cadeau',
        'montant'     => $bonus->montant,
        'status'      => 'completed',
        'reference'   => uniqid('BON-'),
        'description' => "Bonus du système reçu via code : {$bonus->code}",
    ]);

    // Marque comme utilisé
    $bonus->users()->attach($user->id);

    return back()->with('success', "🎉 Bonus de " . fmtCurrency($bonus->montant) . " crédité avec succès !");
}
}