<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function monLien()
    {
        $user = Auth::user();

        $niveau1 = User::where('invited_by', $user->id)->where('level', '>=', 1)->get();
        $niveau2 = User::whereIn('invited_by', $niveau1->pluck('id'))->where('level', '>=', 1)->get();
        $niveau3 = User::whereIn('invited_by', $niveau2->pluck('id'))->where('level', '>=', 1)->get();

        $total = $niveau1->count() + $niveau2->count() + $niveau3->count();
        $refUrl = route('register.view', ['ref' => $user->invitation_code]);

        return view('link', compact('niveau1', 'niveau2', 'niveau3', 'total', 'refUrl'));
    }

    public function team()
    {
        $user = Auth::user();

        // Niveau 1 : filleuls directs (collection) - Filtrés par niveau >= 1
        $niveau1 = User::where('invited_by', $user->id)->where('level', '>=', 1)->get();

        // Niveau 2 : filleuls des filleuls (collection)
        $niveau2 = collect();
        if ($niveau1->isNotEmpty()) {
            $niveau2 = User::whereIn('invited_by', $niveau1->pluck('id'))->where('level', '>=', 1)->get();
        }

        // Niveau 3 : filleuls des niveau 2 (collection)
        $niveau3 = collect();
        if ($niveau2->isNotEmpty()) {
            $niveau3 = User::whereIn('invited_by', $niveau2->pluck('id'))->where('level', '>=', 1)->get();
        }

        // Comptes par niveau
        $countN1 = $niveau1->count();
        $countN2 = $niveau2->count();
        $countN3 = $niveau3->count();

        // Taille totale de l'équipe (somme des 3 niveaux)
        // On additionne les comptes ; si tu veux éviter tout risque de doublons
        // (même si la structure de parrainage ne devrait pas en produire), on peut
        // aussi compter les ids uniques :
        $ids = collect()
            ->merge($niveau1->pluck('id'))
            ->merge($niveau2->pluck('id'))
            ->merge($niveau3->pluck('id'))
            ->unique()
            ->values();
        $taille_equipe = $ids->count();

        // 🔹 Gains de parrainage VIP (bonus attribués lors du premier achat VIP d’un filleul)
        $gainsParrainageVip = Transaction::where('user_id', $user->id)
            ->where('type', 'bonus_vip')
            ->sum('montant');

        // 🔹 Gains journaliers ( gain_journalier)
        $gainsJournalier = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['bonus_journalier'])
            ->sum('montant');

        // 🔹 Gains totaux
        $gainsTotaux = $gainsParrainageVip + $gainsJournalier;

        // Bonus généré par chaque filleul
        

        return view('equipe', compact(
            'taille_equipe',
            'niveau1',
            'niveau2',
            'niveau3',
            'gainsParrainageVip',
            'gainsJournalier',
            'gainsTotaux'
        ));
    }
}