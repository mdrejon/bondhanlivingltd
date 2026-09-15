<?php

namespace App\Http\Middleware;

use App\Support\ModuleRegistry;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Unauthenticated — let the auth middleware handle it
        if (!$user) return $next($request);

        // Inactive user → log out
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account has been deactivated.']);
        }

        // Super admins bypass all checks
        if ($user->isSuperAdmin()) return $next($request);

        $routeName = $request->route()?->getName() ?? '';
        $module    = ModuleRegistry::resolveModule($routeName);

        // Route not mapped to a module → allow through
        if (!$module) return $next($request);

        $action = ModuleRegistry::resolveAction($request->method(), $routeName);

        if (!$user->hasPermission($module, $action)) {
            if ($request->header('X-Inertia')) {
                return Inertia::render('Admin/Errors/Forbidden', [
                    'module' => $module,
                    'action' => $action,
                ])->toResponse($request)->setStatusCode(403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access this section.');
        }

        return $next($request);
    }
}
