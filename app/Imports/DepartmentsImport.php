<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DepartmentsImport implements ToModel, WithHeadingRow, WithValidation
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
        return new Department([
            'company_id' => $this->companyId,
            'name' => $row['name'],
            'code' => $row['code'],
            'hod_user_id' => null, // Will be set later if needed
            'is_active' => $row['status'] === 'Active' ? true : false,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code,NULL,id,company_id,' . $this->companyId,
            'status' => 'nullable|string|in:Active,Inactive',
        ];
    }
}
