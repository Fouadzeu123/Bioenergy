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
            'phone'           => 'required|digits_between:8,12|unique:users,phone',
            'password'        => 'required|confirmed|min:6',
            'invitation_code' => 'required|string|exists:users,invitation_code',
            'country_code'    => 'nullable|in:237,225',
        ], [
            'invitation_code.exists' => 'Ce code d\'invitation est invalide.',
            'phone.unique'           => 'Ce numéro est déjà utilisé.',
            'username.unique'        => 'Ce nom d\'utilisateur est déjà pris.',
            'country_code.in'        => 'Pays non supporté.',
        ]);

        // Recherche du parrain
        $parrain = User::where('invitation_code', $request->invitation_code)->firstOrFail();

        // Pays de l'utilisateur
        $countryCode = $request->country_code === '225' ? '225' : '237';
        $withdrawalCountry = $countryCode === '225' ? 'CI' : 'CM';

        // Création de l'utilisateur
        $user = User::create([
            'username'          => $request->username,
            'country_code'      => $countryCode,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'invited_by'        => $parrain->id,
            'invitation_code'   => strtoupper(Str::random(8)),
            'account_balance'   => 0,
            'level'             => 0,
            'withdrawal_country'=> $withdrawalCountry,
        ]);

        // Notification au parrain
        Notification::create([
            'user_id' => $parrain->id,
            'type'    => 'new_referral',
            'title'   => 'Nouveau filleul !',
            'content' => "{$user->username} s’est inscrit avec votre code.",
        ]);

        // Bonus de bienvenue (10 $)
        $user->increment('account_balance', 6000);

        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'bonus',
            'montant'     => 6000,
            'status'      => 'completed',
            'reference'   => 'WELCOME-' . strtoupper(Str::random(6)),
            'description' => 'Bonus de bienvenue offert par BioEnergy',
        ]);

        // Connexion automatique
        Auth::login($user);

        // Nettoyage de la session
        session()->forget('referral_code');

        return redirect()->route('dashboard')
                         ->with('success', 'Inscription réussie ! Vous avez reçu 6 000 ' . $user->currency . ' de bonus');
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
