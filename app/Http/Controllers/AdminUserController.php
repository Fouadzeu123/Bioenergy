<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);

        $query = User::query()
            ->with(['parrain:id,phone'])
            ->select('id', 'phone', 'email', 'account_balance', 'role', 'level', 'invited_by', 'invitation_code', 'created_at');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('invitation_code', 'like', "%{$search}%");
            });
        }

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // On récupère les utilisateurs avec les sommes calculées
        $users = $query->orderByDesc('created_at')
                       ->paginate($perPage)
                       ->withQueryString();

        // On ajoute les totaux de dépôts/retraits à chaque utilisateur
        $users->getCollection()->transform(function ($user) {
            $user->total_deposits = $user->transactions()
                ->where('type', 'depot')
                ->where('status', 'completed')
                ->sum('montant');

            $user->total_withdrawals = $user->transactions()
                ->where('type', 'retrait')
                ->where('status', 'completed')
                ->sum('montant');

            return $user;
        });

        return view('admin.users-index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with([
                'parrain:id,phone',
                'transactions' => fn($q) => $q->latest()->take(40)
            ])
            ->findOrFail($id);

        // Calcul des totaux réels en USD
        $user->total_deposits = $user->transactions()
            ->where('type', 'depot')
            ->where('status', 'completed')
            ->sum('montant');

        $user->total_withdrawals = $user->transactions()
            ->where('type', 'retrait')
            ->where('status', 'completed')
            ->sum('montant');

        return view('admin.users-show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users-edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'phone'           => 'nullable|string|max:20',
            'email'           => 'required|email|max:255',
            'account_balance' => 'required|numeric|min:0',
            'lucky_spins'     => 'required|integer|min:0',
            'role'            => 'required|in:user,admin',
            'level'           => 'required|integer|min:0|max:10',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only([
            'phone', 'email', 'account_balance', 'lucky_spins', 'role', 'level'
        ]));

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function addLuckySpins(Request $request, $id)
    {
        $request->validate([
            'spins' => 'required|integer|min:1',
        ]);

        $user = User::findOrFail($id);
        $user->increment('lucky_spins', $request->spins);

        return back()->with('success', "{$request->spins} tours de roue ajoutés à {$user->phone}");
    }

    public function addBonus(Request $request, $id)
    {
        $request->validate([
            'montant'     => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);

        $user->increment('account_balance', $request->montant);

        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'bonus_admin',
            'montant'     => $request->montant,
            'status'      => 'completed',
            'reference'   => 'ADM-' . strtoupper(Str::random(8)),
            'description' => $request->description ?? 'Bonus ajouté par l’administrateur',
        ]);

        return back()->with('success', "Bonus de {$request->montant} {$user->currency} ajouté");
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = Str::random(8);

        $user->update(['password' => Hash::make($newPassword)]);

        return back()
            ->with('success', "Mot de passe réinitialisé : <strong>{$newPassword}</strong>")
            ->with('password_reset', $newPassword);
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de bannir un administrateur');
        }

        $user->update(['is_banned' => true]);

        return back()->with('success', 'Utilisateur banni');
    }

    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => false]);

        return back()->with('success', 'Utilisateur réactivé');
    }
}