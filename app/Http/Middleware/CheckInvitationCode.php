<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckInvitationCode
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est en train de s'inscrire
        if ($request->is('register') && $request->has('invitation_code')) {
            $code = $request->input('invitation_code');

            // Vérifier si le code existe chez un utilisateur
            $parrain = User::where('invitation_code', $code)->first();

            if (!$parrain) {
                return redirect()->back()->withErrors([
                    'invitation_code' => 'Code d’invitation invalide.'
                ]);
            }
        }

        return $next($request);
    }
}