<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // If the URL starts with /admin, redirect to admin login
            if (str_starts_with($request->path(), 'admin')) {
                return route('admin.login');
            }
            // Otherwise redirect to regular login
            return route('login');
        }
        return null;
    }
} 