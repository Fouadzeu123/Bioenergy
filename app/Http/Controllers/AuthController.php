<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Page d'accueil (optionnel)
    public function index()
    {
        return view('welcome');
    }

    // ==============================
    // INSCRIPTION
    // ==============================
    public function register(Request $request)
    {
        $request->validate([
            'username'        => 'required|string|max:30|unique:users,username',
            'country_code'    => 'required|in:237', // uniquement Cameroun pour l’instant
            'phone'           => 'required|digits_between:9,10|unique:users,phone',
            'password'        => 'required|confirmed|min:6',
            'invitation_code' => 'required|string|exists:users,invitation_code',
        ], [
            'invitation_code.exists' => 'Ce code d’invitation est invalide.',
            'phone.unique'           => 'Ce numéro est déjà utilisé.',
            'username.unique'        => 'Ce nom d’utilisateur est déjà pris.',
        ]);

        // Recherche du parrain
        $parrain = User::where('invitation_code', $request->invitation_code)->firstOrFail();

        // Création de l'utilisateur
        $user = User::create([
            'username'        => $request->username,
            'country_code'    => $request->country_code,
            'phone'           => $request->phone,
            'password'        => Hash::make($request->password),
            'invited_by'      => $parrain->id,
            'invitation_code' => strtoupper(Str::random(8)), // ex: X7K9P2M4
            'account_balance' => 10,
            'level'           => 0,
        ]);

        // Notification au parrain
        Notification::create([
            'user_id' => $parrain->id,
            'type'    => 'new_referral',
            'title'   => 'Nouveau filleul !',
            'content' => "{$user->username} s’est inscrit avec votre code.",
        ]);

        // Bonus de bienvenue (10 $)
        $user->increment('account_balance', 10);

        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'bonus',
            'montant'     => 10,
            'status'      => 'completed',
            'reference'   => 'WELCOME-' . strtoupper(Str::random(6)),
            'description' => 'Bonus de bienvenue offert par BioEnergy',
        ]);

        // Connexion automatique
        Auth::login($user);

        return redirect()->route('dashboard')
                         ->with('success', 'Inscription réussie ! Vous avez reçu 10 $ de bonus');
    }

    // ==============================
    // CONNEXION (champ unique : nom ou téléphone)
    // ==============================
    public function login(Request $request)
    {
        $request->validate([
            'login'     => 'required|string',
            'password'  => 'required|string',
            'remember'  => 'sometimes|boolean',
        ]);

        // Recherche par username OU phone
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' :
                 (preg_match('/^\d+$/', $request->login) ? 'phone' : 'username');

        $credentials = [
            $field     => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Compte banni ?
            if ($user->is_banned ?? false) {
                Auth::logout();
                return back()->with('error', 'Votre compte a été suspendu. Contactez le support.');
            }

            // Redirection selon rôle
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                                 ->with('success', 'Bienvenue dans l’administration');
            }
            else{

            return redirect()->route('dashboard')
             ->with('success', 'Connexion réussie ! Bienvenue');
            }
        }

        return back()->withErrors([
            'login' => 'Identifiants incorrects.',
        ])->withInput();
    }

    // ==============================
    // DÉCONNEXION
    // ==============================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Déconnexion réussie. À bientôt !');
    }
}