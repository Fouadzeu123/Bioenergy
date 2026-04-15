<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class EmploiController extends Controller
{
    /**
     * Postes disponibles avec leurs conditions et revenus mensuels.
     */
    public static function postes(): array
    {
        return [
            [
                'id'           => 'agent',
                'titre'        => 'Agent Débutant',
                'emoji'        => '🌱',
                'gradient'     => 'from-emerald-400 to-teal-500',
                'bg_light'     => 'bg-emerald-50',
                'border'       => 'border-emerald-200',
                'badge_color'  => 'bg-emerald-100 text-emerald-700',
                'revenu'       => 50,
                'description'  => 'Premier échelon de la famille BioEnergy. Accompagnez de nouveaux membres et commencez à percevoir un revenu mensuel récurrent.',
                'conditions'   => [
                    'filleuls_directs' => 10,
                    'depot_equipe'     => 200,
                    'depot_propre'     => 50,
                ],
            ],
            [
                'id'           => 'commercial',
                'titre'        => 'Agent Commercial',
                'emoji'        => '💼',
                'gradient'     => 'from-blue-500 to-indigo-600',
                'bg_light'     => 'bg-blue-50',
                'border'       => 'border-blue-200',
                'badge_color'  => 'bg-blue-100 text-blue-700',
                'revenu'       => 150,
                'description'  => 'Vous constituez une équipe solide et générez un volume d\'affaires croissant. Un revenu mensuel garanti récompense votre engagement.',
                'conditions'   => [
                    'filleuls_directs' => 15,
                    'depot_equipe'     => 500,
                    'depot_propre'     => 200,
                ],
            ],
            [
                'id'           => 'superviseur',
                'titre'        => 'Superviseur',
                'emoji'        => '⭐',
                'gradient'     => 'from-violet-500 to-purple-600',
                'bg_light'     => 'bg-violet-50',
                'border'       => 'border-violet-200',
                'badge_color'  => 'bg-violet-100 text-violet-700',
                'revenu'       => 400,
                'description'  => 'Vous supervisez plusieurs agents et coordonnez une équipe performante. Votre influence se traduit par un revenu conséquent.',
                'conditions'   => [
                    'filleuls_directs' => 30,
                    'depot_equipe'     => 2000,
                    'depot_propre'     => 500,
                ],
            ],
            [
                'id'           => 'manager',
                'titre'        => 'Manager',
                'emoji'        => '🏆',
                'gradient'     => 'from-orange-500 to-red-500',
                'bg_light'     => 'bg-orange-50',
                'border'       => 'border-orange-200',
                'badge_color'  => 'bg-orange-100 text-orange-700',
                'revenu'       => 800,
                'description'  => 'Vous dirigez une organisation prospère. Votre leadership et votre réseau étendu vous permettent d\'accéder à un revenu premium.',
                'conditions'   => [
                    'filleuls_directs' => 50,
                    'depot_equipe'     => 5000,
                    'depot_propre'     => 1000,
                ],
            ],
            [
                'id'           => 'directeur',
                'titre'        => 'Directeur Régional',
                'emoji'        => '👑',
                'gradient'     => 'from-yellow-400 to-amber-500',
                'bg_light'     => 'bg-yellow-50',
                'border'       => 'border-yellow-200',
                'badge_color'  => 'bg-yellow-100 text-yellow-800',
                'revenu'       => 2000,
                'description'  => 'Sommet de la hiérarchie BioEnergy. Vous pilotez un réseau d\'envergure régionale et bénéficiez du revenu le plus élevé de la plateforme.',
                'conditions'   => [
                    'filleuls_directs' => 100,
                    'depot_equipe'     => 15000,
                    'depot_propre'     => 3000,
                ],
            ],
        ];
    }

    public function index()
    {
        $user = Auth::user();

        // --- Statistiques de l'utilisateur ---

        // 1. Filleuls directs
        $filleulsDirects = User::where('invited_by', $user->id)->count();

        // 2. IDs de l'équipe (3 niveaux)
        $niveau1Ids = User::where('invited_by', $user->id)->pluck('id');
        $niveau2Ids = User::whereIn('invited_by', $niveau1Ids)->pluck('id');
        $niveau3Ids = User::whereIn('invited_by', $niveau2Ids)->pluck('id');
        $teamIds    = $niveau1Ids->merge($niveau2Ids)->merge($niveau3Ids)->unique();

        // 3. Total dépôts de l'équipe (complétés)
        $depotEquipe = Transaction::whereIn('user_id', $teamIds)
            ->where('type', 'depot')
            ->where('status', 'completed')
            ->sum('montant');

        // 4. Propres dépôts (complétés)
        $depotPropre = Transaction::where('user_id', $user->id)
            ->where('type', 'depot')
            ->where('status', 'completed')
            ->sum('montant');

        // --- Calcul éligibilité par poste ---
        $postes = self::postes();
        foreach ($postes as &$poste) {
            $c = $poste['conditions'];
            $poste['eligible'] =
                $filleulsDirects >= $c['filleuls_directs'] &&
                $depotEquipe     >= $c['depot_equipe']     &&
                $depotPropre     >= $c['depot_propre'];

            // Progression (0-100) pour chaque critère
            $poste['progress'] = [
                'filleuls' => min(100, $filleulsDirects > 0 ? round(($filleulsDirects / $c['filleuls_directs']) * 100) : 0),
                'equipe'   => min(100, $depotEquipe > 0     ? round(($depotEquipe    / $c['depot_equipe'])     * 100) : 0),
                'propre'   => min(100, $depotPropre > 0     ? round(($depotPropre   / $c['depot_propre'])      * 100) : 0),
            ];
        }
        unset($poste);

        // Poste le plus élevé atteint
        $posteActuel = collect($postes)->last(fn($p) => $p['eligible']);

        return view('emploi', compact(
            'postes',
            'posteActuel',
            'filleulsDirects',
            'depotEquipe',
            'depotPropre'
        ));
    }
}
