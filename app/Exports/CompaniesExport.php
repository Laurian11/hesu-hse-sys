<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CompaniesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $companyIds;

    public function __construct(array $companyIds = [])
    {
        $this->companyIds = $companyIds;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (!empty($this->companyIds)) {
            return Company::whereIn('id', $this->companyIds)->get();
        }

        return Company::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Slug',
            'Description',
            'Active',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param Company $company
     * @return array
     */
    public function map($company): array
    {
        return [
            $company->id,
            $company->name,
            $company->slug,
            $company->description,
            $company->is_active ? 'Yes' : 'No',
            $company->created_at->format('Y-m-d H:i:s'),
            $company->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
