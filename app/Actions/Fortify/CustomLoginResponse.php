<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request): Response
    {
        // Check if there's a stored previous URL in session
        // This could be set by middleware (Solution 2) or passed from the frontend
        $previousUrl = $request->session()->pull('previous_url');
        
        // Use Laravel's intended URL feature as fallback
        $intendedUrl = session()->pull('url.intended', '/');
        
        // Determine which URL to redirect to
        $redirectTo = $previousUrl ?? $intendedUrl;
        
        // Make sure we're not redirecting back to login page
        if (str_contains($redirectTo, '/login') || str_contains($redirectTo, '/register')) {
            $redirectTo = '/';
        }
        
        // For Inertia requests, use Inertia::location() for external redirects
        if ($request->header('X-Inertia')) {
            return Inertia::location($redirectTo);
        }
        
        // For regular requests
        return redirect()->to($redirectTo);
    }
}