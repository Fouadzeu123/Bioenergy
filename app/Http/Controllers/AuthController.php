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
    public function index()
    {
        return view('welcome');
    }

    // ==============================
    // INSCRIPTION
    // ==============================
    public function register(Request $request)
    {
        $supportedCodes = array_values(config('notchpay.country_phone_codes'));

        $request->validate([
            'phone'           => 'required|digits_between:8,12|unique:users,phone',
            'password'        => 'required|confirmed|min:6',
            'invitation_code' => 'required|string|exists:users,invitation_code',
            'country_code'    => 'nullable|in:' . implode(',', $supportedCodes),
        ], [
            'invitation_code.exists' => "Ce code d'invitation est invalide.",
            'phone.unique'           => 'Ce numéro est déjà utilisé.',
            'country_code.in'        => 'Pays non supporté.',
        ]);

        // Recherche du parrain
        $parrain = User::where('invitation_code', $request->invitation_code)->firstOrFail();

        // Pays de l'utilisateur (depuis l'indicatif)
        $phoneToCountry  = config('notchpay.phone_to_country');
        $countryCode     = $request->country_code ?? '237';
        $withdrawalCountry = $phoneToCountry[$countryCode] ?? 'CM';

        // Création de l'utilisateur
        $user = User::create([
            'country_code'       => $countryCode,
            'phone'              => $request->phone,
            'password'           => Hash::make($request->password),
            'invited_by'         => $parrain->id,
            'invitation_code'    => strtoupper(Str::random(8)),
            'account_balance'    => 0,
            'level'              => 0,
            'withdrawal_country' => $withdrawalCountry,
        ]);

        // Notification au parrain
        Notification::create([
            'user_id' => $parrain->id,
            'type'    => 'new_referral',
            'title'   => 'Nouveau filleul !',
            'content' => "{$user->phone} s'est inscrit avec votre code.",
        ]);

        Auth::login($user);
        session()->forget('referral_code');

        return redirect()->route('dashboard')
                         ->with('success', 'Inscription réussie ! Bienvenue sur BioEnergy.');
    }

    // ==============================
    // CONNEXION
    // ==============================
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $field     => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->is_banned ?? false) {
                Auth::logout();
                return back()->with('error', 'Votre compte a été suspendu. Contactez le support.');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                                 ->with('success', 'Bienvenue dans l\'administration');
            }

            return redirect()->route('dashboard')
                             ->with('success', 'Connexion réussie ! Bienvenue');
        }

        return back()->withErrors(['login' => 'Identifiants incorrects.'])->withInput();
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
