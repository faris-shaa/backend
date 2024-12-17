<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        if (!$request->expectsJson()) {
            if (Str::contains($request->fullUrl(), "admin")) {
                return route("admin.login");
            } elseif (Str::contains($request->fullUrl(), "organizers")) {
                return  route("organizers.login");
            }
            return route('home');
        }
    }
}
