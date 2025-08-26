<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogStorePull extends Model
{
    use HasFactory;

    protected $table = 'log_store_pull';

    protected $fillable = [
        'erp_code',
        'part_no',
        'slot_id',
        'slot_name',
        'rack_name',
        'lot_no',
        'action',
        'user_id',
        'name',
        'qty'
    ];

    protected $casts = [
        'qty' => 'integer',
        'action' => 'string'
    ];

    // Relationship dengan Slot
    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_id');
    }

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope untuk filter berdasarkan action
    public function scopeStore($query)
    {
        return $query->where('action', 'store');
    }

    public function scopePull($query)
    {
        return $query->where('action', 'pull');
    }

    // Helper methods
    public function isStoreAction()
    {
        return $this->action === 'store';
    }

    public function isPullAction()
    {
        return $this->action === 'pull';
    }
}
