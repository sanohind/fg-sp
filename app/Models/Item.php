<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'erp_code',
        'part_no',
        'description',
        'model',
        'customer',
        'qty',
        'part_img',
        'packaging_img'
    ];

    protected $casts = [
        'qty' => 'integer'
    ];

    // Relationship dengan Slots
    public function slots()
    {
        return $this->hasMany(Slot::class, 'item_id');
    }

    // Relationship dengan ItemHistory
    public function histories()
    {
        return $this->hasMany(ItemHistory::class, 'item_id');
    }

    // Helper methods
    public function getTotalStoredQuantity()
    {
        return $this->slots()->sum('current_qty');
    }

    public function getAvailableQuantity()
    {
        return $this->qty - $this->getTotalStoredQuantity();
    }

    // Accessor untuk gambar
    public function getPartImageUrlAttribute()
    {
        return $this->part_img ? asset('storage/parts/' . $this->part_img) : null;
    }

    public function getPackagingImageUrlAttribute()
    {
        return $this->packaging_img ? asset('storage/packaging/' . $this->packaging_img) : null;
    }
}
