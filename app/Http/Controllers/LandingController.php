<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page with company cards.
     */
    public function index(Request $request)
    {
        $viewMode = $request->get('view', 'grid'); // 'grid' or 'list'

        // Fetch active companies
        $companies = Company::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('landing.index', compact('companies', 'viewMode'));
    }
}
