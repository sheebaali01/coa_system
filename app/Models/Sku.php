<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'sku_code',
        'product_name',
        'description',
        'category',
        'unit_price',
        'unit_measure',
        'manufacturer',
        'product_image',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function activeBatches()
    {
        return $this->hasMany(Batch::class)->where('status', 'active');
    }

    public function vials()
    {
        return $this->hasManyThrough(Vial::class, Batch::class);
    }

    // Attributes
    public function getTotalVialsAttribute()
    {
        return $this->batches()->sum('total_vials');
    }

    public function getTotalBatchesAttribute()
    {
        return $this->batches()->count();
    }

    public function getActiveBatchesCountAttribute()
    {
        return $this->activeBatches()->count();
    }

    public function getScannedVialsCountAttribute()
    {
        return $this->vials()->where('is_scanned', true)->count();
    }

    public function getUnscannedVialsCountAttribute()
    {
        return $this->vials()->where('is_scanned', false)->count();
    }

    public function getScanRateAttribute()
    {
        $total = $this->total_vials;
        if ($total == 0) return 0;
        return round(($this->scanned_vials_count / $total) * 100, 2);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('sku_code', 'like', "%{$search}%")
              ->orWhere('product_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Helper Methods
    public function getImageUrl()
    {
        return $this->product_image 
            ? asset('storage/' . $this->product_image) 
            : asset('images/default-product.png');
    }

    public function toggleActive()
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }
}
