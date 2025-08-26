<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $table = 'slots';

    protected $fillable = [
        'slot_name',
        'item_id',
        'rack_id',
        'capacity',
        'current_qty'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'current_qty' => 'integer'
    ];

    // Relationship dengan Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // Relationship dengan Rack
    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id');
    }

    // Relationship dengan SlotHistory
    public function histories()
    {
        return $this->hasMany(SlotHistory::class, 'slot_id');
    }

    // Relationship dengan LogStorePull
    public function logStorePulls()
    {
        return $this->hasMany(LogStorePull::class, 'slot_id');
    }

    // Helper methods
    public function isEmpty()
    {
        return $this->current_qty == 0;
    }

    public function isFull()
    {
        return $this->current_qty >= $this->capacity;
    }

    public function getAvailableSpace()
    {
        return $this->capacity - $this->current_qty;
    }

    public function getOccupancyPercentage()
    {
        return $this->capacity > 0 ? ($this->current_qty / $this->capacity) * 100 : 0;
    }
}
