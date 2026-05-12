<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class WithdrawalInfoController extends Controller
{
    /**
     * Opérateurs autorisés par pays — tirés du config NotchPay.
     */
    public static function operatorsForCountry(string $country): array
    {
        return array_keys(config('notchpay.channels.' . strtoupper($country), []));
    }

    public function index()
    {
        return view('info_retrait');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $supportedCountries = array_keys(config('notchpay.country_phone_codes'));
        $country = strtoupper($request->input('withdrawal_country', 'CM'));
        $allowedOperators = self::operatorsForCountry($country);

        $request->validate([
            'current_password'                => 'required|string',
            'withdrawal_country'              => ['required', Rule::in($supportedCountries)],
            'withdrawal_method'               => ['required', Rule::in($allowedOperators)],
            'withdrawal_account'              => [
                'required', 'string', 'min:8', 'max:20',
                Rule::unique('users', 'withdrawal_account')->ignore($user->id),
            ],
            'withdrawal_name'                 => 'required|string|max:100',
            'withdrawal_password'             => 'required|min:6|confirmed',
            'withdrawal_password_confirmation'=> 'required',
        ], [
            'current_password.required'    => 'Vous devez confirmer votre mot de passe de connexion.',
            'withdrawal_country.required'  => 'Veuillez sélectionner votre pays.',
            'withdrawal_country.in'        => 'Pays non supporté.',
            'withdrawal_method.required'   => 'Veuillez sélectionner un opérateur.',
            'withdrawal_method.in'         => 'Opérateur invalide pour le pays sélectionné.',
            'withdrawal_account.required'  => 'Le numéro Mobile Money est obligatoire.',
            'withdrawal_account.unique'    => 'Ce numéro est déjà utilisé par un autre compte.',
            'withdrawal_password.required' => 'Le mot de passe de retrait est obligatoire.',
            'withdrawal_password.confirmed'=> 'Les mots de passe de retrait ne correspondent pas.',
            'withdrawal_password.min'      => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mot de passe du compte incorrect. Veuillez réessayer.');
        }

        $user->update([
            'withdrawal_country'  => $country,
            'withdrawal_method'   => strtoupper($request->withdrawal_method),
            'withdrawal_account'  => $request->withdrawal_account,
            'withdrawal_name'     => $request->withdrawal_name,
            'withdrawal_password' => Hash::make($request->withdrawal_password),
        ]);

        return back()->with('success', 'Informations de retrait mises à jour avec succès !');
    }
}