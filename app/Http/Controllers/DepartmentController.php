<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentCompanyId = request('current_company_id');
        $departments = Department::where('company_id', $currentCompanyId)
            ->with('hod')
            ->paginate(15);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentCompanyId = request('current_company_id');
        $company = Company::find($currentCompanyId);
        $potentialHods = User::whereHas('companies', function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        })->get();

        return view('departments.create', compact('company', 'potentialHods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentCompanyId = $request->get('current_company_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code,NULL,id,company_id,' . $currentCompanyId,
            'hod_user_id' => 'nullable|exists:users,id',
        ]);

        $department = Department::create([
            'company_id' => $currentCompanyId,
            'name' => $request->name,
            'code' => $request->code,
            'hod_user_id' => $request->hod_user_id,
            'is_active' => true,
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Created department: ' . $department->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure department belongs to current company
        if ($department->company_id != $currentCompanyId) {
            abort(403);
        }

        $department->load(['company', 'hod', 'employees']);
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure department belongs to current company
        if ($department->company_id != $currentCompanyId) {
            abort(403);
        }

        $company = Company::find($currentCompanyId);
        $potentialHods = User::whereHas('companies', function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        })->get();

        return view('departments.edit', compact('department', 'company', 'potentialHods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $currentCompanyId = $request->get('current_company_id');

        // Ensure department belongs to current company
        if ($department->company_id != $currentCompanyId) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:10', Rule::unique('departments')->ignore($department->id)->where('company_id', $currentCompanyId)],
            'hod_user_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $department->update([
            'name' => $request->name,
            'code' => $request->code,
            'hod_user_id' => $request->hod_user_id,
            'is_active' => $request->has('is_active'),
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Updated department: ' . $department->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure department belongs to current company
        if ($department->company_id != $currentCompanyId) {
            abort(403);
        }

        // Log activity before deletion
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Deleted department: ' . $department->name,
            'ip_address' => request()->ip(),
        ]);

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    /**
     * Export departments to Excel/CSV
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $ids = $request->get('ids', []);

        $currentCompanyId = $request->get('current_company_id');

        if (!empty($ids)) {
            $departments = Department::whereIn('id', $ids)->where('company_id', $currentCompanyId)->get();
        } else {
            $departments = Department::where('company_id', $currentCompanyId)->get();
        }

        if ($format === 'csv') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DepartmentsExport($departments), 'departments.csv');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DepartmentsExport($departments), 'departments.xlsx');
    }

    /**
     * Import departments from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $currentCompanyId = $request->get('current_company_id');

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\DepartmentsImport($currentCompanyId), $request->file('file'));

            // Log activity
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'company_id' => $currentCompanyId,
                'action' => 'Imported departments from file',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('departments.index')->with('success', 'Departments imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('departments.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Bulk activate departments
     */
    public function bulkActivate(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('departments.index')->with('error', 'No departments selected.');
        }

        Department::whereIn('id', $ids)->where('company_id', $currentCompanyId)->update(['is_active' => true]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk activated ' . count($ids) . ' departments',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('departments.index')->with('success', count($ids) . ' departments activated successfully.');
    }

    /**
     * Bulk deactivate departments
     */
    public function bulkDeactivate(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('departments.index')->with('error', 'No departments selected.');
        }

        Department::whereIn('id', $ids)->where('company_id', $currentCompanyId)->update(['is_active' => false]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk deactivated ' . count($ids) . ' departments',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('departments.index')->with('success', count($ids) . ' departments deactivated successfully.');
    }

    /**
     * Bulk delete departments
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('departments.index')->with('error', 'No departments selected.');
        }

        // Log activity before deletion
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk deleted ' . count($ids) . ' departments',
            'ip_address' => $request->ip(),
        ]);

        Department::whereIn('id', $ids)->where('company_id', $currentCompanyId)->delete();

        return redirect()->route('departments.index')->with('success', count($ids) . ' departments deleted successfully.');
    }
}
