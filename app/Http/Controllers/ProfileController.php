<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Epargne;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class ProfileController extends Controller
{
    private const RATE_FCFA_PER_USD = 600;

    private const INCOME_TYPES = [
        'gain_journalier',
        'bonus_journalier',
        'bonus_vip',
        'bonus',
        'cadeau',
        'remboursement_preservation',
    ];

    public function index()
    {
        $user = Auth::user();
        $rate = config('notchpay.usd_to_xaf', 600);
        $rateFCFAperUSD = $rate;
        $now  = Carbon::now();

        // 1. Revenus Total (Coherent with INCOME_TYPES)
        $revenu_total_usd = Transaction::where('user_id', $user->id)
            ->whereIn('type', self::INCOME_TYPES)
            ->where('status', 'completed')
            ->sum('montant');
        $revenu_total_fcfa = round($revenu_total_usd * $rate);

        // 1. Revenus (journalier, mensuel, annuel)
        $revenus = $this->calculateIncome($user->id, $now);

        // 2. Solde actuel
        $solde_total_usd   = round($user->account_balance ?? 0, 2);
        $solde_total_fcfa  = round($solde_total_usd * $rate);

        // 3. Total retraits validés
        $total_retraits_usd  = Transaction::where('user_id', $user->id)
            ->where('type', 'retrait')
            ->where('status', 'completed')
            ->sum('montant');

        $total_retraits_fcfa = round($total_retraits_usd * $rate);

        // 4. Dernières transactions (5)
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($tx) use ($rate) {
                $tx->montant_usd  = round($tx->montant, 2);
                $tx->montant_fcfa = round($tx->montant * $rate);
                return $tx;
            });

        // 5. Équipe sur 3 niveaux + revenu équipe
        [$taille_equipe, $revenu_equipe_usd] = $this->calculateTeamData($user->id);

        $revenu_equipe_fcfa = round($revenu_equipe_usd * $rate);

        // 6. Bénéfices a  capturer par jour
        $capturer_benefices_usd = Order::where('user_id', $user->id)
            ->sum('day_income');

        $capturer_benefices_fcfa = round($capturer_benefices_usd * $rate);

        // 7. Dépôt
        $fonds_recharge_usd = Transaction::where('user_id', $user->id)
            ->where('type', 'depot')
            ->where('status', 'completed')
            ->sum('montant');

        $fonds_recharge_fcfa = round($fonds_recharge_usd * $rate);

        // 8. Revenu épargne attendu
        $revenu_epargne_usd=Epargne::where('user_id',$user->id)->where('is_closed',false)->sum('revenu_attendu');
        $revenu_epargne_fcfa=round($revenu_epargne_usd * $rate);
        // 8. Total épargne fixe
        $total_epargne_usd = Epargne::where('user_id',$user->id)->where('is_closed',false)->sum('amount');
        $total_epargne_fcfa = round($total_epargne_usd * $rate);

        return view('profile', compact(
            'revenus',
            
            'solde_total_usd',
            'solde_total_fcfa',
            'total_retraits_usd',
            'total_retraits_fcfa',
            'transactions',
            'taille_equipe',
            'revenu_total_usd',
            'revenu_total_fcfa',
            'revenu_equipe_usd',
            'revenu_equipe_fcfa',
            'capturer_benefices_usd',
            'capturer_benefices_fcfa',
            'fonds_recharge_usd',
            'fonds_recharge_fcfa',
            'revenu_epargne_usd',
            'revenu_epargne_fcfa',
            'total_epargne_usd',
            'total_epargne_fcfa',
            'rateFCFAperUSD'
        ));
    }

    private function calculateIncome(int $userId, Carbon $now): array
    {
        $baseQuery = Transaction::where('user_id', $userId)
            ->whereIn('type', self::INCOME_TYPES);

        $journalier = (clone $baseQuery)->whereDate('created_at', $now->copy()->today())->sum('montant');
        $mensuel    = (clone $baseQuery)->whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->sum('montant');
        $annuel     = (clone $baseQuery)->whereYear('created_at', $now->year)->sum('montant');

        $rate = config('notchpay.usd_to_xaf', 600);

        return [
            'journalier'       => round($journalier, 2),
            'mensuel'          => round($mensuel, 2),
            'annuel'           => round($annuel, 2),
            'journalier_fcfa'  => round($journalier * $rate),
            'mensuel_fcfa'     => round($mensuel * $rate),
            'annuel_fcfa'      => round($annuel * $rate),
        ];
    }

    private function calculateTeamData(int $leaderId): array
    {
        $level1 = User::where('invited_by', $leaderId)->pluck('id');
        $level2 = User::whereIn('invited_by', $level1)->pluck('id');
        $level3 = User::whereIn('invited_by', $level2)->pluck('id');

        $teamIds = $level1->merge($level2)->merge($level3)->unique();

        $teamSize = $teamIds->count();

        $teamIncomeUsd = $teamIds->isEmpty()
            ? 0
            : Transaction::whereIn('user_id', $teamIds)
                ->whereIn('type', self::INCOME_TYPES)
                ->where('status', 'completed')
                ->sum('montant');

        return [$teamSize, round($teamIncomeUsd, 2)];
    }


    // ===================================================================
    // Autres méthodes (mise à jour profil, etc.)
    // ===================================================================

    public function edit()
    {
        return view('profile-edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username'        => 'required|string|max:255',
            'phone'           => 'nullable|string|max:30',
            'email'           => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'current_password'=> 'nullable|required_with:password',
            'password'        => 'nullable|min:8|confirmed',
        ]);

        $user->fill($request->only(['username', 'phone', 'email']));

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect']);
            }

            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès !');
    }

    public function updateEmailPreferences(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:users,email,' . Auth::id()]);

        Auth::user()->update(['email' => $request->email]);

        return back()->with('success', 'Email mis à jour avec succès ! Vous recevrez désormais les notifications.');
    }
}