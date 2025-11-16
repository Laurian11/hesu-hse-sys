<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::firstOrCreate([
            'email' => $row['email'],
        ], [
            'name' => $row['name'],
            'password' => Hash::make('password123'), // Default password
            'phone' => $row['phone'] ?? null,
            'is_active' => $row['status'] === 'Active' ? true : false,
        ]);

        // Attach to company if not already attached
        if (!$user->companies()->where('company_id', $this->companyId)->exists()) {
            $user->companies()->attach($this->companyId, [
                'role_id' => 2, // Default to employee role
            ]);
        }

        return $user;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:Active,Inactive',
        ];
    }
}
