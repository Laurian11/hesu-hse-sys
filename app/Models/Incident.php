<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    protected $fillable = [
        'incident_number',
        'type',
        'severity',
        'title',
        'description',
        'incident_date',
        'location',
        'affected_parties',
        'witnesses',
        'immediate_actions',
        'status',
        'root_cause',
        'corrective_actions',
        'preventive_actions',
        'closure_date',
        'attachments',
        'reported_by',
        'investigated_by',
        'company_id',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'closure_date' => 'datetime',
        'affected_parties' => 'array',
        'witnesses' => 'array',
        'attachments' => 'array',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function investigator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'investigated_by');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function generateIncidentNumber(): string
    {
        return 'INC-' . date('Y') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
