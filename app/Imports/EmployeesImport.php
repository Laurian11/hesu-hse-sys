<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
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
        // Find user by email
        $user = User::where('email', $row['email'])->first();
        if (!$user) {
            return null; // Skip if user not found
        }

        // Find department by name
        $department = Department::where('company_id', $this->companyId)
            ->where('name', $row['department'])
            ->first();
        if (!$department) {
            return null; // Skip if department not found
        }

        return new Employee([
            'user_id' => $user->id,
            'company_id' => $this->companyId,
            'employee_id' => $row['employee_id'],
            'department_id' => $department->id,
            'designation' => $row['designation'],
            'date_of_joining' => $row['date_of_joining'],
            'work_location' => $row['work_location'] ?? null,
            'is_active' => $row['status'] === 'Active' ? true : false,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|string|max:50|unique:employees,employee_id,NULL,id,company_id,' . $this->companyId,
            'email' => 'required|email|exists:users,email',
            'department' => 'required|string|exists:departments,name',
            'designation' => 'required|string|max:255',
            'date_of_joining' => 'required|date',
            'work_location' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:Active,Inactive',
        ];
    }
}
