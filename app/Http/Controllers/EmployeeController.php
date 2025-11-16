<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentCompanyId = request('current_company_id');
        $employees = Employee::where('company_id', $currentCompanyId)
            ->with(['user', 'department'])
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentCompanyId = request('current_company_id');
        $company = Company::find($currentCompanyId);
        $users = User::whereHas('companies', function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        })->whereDoesntHave('employee', function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        })->get();
        $departments = Department::where('company_id', $currentCompanyId)->where('is_active', true)->get();

        return view('employees.create', compact('company', 'users', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentCompanyId = $request->get('current_company_id');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'employee_id' => 'required|string|max:50|unique:employees,employee_id,NULL,id,company_id,' . $currentCompanyId,
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string|max:255',
            'date_of_joining' => 'required|date',
            'work_location' => 'nullable|string|max:255',
        ]);

        $employee = Employee::create([
            'user_id' => $request->user_id,
            'company_id' => $currentCompanyId,
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'designation' => $request->designation,
            'date_of_joining' => $request->date_of_joining,
            'work_location' => $request->work_location,
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Created employee record for: ' . $employee->user->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure employee belongs to current company
        if ($employee->company_id != $currentCompanyId) {
            abort(403);
        }

        $employee->load(['user', 'company', 'department']);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure employee belongs to current company
        if ($employee->company_id != $currentCompanyId) {
            abort(403);
        }

        $company = Company::find($currentCompanyId);
        $departments = Department::where('company_id', $currentCompanyId)->where('is_active', true)->get();

        return view('employees.edit', compact('employee', 'company', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $currentCompanyId = $request->get('current_company_id');

        // Ensure employee belongs to current company
        if ($employee->company_id != $currentCompanyId) {
            abort(403);
        }

        $request->validate([
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('employees')->ignore($employee->id)->where('company_id', $currentCompanyId)],
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string|max:255',
            'date_of_joining' => 'required|date',
            'work_location' => 'nullable|string|max:255',
        ]);

        $employee->update([
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'designation' => $request->designation,
            'date_of_joining' => $request->date_of_joining,
            'work_location' => $request->work_location,
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Updated employee record for: ' . $employee->user->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure employee belongs to current company
        if ($employee->company_id != $currentCompanyId) {
            abort(403);
        }

        // Log activity before deletion
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Deleted employee record for: ' . $employee->user->name,
            'ip_address' => request()->ip(),
        ]);

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee record deleted successfully.');
    }

    /**
     * Export employees to Excel/CSV
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $ids = $request->get('ids', []);

        $currentCompanyId = $request->get('current_company_id');

        if (!empty($ids)) {
            $employees = Employee::whereIn('id', $ids)->where('company_id', $currentCompanyId)->with(['user', 'department'])->get();
        } else {
            $employees = Employee::where('company_id', $currentCompanyId)->with(['user', 'department'])->get();
        }

        if ($format === 'csv') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EmployeesExport($employees), 'employees.csv');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EmployeesExport($employees), 'employees.xlsx');
    }

    /**
     * Import employees from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $currentCompanyId = $request->get('current_company_id');

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\EmployeesImport($currentCompanyId), $request->file('file'));

            // Log activity
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'company_id' => $currentCompanyId,
                'action' => 'Imported employees from file',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('employees.index')->with('success', 'Employees imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Bulk activate employees
     */
    public function bulkActivate(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('employees.index')->with('error', 'No employees selected.');
        }

        Employee::whereIn('id', $ids)->where('company_id', $currentCompanyId)->update(['is_active' => true]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk activated ' . count($ids) . ' employees',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('employees.index')->with('success', count($ids) . ' employees activated successfully.');
    }

    /**
     * Bulk deactivate employees
     */
    public function bulkDeactivate(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('employees.index')->with('error', 'No employees selected.');
        }

        Employee::whereIn('id', $ids)->where('company_id', $currentCompanyId)->update(['is_active' => false]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk deactivated ' . count($ids) . ' employees',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('employees.index')->with('success', count($ids) . ' employees deactivated successfully.');
    }

    /**
     * Bulk delete employees
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('employees.index')->with('error', 'No employees selected.');
        }

        // Log activity before deletion
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk deleted ' . count($ids) . ' employees',
            'ip_address' => $request->ip(),
        ]);

        Employee::whereIn('id', $ids)->where('company_id', $currentCompanyId)->delete();

        return redirect()->route('employees.index')->with('success', count($ids) . ' employees deleted successfully.');
    }
}
