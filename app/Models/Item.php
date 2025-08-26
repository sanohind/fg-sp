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
        if (!$this->part_img) {
            return null;
        }
        $path = $this->part_img;
        // If already absolute URL
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }
        // If already points under storage
        if (str_starts_with($path, 'storage/') || str_starts_with($path, '/storage/')) {
            return asset(ltrim($path, '/'));
        }
        // If includes nested folders, prefix with storage/
        if (str_contains($path, '/')) {
            return asset('storage/' . ltrim($path, '/'));
        }
        // Fallback assume filename only under parts/
        return asset('storage/parts/' . $path);
    }

    public function getPackagingImageUrlAttribute()
    {
        if (!$this->packaging_img) {
            return null;
        }
        $path = $this->packaging_img;
        // If already absolute URL
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }
        // If already points under storage
        if (str_starts_with($path, 'storage/') || str_starts_with($path, '/storage/')) {
            return asset(ltrim($path, '/'));
        }
        // If includes nested folders, prefix with storage/
        if (str_contains($path, '/')) {
            return asset('storage/' . ltrim($path, '/'));
        }
        // Fallback assume filename only under packaging/
        return asset('storage/packaging/' . $path);
    }
}
