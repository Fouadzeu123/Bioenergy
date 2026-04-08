<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class WithdrawalInfoController extends Controller
{
    public function index()
    {
        return view('info_retrait'); // $user est déjà disponible via Auth dans la vue
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation des champs
        $request->validate([
            'current_password'           => 'required|string', // OBLIGATOIRE : mot de passe du compte
            'withdrawal_method'          => 'required|in:MTN,ORANGE',
            'withdrawal_account'         => [
                'required',
                'string',
                'min:8',
                'max:20',
                Rule::unique('users', 'withdrawal_account')->ignore($user->id),
            ],
            'withdrawal_name'            => 'required|string|max:100',
            'withdrawal_password'        => 'required|min:6|confirmed', // Toujours obligatoire
            'withdrawal_password_confirmation' => 'required',
        ], [
            'current_password.required'           => 'Vous devez confirmer votre mot de passe de connexion.',
            'withdrawal_method.required'          => 'Veuillez sélectionner un opérateur.',
            'withdrawal_account.required'         => 'Le numéro Mobile Money est obligatoire.',
            'withdrawal_account.unique'           => 'Ce numéro est déjà utilisé par un autre compte.',
            'withdrawal_password.required'        => 'Le mot de passe de retrait est obligatoire.',
            'withdrawal_password.confirmed'       => 'Les mots de passe de retrait ne correspondent pas.',
            'withdrawal_password.min'             => 'Le mot de passe de retrait doit contenir au moins 6 caractères.',
        ]);

        // Vérification du mot de passe du compte (celui de login)
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mot de passe du compte incorrect. Veuillez réessayer.');
        }

        // Mise à jour des informations
        $user->update([
            'withdrawal_method'   => strtoupper($request->withdrawal_method),
            'withdrawal_account'  => $request->withdrawal_account,
            'withdrawal_name'     => $request->withdrawal_name,
            'withdrawal_password' => Hash::make($request->withdrawal_password),
        ]);

        return back()->with('success', 'Informations de retrait mises à jour avec succès ! Vous pouvez maintenant retirer vos gains en toute sécurité.');
    }
}