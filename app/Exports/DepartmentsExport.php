<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartmentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $departments;

    public function __construct(Collection $departments)
    {
        $this->departments = $departments;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->departments;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Code',
            'HOD',
            'Status',
            'Created At',
        ];
    }

    /**
     * @param mixed $department
     * @return array
     */
    public function map($department): array
    {
        return [
            $department->id,
            $department->name,
            $department->code,
            $department->hod ? $department->hod->name : 'N/A',
            $department->is_active ? 'Active' : 'Inactive',
            $department->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
