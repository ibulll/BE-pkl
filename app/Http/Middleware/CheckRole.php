<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        // Check if the user has the specified role
        if ($request->user() && $request->user()->role_id == $role) {
            return $next($request);
        }

        // Redirect or respond according to your application's logic
        return redirect('/unauthorized'); // You can customize the unauthorized page URL
    }
}
