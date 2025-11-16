<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class PublicDashboardController extends Controller
{
    /**
     * Display the public dashboard for a company.
     */
    public function show(Company $company)
    {
        // Ensure company is active
        if (!$company->is_active) {
            abort(404, 'Company not found');
        }

        // Here you would fetch aggregated HSE metrics for the company
        // For now, we'll use placeholder data
        $metrics = [
            'total_safe_man_hours' => 125000,
            'monthly_incident_rate' => 0.8,
            'active_safety_observations' => 45,
            'days_since_lti' => 365,
        ];

        return view('public-dashboard.show', compact('company', 'metrics'));
    }
}
