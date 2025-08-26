<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackHistory extends Model
{
    use HasFactory;

    protected $table = 'rack_history';

    protected $fillable = [
        'rack_id',
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

    // Relationship dengan Rack
    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id');
    }

    // Relationship dengan User (changed_by)
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
