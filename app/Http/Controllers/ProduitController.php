<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Produit;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    private const INVESTMENT_DURATION_DAYS = 365; // 6 mois

    private const BONUS_RATES = [
        1 => 0.10, // 10% niveau 1 (premier investissement du filleul)
        2 => 0.03, // 3%  niveau 2
        3 => 0.01, // 1%  niveau 3
    ];

    public function index()
    {
        $produits = Produit::all();
        return view('produit', compact('produits'));
    }

    public function acheter(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        $user = Auth::user();
        $curr = $user->currency;

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = (float) $request->input('amount');

        // Vérifications
        if ($produit->min_amount && $amount < $produit->min_amount) {
            return back()->with('error', "Montant minimum : " . fmtCurrency($produit->min_amount));
        }
        if ($produit->max_amount && $amount > $produit->max_amount) {
            return back()->with('error', "Montant maximum : " . fmtCurrency($produit->max_amount));
        }

        $userPurchases = $user->orders()->where('produit_id', $produit->id)->count();
        if ($userPurchases >= $produit->limit_order) {
            return back()->with('error', 'Limite d\'achat atteinte pour ce produit.');
        }

        if ($user->account_balance < $amount) {
            return back()->with('error', 'Solde insuffisant.');
        }

        // Déduction du solde
        $user->account_balance -= $amount;
        $user->save();

        $isFirstPurchase = $user->orders()->count() === 0;

        // Mise à jour VIP
        if ($user->level < $produit->level) {
            $user->level = $produit->level;
            if (!$isFirstPurchase) {
                $user->increment('lucky_spins', 1); // +1 Tour de roue pour montée en VIP
            }
            $user->save();
        }
        if (is_null($user->vip_activated_at)) {
            $user->vip_activated_at = now();
            $user->save();
        }

        // Calcul du gain journalier
        $rate = $produit->rate ;
        $dayIncome = round(($amount * $rate) / 100); // Gain total par jour (arrondi à l'unité locale)

        // Création de la commande
        Order::create([
            'user_id'         => $user->id,
            'produit_id'      => $produit->id,
            'quantity'        => 1,
            'amount_invested' => $amount,
            'day_income'      => $dayIncome,
            'start_date'      => Carbon::now(),
            'end_date'        => Carbon::now()->addDays(self::INVESTMENT_DURATION_DAYS),
            'next_gain_date'  => Carbon::now()->addDay(),
        ]);

        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'invest',
            'montant'     => $amount,
            'status'      => 'completed',
            'reference'   => 'INV-' . strtoupper(uniqid()),
            'description' => "Investissement {$produit->name} : " . fmtCurrency($amount),
        ]);

        // Bonus parrainage (premier achat du produit spécifique)
        if ($userPurchases === 0) {
            $this->attribuerBonusParrainage($user, $amount, $produit->name);
        }

        // Bonus de bienvenue (après le tout premier achat global)
        $totalOrders = $user->orders()->count();
        if ($totalOrders === 1) {
            $bonusAmount = 500; // Montant du bonus
            $user->increment('account_balance', $bonusAmount);
            $user->increment('lucky_spins', 1); // +1 Tour de roue pour le 1er achat

            Transaction::create([
                'user_id'     => $user->id,
                'type'        => 'bonus',
                'montant'     => $bonusAmount,
                'status'      => 'completed',
                'reference'   => 'WELCOME-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'description' => 'Bonus de bienvenue offert après votre premier investissement',
            ]);

            Notification::create([
                'user_id' => $user->id,
                'type'    => 'bonus',
                'content' => "Félicitations ! Vous avez reçu un bonus de " . fmtCurrency($bonusAmount) . " et 1 tour de roue gratuit après votre premier investissement.",
            ]);
        }

        return back()->with('success', 'Investissement effectué avec succès !' . ($totalOrders === 1 ? ' Vous avez reçu un bonus de bienvenue et un tour de roue !' : ''));
    }

    private function attribuerBonusParrainage(User $filleul, float $amount, string $productName): void
    {
        $parrain = $filleul->parrain;
        $level = 1;

        while ($parrain && $level <= 3) {
            $bonusRate = self::BONUS_RATES[$level] ?? 0;
            $bonusAmount = round($amount * $bonusRate); // Arrondi pour la devise locale

            if ($bonusAmount > 0) {
                $parrain->increment('account_balance', $bonusAmount);

                // +1 Tour de roue pour le parrain de niveau 1 lors du 1er achat du filleul
                if ($level === 1) {
                    $parrain->increment('lucky_spins', 1);
                }

                Transaction::create([
                    'user_id'     => $parrain->id,
                    'type'        => 'bonus_vip',
                    'montant'     => $bonusAmount,
                    'from_user_id'=> $filleul->id,
                    'status'      => 'completed',
                    'reference'   => 'BONUS-' . uniqid(),
                    'description' => "Bonus niveau {$level} sur investissement de {$filleul->phone}",
                ]);

                Notification::create([
                    'user_id' => $parrain->id,
                    'type'    => 'bonus',
                    'content' => "Bonus de parrainage reçu : +" . fmtCurrency($bonusAmount, $parrain->currency) . ($level === 1 ? " + 1 Tour de roue gratuit !" : ""),
                ]);
            }

            $parrain = $parrain->parrain;
            $level++;
        }
    }

    public function mesProduits()
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with('produit')
            ->orderByDesc('created_at')
            ->get();

        $revenusJournee = $user->transactions()
            ->where('type', 'gain_journalier')
            ->whereDate('created_at', Carbon::today())
            ->sum('montant');

        // Données pour le modal JavaScript
        $ordersForJs = $orders->map(function ($order) {
            $user = Auth::user();
            $p = $order->produit;

            $invested = $order->amount_invested ?? 0;
            $dayIncome = $order->day_income ?? 0;

            $start = Carbon::parse($order->start_date);
            $end   = Carbon::parse($order->end_date);
            $now   = Carbon::now();

            $daysPassed    = $start->diffInDays($now);
            $daysRemaining = max(365, $end->diffInDays($now, false));
            $earnedSoFar   = Transaction::where('user_id',$user->id)
                ->where('type', 'gain_journalier')
                ->where('order_id', $order->id) // Spécifique à cet ordre
                ->sum('montant');
            $totalDays     = $start->diffInDays($end);
            $progress      = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100, 1) : 100;

            return [
                'id'            => $order->id,
                'produitName'   => $p->name,
                'description'   => $p->description,
                'information'   => $p->information,
                'invested'      => $invested,
                'dayIncome'     => $dayIncome,
                'earnedSoFar'   => $earnedSoFar,
                'start'         => $start->format('d/m/Y'),
                'end'           => $end->format('d/m/Y'),
                'quantity'      => $order->quantity,
                'progress'      => $progress,
                'daysPassed'    => $daysPassed,
                'daysRemaining' => $daysRemaining,
            ];
        })->toArray();

        $claimableAmount = 0;
        foreach ($orders as $order) {
            $today = Carbon::today()->startOfDay();

            $validGainDay = !$today->isSunday() &&
                            $today->isAfter(Carbon::parse($order->start_date)->startOfDay()) &&
                            ($order->last_gain_at === null || Carbon::parse($order->last_gain_at)->startOfDay()->lt($today));

            if ($validGainDay) {
                $claimableAmount += (float) $order->day_income;
            }
        }

        return view('mes_produit', compact('orders', 'revenusJournee', 'ordersForJs', 'claimableAmount'));
    }

    public function claimGains(\App\Services\SystemAutomationService $automationService)
    {
        $user = Auth::user();

        $balanceBefore = $user->account_balance;

        // Traite les gains pour tous les ordres éligibles
        $automationService->processGains($user);

        $user->refresh();

        $gains = $user->account_balance - $balanceBefore;

        if ($gains > 0) {
            return back()->with('success', "Vous avez réclamé " . fmtCurrency($gains, $user->currency) . " de gains avec succès !");
        } else {
            return back()->with('error', "Aucun gain disponible à réclamer pour le moment.");
        }
    }
}
