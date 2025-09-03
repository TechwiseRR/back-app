<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockDeactivatedUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->is_active != 1) {
            auth()->logout();
            return response()->json([
                'error' => 'Compte désactivé',
                'message' => 'Votre compte a été désactivé. Vous ne pouvez plus accéder à cette ressource.',
            ], 403);
        }

        return $next($request);
    }
}
