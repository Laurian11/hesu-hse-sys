<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentCompanyId = request('current_company_id');
        $users = User::whereHas('companies', function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        })->with(['companies' => function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        }])->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentCompanyId = request('current_company_id');
        $company = Company::find($currentCompanyId);
        $roles = Role::all();

        return view('users.create', compact('company', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentCompanyId = $request->get('current_company_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        // Attach user to company with role
        $user->companies()->attach($currentCompanyId, [
            'role_id' => $request->role_id,
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Created user: ' . $user->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure user belongs to current company
        if (!$user->belongsToCompany(Company::find($currentCompanyId))) {
            abort(403);
        }

        $user->load(['companies' => function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        }, 'employee']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure user belongs to current company
        if (!$user->belongsToCompany(Company::find($currentCompanyId))) {
            abort(403);
        }

        $company = Company::find($currentCompanyId);
        $roles = Role::all();
        $userRole = $user->companies()->where('company_id', $currentCompanyId)->first()->pivot->role_id;

        return view('users.edit', compact('user', 'company', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $currentCompanyId = $request->get('current_company_id');

        // Ensure user belongs to current company
        if (!$user->belongsToCompany(Company::find($currentCompanyId))) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ]);

        // Update role in company
        $user->companies()->updateExistingPivot($currentCompanyId, [
            'role_id' => $request->role_id,
        ]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Updated user: ' . $user->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $currentCompanyId = request('current_company_id');

        // Ensure user belongs to current company
        if (!$user->belongsToCompany(Company::find($currentCompanyId))) {
            abort(403);
        }

        // Log activity before deletion
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Deleted user: ' . $user->name,
            'ip_address' => request()->ip(),
        ]);

        // Remove user from company (soft delete by setting inactive)
        $user->companies()->detach($currentCompanyId);

        return redirect()->route('users.index')->with('success', 'User removed from company successfully.');
    }

    /**
     * Export users to Excel/CSV
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $ids = $request->get('ids', []);

        $currentCompanyId = $request->get('current_company_id');

        if (!empty($ids)) {
            $users = User::whereIn('id', $ids)->whereHas('companies', function ($query) use ($currentCompanyId) {
                $query->where('company_id', $currentCompanyId);
            })->get();
        } else {
            $users = User::whereHas('companies', function ($query) use ($currentCompanyId) {
                $query->where('company_id', $currentCompanyId);
            })->get();
        }

        if ($format === 'csv') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UsersExport($users), 'users.csv');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UsersExport($users), 'users.xlsx');
    }

    /**
     * Import users from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $currentCompanyId = $request->get('current_company_id');

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\UsersImport($currentCompanyId), $request->file('file'));

            // Log activity
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'company_id' => $currentCompanyId,
                'action' => 'Imported users from file',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('users.index')->with('success', 'Users imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Bulk activate users
     */
    public function bulkActivate(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('users.index')->with('error', 'No users selected.');
        }

        User::whereIn('id', $ids)->whereHas('companies', function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        })->update(['is_active' => true]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk activated ' . count($ids) . ' users',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', count($ids) . ' users activated successfully.');
    }

    /**
     * Bulk deactivate users
     */
    public function bulkDeactivate(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('users.index')->with('error', 'No users selected.');
        }

        User::whereIn('id', $ids)->whereHas('companies', function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        })->update(['is_active' => false]);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk deactivated ' . count($ids) . ' users',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', count($ids) . ' users deactivated successfully.');
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->get('ids', []);
        $currentCompanyId = $request->get('current_company_id');

        if (empty($ids)) {
            return redirect()->route('users.index')->with('error', 'No users selected.');
        }

        // Log activity before deletion
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'company_id' => $currentCompanyId,
            'action' => 'Bulk deleted ' . count($ids) . ' users',
            'ip_address' => $request->ip(),
        ]);

        // Remove users from company
        $users = User::whereIn('id', $ids)->whereHas('companies', function ($query) use ($currentCompanyId) {
            $query->where('company_id', $currentCompanyId);
        })->get();

        foreach ($users as $user) {
            $user->companies()->detach($currentCompanyId);
        }

        return redirect()->route('users.index')->with('success', count($ids) . ' users removed from company successfully.');
    }
}
