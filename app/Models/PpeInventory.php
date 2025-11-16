<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpeInventory extends Model
{
    protected $table = 'ppe_inventory';

    protected $fillable = [
        'item_code',
        'name',
        'description',
        'category',
        'size',
        'color',
        'quantity_available',
        'quantity_minimum',
        'unit_cost',
        'supplier',
        'last_restocked',
        'expiry_date',
        'location',
        'status',
        'attachments',
        'company_id',
        'created_by',
    ];

    protected $casts = [
        'quantity_available' => 'integer',
        'quantity_minimum' => 'integer',
        'unit_cost' => 'decimal:2',
        'last_restocked' => 'date',
        'expiry_date' => 'date',
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

    public function generateItemCode(): string
    {
        return 'PPE-' . date('Y') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function isLowStock(): bool
    {
        return $this->quantity_available <= $this->quantity_minimum;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
