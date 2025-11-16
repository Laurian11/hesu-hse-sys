<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Training extends Model
{
    protected $fillable = [
        'training_number',
        'title',
        'description',
        'type',
        'scheduled_date',
        'start_time',
        'end_time',
        'location',
        'trainer',
        'attendees',
        'objectives',
        'materials',
        'status',
        'completion_date',
        'notes',
        'attachments',
        'company_id',
        'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completion_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'attendees' => 'array',
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

    public function generateTrainingNumber(): string
    {
        return 'TR-' . date('Y') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
