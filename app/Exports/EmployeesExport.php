<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $employees;

    public function __construct(Collection $employees)
    {
        $this->employees = $employees;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->employees;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Employee ID',
            'Name',
            'Email',
            'Department',
            'Designation',
            'Date of Joining',
            'Work Location',
            'Status',
            'Created At',
        ];
    }

    /**
     * @param mixed $employee
     * @return array
     */
    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->employee_id,
            $employee->user->name,
            $employee->user->email,
            $employee->department->name,
            $employee->designation,
            $employee->date_of_joining->format('Y-m-d'),
            $employee->work_location,
            $employee->is_active ? 'Active' : 'Inactive',
            $employee->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
