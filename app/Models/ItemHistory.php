<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemHistory extends Model
{
    use HasFactory;

    protected $table = 'item_history';

    protected $fillable = [
        'item_id',
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

    // Relationship dengan Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
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
