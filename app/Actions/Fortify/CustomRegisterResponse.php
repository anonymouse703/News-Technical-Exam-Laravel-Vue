<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CustomRegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): Response
    {
        $previousUrl = $request->session()->pull('previous_url');
        $intendedUrl = session()->pull('url.intended', '/');

        $redirectTo = $previousUrl ?? $intendedUrl;

        if (str_contains($redirectTo, '/login') || str_contains($redirectTo, '/register')) {
            $redirectTo = '/';
        }

        if ($request->header('X-Inertia')) {
            return Inertia::location($redirectTo);
        }

        return redirect()->to($redirectTo);
    }
}
