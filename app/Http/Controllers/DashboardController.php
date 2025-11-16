<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Switch company context.
     */
    public function switchCompany(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        // Verify user belongs to this company
        $user = auth()->user();
        $belongsToCompany = $user->companies()->where('company_id', $request->company_id)->exists();

        if (!$belongsToCompany) {
            return redirect()->back()->with('error', 'You do not have access to this company.');
        }

        // Set company context in session
        session(['current_company_id' => $request->company_id]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'company_id' => $request->company_id,
            'action' => 'Switched to company: ' . \App\Models\Company::find($request->company_id)->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Company switched successfully.');
    }
}
