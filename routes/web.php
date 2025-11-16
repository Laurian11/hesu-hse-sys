<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PublicDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/company/{company:slug}/dashboard', [PublicDashboardController::class, 'show'])->name('public-dashboard');

// Authenticated routes with company context
Route::middleware(['auth', 'company.context'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-company', [App\Http\Controllers\DashboardController::class, 'switchCompany'])->name('switch-company');

    // Settings
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

    // UI Management
    Route::get('/ui-management', function () {
        return view('ui-management.index');
    })->name('ui-management.index');
    Route::patch('/ui-management', [App\Http\Controllers\UIManagementController::class, 'update'])->name('ui-management.update');

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Landing page for authenticated users
    Route::get('/landing', [LandingController::class, 'index'])->name('landing.index');

    // Company Management (Admin only)
    Route::middleware('permission:company.manage')->group(function () {
        Route::resource('companies', \App\Http\Controllers\CompanyController::class);
        Route::post('companies/export', [App\Http\Controllers\CompanyController::class, 'export'])->name('companies.export');
        Route::post('companies/import', [App\Http\Controllers\CompanyController::class, 'import'])->name('companies.import');
        Route::post('companies/bulk-activate', [App\Http\Controllers\CompanyController::class, 'bulkActivate'])->name('companies.bulk-activate');
        Route::post('companies/bulk-deactivate', [App\Http\Controllers\CompanyController::class, 'bulkDeactivate'])->name('companies.bulk-deactivate');
        Route::delete('companies/bulk-delete', [App\Http\Controllers\CompanyController::class, 'bulkDelete'])->name('companies.bulk-delete');
    });

    // User Management
    Route::middleware('permission:user.manage')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::post('users/export', [App\Http\Controllers\UserController::class, 'export'])->name('users.export');
        Route::post('users/import', [App\Http\Controllers\UserController::class, 'import'])->name('users.import');
        Route::post('users/bulk-activate', [App\Http\Controllers\UserController::class, 'bulkActivate'])->name('users.bulk-activate');
        Route::post('users/bulk-deactivate', [App\Http\Controllers\UserController::class, 'bulkDeactivate'])->name('users.bulk-deactivate');
        Route::delete('users/bulk-delete', [App\Http\Controllers\UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    });

    // Department Management
    Route::middleware('permission:department.manage')->group(function () {
        Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
        Route::post('departments/export', [App\Http\Controllers\DepartmentController::class, 'export'])->name('departments.export');
        Route::post('departments/import', [App\Http\Controllers\DepartmentController::class, 'import'])->name('departments.import');
        Route::post('departments/bulk-activate', [App\Http\Controllers\DepartmentController::class, 'bulkActivate'])->name('departments.bulk-activate');
        Route::post('departments/bulk-deactivate', [App\Http\Controllers\DepartmentController::class, 'bulkDeactivate'])->name('departments.bulk-deactivate');
        Route::delete('departments/bulk-delete', [App\Http\Controllers\DepartmentController::class, 'bulkDelete'])->name('departments.bulk-delete');
    });

    // Employee Management
    Route::middleware('permission:employee.manage')->group(function () {
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
        Route::post('employees/export', [App\Http\Controllers\EmployeeController::class, 'export'])->name('employees.export');
        Route::post('employees/import', [App\Http\Controllers\EmployeeController::class, 'import'])->name('employees.import');
        Route::post('employees/bulk-activate', [App\Http\Controllers\EmployeeController::class, 'bulkActivate'])->name('employees.bulk-activate');
        Route::post('employees/bulk-deactivate', [App\Http\Controllers\EmployeeController::class, 'bulkDeactivate'])->name('employees.bulk-deactivate');
        Route::delete('employees/bulk-delete', [App\Http\Controllers\EmployeeController::class, 'bulkDelete'])->name('employees.bulk-delete');
    });

    // Incident Management
    Route::middleware('permission:incident.manage')->group(function () {
        Route::resource('incidents', \App\Http\Controllers\IncidentController::class);
        Route::post('incidents/export', [App\Http\Controllers\IncidentController::class, 'export'])->name('incidents.export');
        Route::post('incidents/import', [App\Http\Controllers\IncidentController::class, 'import'])->name('incidents.import');
    });

    // Permit to Work Management
    Route::middleware('permission:permit.manage')->group(function () {
        Route::resource('permits', \App\Http\Controllers\PermitController::class);
        Route::patch('permits/{permit}/approve', [App\Http\Controllers\PermitController::class, 'approve'])->name('permits.approve');
        Route::patch('permits/{permit}/issue', [App\Http\Controllers\PermitController::class, 'issue'])->name('permits.issue');
        Route::patch('permits/{permit}/close', [App\Http\Controllers\PermitController::class, 'close'])->name('permits.close');
        Route::post('permits/export', [App\Http\Controllers\PermitController::class, 'export'])->name('permits.export');
        Route::post('permits/import', [App\Http\Controllers\PermitController::class, 'import'])->name('permits.import');
    });

    // PPE Inventory Management
    Route::middleware('permission:ppe.manage')->group(function () {
        Route::resource('ppe-inventory', \App\Http\Controllers\PpeInventoryController::class);
        Route::post('ppe-inventory/export', [App\Http\Controllers\PpeInventoryController::class, 'export'])->name('ppe-inventory.export');
        Route::post('ppe-inventory/import', [App\Http\Controllers\PpeInventoryController::class, 'import'])->name('ppe-inventory.import');
    });

    // Risk Assessment Management
    Route::middleware('permission:risk.manage')->group(function () {
        Route::resource('risk-assessments', \App\Http\Controllers\RiskAssessmentController::class);
        Route::post('risk-assessments/export', [App\Http\Controllers\RiskAssessmentController::class, 'export'])->name('risk-assessments.export');
        Route::post('risk-assessments/import', [App\Http\Controllers\RiskAssessmentController::class, 'import'])->name('risk-assessments.import');
    });

    // Training Management
    Route::middleware('permission:training.manage')->group(function () {
        Route::resource('trainings', \App\Http\Controllers\TrainingController::class);
        Route::post('trainings/export', [App\Http\Controllers\TrainingController::class, 'export'])->name('trainings.export');
        Route::post('trainings/import', [App\Http\Controllers\TrainingController::class, 'import'])->name('trainings.import');
    });

    // HSE Observations - Commented out until implemented
    // Route::middleware('permission:observation.manage')->group(function () {
    //     Route::resource('observations', \App\Http\Controllers\ObservationController::class);
    // });

    // Reports - Commented out until implemented
    // Route::middleware('permission:report.view')->group(function () {
    //     Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    //     Route::get('/reports/{type}', [\App\Http\Controllers\ReportController::class, 'show'])->name('reports.show');
    // });
});

require __DIR__.'/auth.php';
