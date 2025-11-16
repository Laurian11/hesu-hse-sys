<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UIManagementController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'nullable|string|in:light,dark',
            'font_size' => 'nullable|string|in:small,medium,large',
            'layout' => 'nullable|string|in:compact,expanded',
            'high_contrast' => 'nullable|boolean',
            'reduced_motion' => 'nullable|boolean',
            'language' => 'nullable|string|in:en,es,fr,de',
            'notifications' => 'nullable|boolean',
            'font_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'icon_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'shadow_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'primary_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'accent_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        // Store UI preferences in user session or database
        session(['ui_preferences' => $validated]);

        return back()->with('success', 'UI preferences updated successfully.');
    }
}
