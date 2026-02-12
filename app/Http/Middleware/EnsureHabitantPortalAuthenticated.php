<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Habitant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHabitantPortalAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $habitantId = $request->session()->get('portal_habitant_id');

        if (! $habitantId || ! Habitant::find($habitantId)) {
            return redirect()->route('portal.login')->with('error', 'Veuillez vous connecter.');
        }

        return $next($request);
    }
}
