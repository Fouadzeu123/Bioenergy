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
        
        // Utilisateurs fictifs pour le tableau défilant
        $fictiveWinners = [
            ['name' => 'Kouassi B.', 'prize' => 500, 'time' => 'Il y a 2 min'],
            ['name' => 'Moussa T.', 'prize' => 1200, 'time' => 'Il y a 5 min'],
            ['name' => 'Nathalie O.', 'prize' => 500, 'time' => 'Il y a 8 min'],
            ['name' => 'Jean-Paul M.', 'prize' => 5000, 'time' => 'Il y a 12 min'],
            ['name' => 'Aïcha D.', 'prize' => 500, 'time' => 'Il y a 15 min'],
            ['name' => 'Patrick K.', 'prize' => 8000, 'time' => 'Il y a 20 min'],
            ['name' => 'Saliou S.', 'prize' => 500, 'time' => 'Il y a 25 min'],
            ['name' => 'Marie L.', 'prize' => 500, 'time' => 'Il y a 30 min'],
        ];

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

        if ($globalCount % 120 === 0) {
            $prize = 'Power Bank';
        } elseif ($globalCount % 70 === 0) {
            $prize = 'Ventilateur';
        } elseif ($globalCount % 30 === 0) {
            $prize = 'Montre connectée';
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
