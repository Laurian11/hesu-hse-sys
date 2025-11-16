<?php

namespace App\Http\Controllers;

use App\Exports\CompaniesExport;
use App\Imports\CompaniesImport;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::paginate(15);
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoUrl = $request->file('logo')->store('company-logos', 'public');
        }

        $company = Company::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'logo_url' => $logoUrl,
            'is_active' => true,
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $company->id,
            'action' => 'Created company: ' . $company->name,
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company->load(['users', 'departments', 'employees']);
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $logoUrl = $company->logo_url;
        if ($request->hasFile('logo')) {
            $logoUrl = $request->file('logo')->store('company-logos', 'public');
        }

        $company->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'logo_url' => $logoUrl,
            'is_active' => $request->has('is_active'),
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $company->id,
            'action' => 'Updated company: ' . $company->name,
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Log activity before deletion
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $company->id,
            'action' => 'Deleted company: ' . $company->name,
            'ip_address' => request()->ip(),
            'timestamp' => now(),
        ]);

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    /**
     * Export companies to Excel/CSV.
     */
    public function export(Request $request)
    {
        $request->validate([
            'company_ids' => 'nullable|array',
            'company_ids.*' => 'integer|exists:companies,id',
            'format' => 'required|in:excel,csv',
        ]);

        $companyIds = $request->company_ids ?? [];
        $format = $request->format;

        $filename = 'companies_' . date('Y-m-d_H-i-s');

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()->currentCompany->id ?? null,
            'action' => 'Exported ' . (empty($companyIds) ? 'all' : count($companyIds)) . ' companies to ' . strtoupper($format),
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        if ($format === 'excel') {
            return Excel::download(new CompaniesExport($companyIds), $filename . '.xlsx');
        } else {
            return Excel::download(new CompaniesExport($companyIds), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }

    /**
     * Import companies from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:2048',
        ]);

        try {
            Excel::import(new CompaniesImport, $request->file('file'));

            $imported = session('imported_count', 0);

            // Log activity
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'company_id' => auth()->user()->currentCompany->id ?? null,
                'action' => 'Imported companies from ' . $request->file('file')->getClientOriginalName(),
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Companies imported successfully.',
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            return response()->json([
                'success' => false,
                'message' => 'Import failed due to validation errors.',
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk activate companies.
     */
    public function bulkActivate(Request $request)
    {
        $request->validate([
            'company_ids' => 'required|array',
            'company_ids.*' => 'integer|exists:companies,id',
        ]);

        $count = Company::whereIn('id', $request->company_ids)->update(['is_active' => true]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()->currentCompany->id ?? null,
            'action' => 'Bulk activated ' . $count . ' companies',
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('companies.index')->with('success', "{$count} companies activated successfully.");
    }

    /**
     * Bulk deactivate companies.
     */
    public function bulkDeactivate(Request $request)
    {
        $request->validate([
            'company_ids' => 'required|array',
            'company_ids.*' => 'integer|exists:companies,id',
        ]);

        $count = Company::whereIn('id', $request->company_ids)->update(['is_active' => false]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()->currentCompany->id ?? null,
            'action' => 'Bulk deactivated ' . $count . ' companies',
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('companies.index')->with('success', "{$count} companies deactivated successfully.");
    }

    /**
     * Bulk delete companies.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'company_ids' => 'required|array',
            'company_ids.*' => 'integer|exists:companies,id',
        ]);

        $count = Company::whereIn('id', $request->company_ids)->delete();

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()->currentCompany->id ?? null,
            'action' => 'Bulk deleted ' . $count . ' companies',
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->route('companies.index')->with('success', "{$count} companies deleted successfully.");
    }
}
