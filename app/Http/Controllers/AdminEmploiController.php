<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminEmploiController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();
        $postes = EmploiController::postes();
        $eligibleUsers = [];

        foreach ($users as $user) {
            // Calcul stats similaires à EmploiController
            $filleulsDirects = User::where('invited_by', $user->id)->count();
            $niveau1Ids = User::where('invited_by', $user->id)->pluck('id');
            $niveau2Ids = User::whereIn('invited_by', $niveau1Ids)->pluck('id');
            $niveau3Ids = User::whereIn('invited_by', $niveau2Ids)->pluck('id');
            $teamIds    = $niveau1Ids->merge($niveau2Ids)->merge($niveau3Ids)->unique();

            $depotEquipe = Transaction::whereIn('user_id', $teamIds)
                ->where('type', 'depot')
                ->where('status', 'completed')
                ->sum('montant');

            $depotPropre = Transaction::where('user_id', $user->id)
                ->where('type', 'depot')
                ->where('status', 'completed')
                ->sum('montant');

            $posteAtteint = null;
            foreach (array_reverse($postes) as $poste) {
                $c = $poste['conditions'];
                if ($filleulsDirects >= $c['filleuls_directs'] && $depotEquipe >= $c['depot_equipe'] && $depotPropre >= $c['depot_propre']) {
                    $posteAtteint = $poste;
                    break;
                }
            }

            if ($posteAtteint) {
                $eligibleUsers[] = [
                    'user' => $user,
                    'poste' => $posteAtteint,
                    'stats' => [
                        'filleuls' => $filleulsDirects,
                        'equipe' => $depotEquipe,
                        'propre' => $depotPropre
                    ]
                ];
            }
        }

        return view('admin.emploi-index', compact('eligibleUsers'));
    }

    public function payAll()
    {
        $users = User::whereNotNull('poste_id')->where('role', 'user')->get();
        $postes = collect(EmploiController::postes())->keyBy('id');
        $count = 0;

        foreach ($users as $user) {
            $posteAtteint = $postes->get($user->poste_id);

            if ($posteAtteint) {
                $user->increment('account_balance', $posteAtteint['revenu']);
                
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'bonus_emploi',
                    'montant' => $posteAtteint['revenu'],
                    'status' => 'completed',
                    'reference' => 'SALARY-' . strtoupper(Str::random(8)),
                    'description' => "Salaire mensuel : " . $posteAtteint['titre']
                ]);

                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'bonus',
                    'content' => "Votre salaire mensuel de " . fmtCurrency($posteAtteint['revenu']) . " pour le poste de " . $posteAtteint['titre'] . " a été versé."
                ]);
                $count++;
            }
        }

        return back()->with('success', "Paiements effectués pour {$count} utilisateurs.");
    }

    public function assignJob(Request $request, $user)
    {
        $u = User::findOrFail($user);
        $posteId = $request->input('poste_id');

        $postes = EmploiController::postes();
        $posteExists = collect($postes)->contains('id', $posteId);

        if (!$posteExists) {
            return back()->with('error', 'Poste invalide.');
        }

        $u->poste_id = $posteId;
        $u->save();

        Notification::create([
            'user_id' => $u->id,
            'type' => 'info',
            'content' => "Félicitations ! Votre contrat d'emploi a été validé par le Service RH. Vous êtes officiellement nommé à ce poste."
        ]);

        return back()->with('success', "Le poste a été officiellement attribué à l'utilisateur.");
    }
}
