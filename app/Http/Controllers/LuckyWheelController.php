<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LuckyWheelController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Utilisateurs fictifs pour le tableau défilant (Numéros de téléphone masqués)
        $fictiveWinners = [];
        $possiblePrizes = [500, 1200, 500, 5000, 500, 500, 5000, 150000];
        $timeMinutes = 1;

        for ($i = 0; $i < 40; $i++) {
            $country = rand(1, 100) > 30 ? '237' : '225'; // 70% Cameroun, 30% CI
            if ($country === '237') {
                $prefixes = ['650', '651', '652', '653', '655', '656', '657', '658', '659', '670', '671', '672', '673', '680', '681', '682', '683', '690', '691', '692', '693', '694', '695', '696'];
                $p = $prefixes[array_rand($prefixes)];
            } else {
                $prefixes = ['01', '05', '07'];
                $p = $prefixes[array_rand($prefixes)];
            }

            $mid = str_pad(rand(10, 99), 2, '0', STR_PAD_LEFT);
            $last = str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);

            $phone = "+{$country} {$p}**{$mid}**{$last}";

            $prize = $possiblePrizes[array_rand($possiblePrizes)];
            $timeMinutes += rand(1, 4);

            $fictiveWinners[] = [
                'phone' => $phone,
                'prize' => $prize,
                'time'  => "Il y a {$timeMinutes} min"
            ];
        }

        return view('luckywheel', compact('user', 'fictiveWinners'));
    }

    public function spin()
    {
        $user = Auth::user();

        if ($user->lucky_spins <= 0) {
            return response()->json(['error' => 'Pas de tours disponibles.'], 403);
        }

        // Décrémenter les tours
        $user->decrement('lucky_spins', 1);

        // Incrémenter le compteur global
        DB::table('lucky_wheel_configs')->where('id', 1)->increment('total_spins_global');
        $globalCount = DB::table('lucky_wheel_configs')->where('id', 1)->value('total_spins_global');

        // Déterminer le prix
        $prize = 500; // Prix par défaut

        if ($globalCount % 1200 === 0) {
            $prize = 1200;
        } elseif ($globalCount % 700 === 0) {
            $prize = 2500;
        } elseif ($globalCount % 300 === 0) {
            $prize = 5000;
        } elseif (rand(1, 2000) === 777) {
            $prize = 150000;
        }

        // Créditer l'utilisateur uniquement si c'est du cash
        if (is_numeric($prize)) {
            $user->increment('account_balance', $prize);
        }

        // Enregistrer la transaction
        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'bonus',
            'montant'     => $prize,
            'status'      => 'completed',
            'reference'   => 'LUCKY-' . strtoupper(uniqid()),
            'description' => "Gain Lucky Wheel : " . (is_numeric($prize) ? fmtCurrency($prize) : $prize),
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => 'bonus',
            'content' => "Bravo ! Vous avez gagné " . (is_numeric($prize) ? fmtCurrency($prize) : $prize) . " sur la Lucky Wheel !",
        ]);

        return response()->json([
            'prize' => $prize,
            'new_balance' => fmtCurrency($user->account_balance),
            'remaining_spins' => $user->lucky_spins,
            'message' => "Félicitations ! Vous avez gagné " . (is_numeric($prize) ? fmtCurrency($prize) : $prize)
        ]);
    }
}
