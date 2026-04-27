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
        $now  = Carbon::now();

        // 1. Revenus Total
        $revenu_total = Transaction::where('user_id', $user->id)
            ->whereIn('type', self::INCOME_TYPES)
            ->where('status', 'completed')
            ->sum('montant');

        // 2. Revenus (journalier, mensuel, annuel)
        $revenus = $this->calculateIncome($user->id, $now);

        // 3. Solde actuel
        $solde_total = (float) ($user->account_balance ?? 0);

        // 4. Total retraits validés
        $total_retraits = Transaction::where('user_id', $user->id)
            ->where('type', 'retrait')
            ->where('status', 'completed')
            ->sum('montant');

        // 5. Dernières transactions (5)
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 6. Équipe sur 3 niveaux + revenu équipe
        [$taille_equipe, $revenu_equipe] = $this->calculateTeamData($user->id);

        // 7. Bénéfices à capturer par jour
        $capturer_benefices = Order::where('user_id', $user->id)
            ->sum('day_income');

        // 8. Dépôts total
        $fonds_recharge = Transaction::where('user_id', $user->id)
            ->where('type', 'depot')
            ->where('status', 'completed')
            ->sum('montant');

        // 9. Revenu épargne attendu
        $revenu_epargne = Epargne::where('user_id', $user->id)->where('is_closed', false)->sum('revenu_attendu');

        // 10. Total épargne fixe
        $total_epargne = Epargne::where('user_id', $user->id)->where('is_closed', false)->sum('amount');

        return view('profile', compact(
            'revenus',
            'solde_total',
            'total_retraits',
            'transactions',
            'taille_equipe',
            'revenu_total',
            'revenu_equipe',
            'capturer_benefices',
            'fonds_recharge',
            'revenu_epargne',
            'total_epargne'
        ));
    }

    private function calculateIncome(int $userId, Carbon $now): array
    {
        $baseQuery = Transaction::where('user_id', $userId)
            ->whereIn('type', self::INCOME_TYPES);

        $journalier = (clone $baseQuery)->whereDate('created_at', $now->copy()->today())->sum('montant');
        $mensuel    = (clone $baseQuery)->whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->sum('montant');
        $annuel     = (clone $baseQuery)->whereYear('created_at', $now->year)->sum('montant');

        return [
            'journalier' => round($journalier),
            'mensuel'    => round($mensuel),
            'annuel'     => round($annuel),
        ];
    }

    private function calculateTeamData(int $leaderId): array
    {
        $level1 = User::where('invited_by', $leaderId)->pluck('id');
        $level2 = User::whereIn('invited_by', $level1)->pluck('id');
        $level3 = User::whereIn('invited_by', $level2)->pluck('id');

        $teamIds = $level1->merge($level2)->merge($level3)->unique();
        $teamSize = $teamIds->count();

        $teamIncome = $teamIds->isEmpty()
            ? 0
            : Transaction::whereIn('user_id', $teamIds)
                ->whereIn('type', self::INCOME_TYPES)
                ->where('status', 'completed')
                ->sum('montant');

        return [$teamSize, round($teamIncome)];
    }

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
        return back()->with('success', 'Email mis à jour avec succès !');
    }
}