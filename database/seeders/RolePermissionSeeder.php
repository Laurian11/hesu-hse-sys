<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            // Company Management
            ['name' => 'company.manage', 'module' => 'company'],
            ['name' => 'company.view', 'module' => 'company'],

            // User Management
            ['name' => 'user.manage', 'module' => 'user'],
            ['name' => 'user.view', 'module' => 'user'],
            ['name' => 'user.create', 'module' => 'user'],
            ['name' => 'user.edit', 'module' => 'user'],
            ['name' => 'user.delete', 'module' => 'user'],

            // Department Management
            ['name' => 'department.manage', 'module' => 'department'],
            ['name' => 'department.view', 'module' => 'department'],
            ['name' => 'department.create', 'module' => 'department'],
            ['name' => 'department.edit', 'module' => 'department'],
            ['name' => 'department.delete', 'module' => 'department'],

            // Employee Management
            ['name' => 'employee.manage', 'module' => 'employee'],
            ['name' => 'employee.view', 'module' => 'employee'],
            ['name' => 'employee.create', 'module' => 'employee'],
            ['name' => 'employee.edit', 'module' => 'employee'],
            ['name' => 'employee.delete', 'module' => 'employee'],

            // Incident Management
            ['name' => 'incident.report', 'module' => 'incident'],
            ['name' => 'incident.manage', 'module' => 'incident'],
            ['name' => 'incident.view', 'module' => 'incident'],
            ['name' => 'incident.investigate', 'module' => 'incident'],

            // Risk Assessment Management
            ['name' => 'risk.manage', 'module' => 'risk'],
            ['name' => 'risk.view', 'module' => 'risk'],
            ['name' => 'risk.create', 'module' => 'risk'],
            ['name' => 'risk.edit', 'module' => 'risk'],
            ['name' => 'risk.review', 'module' => 'risk'],

            // Training Management
            ['name' => 'training.manage', 'module' => 'training'],
            ['name' => 'training.view', 'module' => 'training'],
            ['name' => 'training.create', 'module' => 'training'],
            ['name' => 'training.edit', 'module' => 'training'],

            // PPE Management
            ['name' => 'ppe.manage', 'module' => 'ppe'],
            ['name' => 'ppe.view', 'module' => 'ppe'],
            ['name' => 'ppe.create', 'module' => 'ppe'],
            ['name' => 'ppe.edit', 'module' => 'ppe'],

            // Permit to Work Management
            ['name' => 'permit.manage', 'module' => 'permit'],
            ['name' => 'permit.view', 'module' => 'permit'],
            ['name' => 'permit.create', 'module' => 'permit'],
            ['name' => 'permit.edit', 'module' => 'permit'],
            ['name' => 'permit.approve', 'module' => 'permit'],
            ['name' => 'permit.issue', 'module' => 'permit'],
            ['name' => 'permit.close', 'module' => 'permit'],

            // HSE Observations
            ['name' => 'observation.manage', 'module' => 'observation'],
            ['name' => 'observation.view', 'module' => 'observation'],
            ['name' => 'observation.create', 'module' => 'observation'],
            ['name' => 'observation.close', 'module' => 'observation'],

            // Reports
            ['name' => 'report.view', 'module' => 'report'],
            ['name' => 'report.generate', 'module' => 'report'],
            ['name' => 'report.export', 'module' => 'report'],

            // Audit & Activity Logs
            ['name' => 'activity.view', 'module' => 'activity'],
            ['name' => 'audit.view', 'module' => 'audit'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Define roles and their permissions
        $roles = [
            'Super Admin' => [
                'company.manage', 'company.view',
                'user.manage', 'user.view', 'user.create', 'user.edit', 'user.delete',
                'department.manage', 'department.view', 'department.create', 'department.edit', 'department.delete',
                'employee.manage', 'employee.view', 'employee.create', 'employee.edit', 'employee.delete',
                'incident.report', 'incident.manage', 'incident.view', 'incident.investigate',
                'risk.manage', 'risk.view', 'risk.create', 'risk.edit', 'risk.review',
                'training.manage', 'training.view', 'training.create', 'training.edit',
                'ppe.manage', 'ppe.view', 'ppe.create', 'ppe.edit',
                'permit.manage', 'permit.view', 'permit.create', 'permit.edit', 'permit.approve', 'permit.issue', 'permit.close',
                'observation.manage', 'observation.view', 'observation.create', 'observation.close',
                'report.view', 'report.generate', 'report.export',
                'activity.view', 'audit.view',
            ],
            'Admin' => [
                'company.view',
                'user.manage', 'user.view', 'user.create', 'user.edit', 'user.delete',
                'department.manage', 'department.view', 'department.create', 'department.edit', 'department.delete',
                'employee.manage', 'employee.view', 'employee.create', 'employee.edit', 'employee.delete',
                'incident.report', 'incident.manage', 'incident.view', 'incident.investigate',
                'risk.manage', 'risk.view', 'risk.create', 'risk.edit', 'risk.review',
                'training.manage', 'training.view', 'training.create', 'training.edit',
                'ppe.manage', 'ppe.view', 'ppe.create', 'ppe.edit',
                'permit.manage', 'permit.view', 'permit.create', 'permit.edit', 'permit.approve', 'permit.issue', 'permit.close',
                'observation.manage', 'observation.view', 'observation.create', 'observation.close',
                'report.view', 'report.generate', 'report.export',
                'activity.view',
            ],
            'HSE Officer' => [
                'company.view',
                'user.view',
                'department.view',
                'employee.view',
                'incident.report', 'incident.manage', 'incident.view', 'incident.investigate',
                'risk.manage', 'risk.view', 'risk.create', 'risk.edit', 'risk.review',
                'training.manage', 'training.view', 'training.create', 'training.edit',
                'ppe.manage', 'ppe.view', 'ppe.create', 'ppe.edit',
                'permit.manage', 'permit.view', 'permit.create', 'permit.edit', 'permit.approve', 'permit.issue', 'permit.close',
                'observation.manage', 'observation.view', 'observation.create', 'observation.close',
                'report.view', 'report.generate', 'report.export',
                'activity.view',
            ],
            'Supervisor' => [
                'company.view',
                'user.view',
                'department.view',
                'employee.view',
                'incident.report', 'incident.view',
                'training.view',
                'observation.view', 'observation.create',
                'report.view',
            ],
            'HOD' => [
                'company.view',
                'user.view',
                'department.view',
                'employee.view',
                'incident.report', 'incident.view',
                'training.view',
                'observation.view', 'observation.create',
                'report.view',
            ],
            'Employee' => [
                'company.view',
                'user.view',
                'department.view',
                'employee.view',
                'incident.report',
                'training.view',
                'observation.view', 'observation.create',
                'report.view',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);

            foreach ($rolePermissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $role->permissions()->attach($permission);
                }
            }
        }
    }
}
