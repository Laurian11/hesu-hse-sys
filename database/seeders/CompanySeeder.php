<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Hesu Investment',
                'slug' => 'hesu-investment',
                'description' => 'Main investment company',
                'logo_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'SSA Logistics',
                'slug' => 'ssa-logistics',
                'description' => 'Inland Car Depot',
                'logo_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'AZNAS',
                'slug' => 'aznas',
                'description' => 'AZNAS operations',
                'logo_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Ramada',
                'slug' => 'ramada',
                'description' => 'Ramada operations',
                'logo_url' => null,
                'is_active' => true,
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
