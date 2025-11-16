<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskAssessment extends Model
{
    protected $fillable = [
        'assessment_number',
        'title',
        'description',
        'risk_level',
        'probability',
        'impact',
        'mitigation_plan',
        'residual_risk',
        'status',
        'assessment_date',
        'review_date',
        'attachments',
        'company_id',
        'created_by',
        'reviewed_by',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'review_date' => 'date',
        'attachments' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function generateAssessmentNumber(): string
    {
        return 'RA-' . date('Y') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
