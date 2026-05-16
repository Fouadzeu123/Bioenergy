<?php
namespace App\Http\Controllers;

use App\Models\Preservation;
use App\Models\Epargne;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PreservationController extends Controller
{
    /**
     * Afficher la liste des fonds de préservation
     */
public function index()
{
    $fonds = Preservation::all();
    $mesEpargnes = Epargne::where('user_id', Auth::id())->with('preservation')->where('is_closed',false)->get();

    return view('fond_preservation', [
        'fonds' => $fonds,
        'mesEpargnes' => $mesEpargnes,
        'afficherEpargnesSeulement' => false,
    ]);
}

public function mesEpargnes()
{
    $mesEpargnes = Epargne::where('user_id', Auth::id())->with('preservation')->where('is_closed',false)->get();

    return view('fond_preservation', [
        'fonds' => collect(),
        'mesEpargnes' => $mesEpargnes,
        'afficherEpargnesSeulement' => true,
    ]);
}

    /**
     * Investir dans un fond de préservation
     */
    public function epagner(Request $request, $id)
    {
        $fond = Preservation::findOrFail($id);
        $user = Auth::user();
        $request->validate([
            'amount' => 'required|integer|min:' . $fond->min_amount,
        ]);

        if ($user->account_balance < $request->amount) {
            return redirect()->back()->with('error', 'Solde insuffisant ❌');
        }

        // Déduire le montant du solde
        $user->account_balance -= $request->amount;
        $user->save();

        // Calcul du revenu attendu
        $revenu = $request->amount * ($fond->rate / 100);

        // Enregistrer l'épargne
        Epargne::create([
            'user_id'        => $user->id,
            'preservation_id'=> $fond->id,
            'amount'         => $request->amount,
            'revenu_attendu' => $revenu,
            'start_date'     => now(),
            'end_date'       => now()->addDays($fond->period_days),
            'is_closed'      => false, // champ booléen à ajouter dans ta migration
        ]);

        // Enregistrer la transaction d'investissement
        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'investissement_preservation',
            'montant'     => $request->amount,
            'status'      => 'completed',
            'reference'   => uniqid('INV-'),
            'description' => "Investissement dans {$fond->name} pour {$fond->period_days} jours",
        ]);

        return redirect()->back()->with('success', 'Épargne enregistrée avec succès ✅');
    }

    /**
     * Vérifier et rembourser les épargnes arrivées à échéance
     */
    public function rembourser()
    {
        $epargnes = Epargne::where('end_date', '<=', Carbon::today())
            ->where('is_closed', false)
            ->get();

        foreach ($epargnes as $epargne) {
            $user = $epargne->user;
            $total = $epargne->amount + $epargne->revenu_attendu;

            // Créditer le solde utilisateur
            $user->increment('account_balance', $total);

            // Enregistrer la transaction de remboursement
            Transaction::create([
                'user_id'     => $user->id,
                'type'        => 'remboursement_preservation',
                'montant'     => $total,
                'status'      => 'completed',
                'reference'   => uniqid('REM-'),
                'description' => "Remboursement du capital + revenu pour {$epargne->preservation->name}",
            ]);

            // Marquer l’épargne comme terminée
            $epargne->is_closed = true;
            $epargne->save();
        }

        return redirect()->back()->with('success', 'Les épargnes arrivées à échéance ont été remboursées ✅');
    }
}