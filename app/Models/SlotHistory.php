<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotHistory extends Model
{
    use HasFactory;

    protected $table = 'slot_history';

    protected $fillable = [
        'slot_id',
        'action',
        'field_changed',
        'old_value',
        'new_value',
        'changed_by',
        'name',
        'notes'
    ];

    protected $casts = [
        'action' => 'string'
    ];

    // Relationship dengan Slot
    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_id');
    }

    // Relationship dengan User (changed_by)
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Scope untuk filter berdasarkan action
    public function scopeUpdates($query)
    {
        return $query->where('action', 'update');
    }

    public function scopeDeletes($query)
    {
        return $query->where('action', 'delete');
    }
}

