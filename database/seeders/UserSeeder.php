<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users or create new ones
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $user = User::firstOrCreate([
            'email' => 'user@example.com'
        ], [
            'name' => 'Regular User',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Get companies
        $companies = \App\Models\Company::all();

        // Assign roles and companies
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $adminRole = Role::where('name', 'Admin')->first();
        $employeeRole = Role::where('name', 'Employee')->first();

        if ($superAdminRole && $companies->isNotEmpty()) {
            // Assign admin to all companies as Super Admin
            foreach ($companies as $company) {
                $admin->companies()->attach($company->id, [
                    'role_id' => $superAdminRole->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        if ($adminRole && $companies->isNotEmpty()) {
            // Assign regular user to first company as Admin
            $firstCompany = $companies->first();
            $user->companies()->attach($firstCompany->id, [
                'role_id' => $adminRole->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
