<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rack extends Model
{
    use HasFactory;

    protected $table = 'rack';

    protected $fillable = [
        'rack_name',
        'total_slots'
    ];

    protected $casts = [
        'total_slots' => 'integer'
    ];

    // Relationship dengan Slots
    public function slots()
    {
        return $this->hasMany(Slot::class, 'rack_id');
    }

    // Relationship dengan RackHistory
    public function histories()
    {
        return $this->hasMany(RackHistory::class, 'rack_id');
    }

    // Helper methods
    public function getAvailableSlots()
    {
        return $this->slots()->where('current_qty', '<', DB::raw('capacity'))->count();
    }

    public function getOccupiedSlots()
    {
        return $this->slots()->where('current_qty', '>', 0)->count();
    }

    public function getEmptySlots()
    {
        return $this->slots()->where('current_qty', 0)->count();
    }

    public function getTotalCapacity()
    {
        return $this->slots()->sum('capacity');
    }

    public function getCurrentOccupancy()
    {
        return $this->slots()->sum('current_qty');
    }

    // Check if rack has available slot capacity
    public function hasAvailableSlotCapacity()
    {
        $currentSlotCount = $this->slots()->count();
        return $currentSlotCount < $this->total_slots;
    }

    // Get remaining slot capacity
    public function getRemainingSlotCapacity()
    {
        $currentSlotCount = $this->slots()->count();
        return max(0, $this->total_slots - $currentSlotCount);
    }
}
