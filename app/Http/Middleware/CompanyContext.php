<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompanyContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Get the current company from session or default to first company
            $currentCompanyId = session('current_company_id');

            if (!$currentCompanyId) {
                // Set default company (first active company user belongs to)
                $defaultCompany = $user->companies()->where('is_active', true)->first();
                if ($defaultCompany) {
                    session(['current_company_id' => $defaultCompany->id]);
                    $currentCompanyId = $defaultCompany->id;
                }
            }

            // Add current company to request for easy access
            if ($currentCompanyId) {
                $request->merge(['current_company_id' => $currentCompanyId]);
            }
        }

        return $next($request);
    }
}
