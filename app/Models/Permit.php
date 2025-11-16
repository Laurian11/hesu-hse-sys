<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permit extends Model
{
    protected $fillable = [
        'permit_number',
        'type',
        'title',
        'description',
        'work_description',
        'location',
        'planned_start',
        'planned_end',
        'actual_start',
        'actual_end',
        'status',
        'hazards_identified',
        'control_measures',
        'ppe_required',
        'emergency_procedures',
        'approvals',
        'attachments',
        'closure_notes',
        'company_id',
        'requested_by',
        'approved_by',
        'issued_by',
        'closed_by',
    ];

    protected $casts = [
        'planned_start' => 'datetime',
        'planned_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'approvals' => 'array',
        'attachments' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function generatePermitNumber(): string
    {
        return 'PTW-' . date('Y') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isExpired(): bool
    {
        return $this->planned_end && $this->planned_end->isPast() && $this->status !== 'closed';
    }

    public function canBeApproved(): bool
    {
        return in_array($this->status, ['draft', 'pending_approval']);
    }

    public function canBeClosed(): bool
    {
        return $this->status === 'active';
    }
}
