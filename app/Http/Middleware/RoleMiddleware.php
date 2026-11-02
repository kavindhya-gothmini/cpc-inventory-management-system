<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage:
     * ->middleware('role:super_admin')
     * ->middleware('role:admin,super_admin')
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        // User must be authenticated.
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Get user's role
        |--------------------------------------------------------------------------
        |
        | This supports the common role column used in the project.
        | The value is normalized so that:
        |
        | Super Admin / super_admin / SUPER_ADMIN
        |
        | are treated consistently.
        |
        */
        $userRole = strtolower(trim((string) $user->role));

        /*
        |--------------------------------------------------------------------------
        | Normalize allowed roles
        |--------------------------------------------------------------------------
        */
        $allowedRoles = collect($roles)
            ->map(function ($role) {
                return strtolower(trim($role));
            })
            ->filter()
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | No role supplied
        |--------------------------------------------------------------------------
        |
        | If this middleware is accidentally used without specifying
        | a role, deny access instead of allowing it.
        |
        */
        if (empty($allowedRoles)) {
            abort(403, 'No authorized role was specified.');
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        |
        | Super Admin is the highest-level administrative role.
        |
        | IMPORTANT:
        | We do NOT automatically allow Super Admin here when the route
        | explicitly restricts access to another role. This means the
        | route definition remains the authority.
        |
        | Example:
        |
        | role:super_admin
        |
        | Only Super Admin can access.
        |
        | role:admin,super_admin
        |
        | Admin and Super Admin can access.
        |
        */
        if (in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Unauthorized
        |--------------------------------------------------------------------------
        */
        abort(403, 'You do not have permission to access this page.');
    }
}

