<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Batch extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'sku_id',
        'batch_number',
        'total_vials',
        'manufacture_date',
        'expiry_date',
        'coa_document',
        'lab_results',
        'status',
        'notes',
        'storage_conditions',
        'batch_size',
        'lot_number',
        'metadata',
    ];

    protected $casts = [
        'lab_results' => 'array',
        'metadata' => 'array',
        'manufacture_date' => 'date',
        'expiry_date' => 'date',
        'batch_size' => 'decimal:2',
    ];

    protected $appends = [
        'is_expired',
        'days_until_expiry',
        'scanned_vials_count',
        'unscanned_vials_count',
        'scan_rate'
    ];

    protected static function booted()
    {
        static::created(function ($batch) {
            // dispatch(function () use ($batch) {
                app(\App\Services\VialService::class)->generateVialsForBatch($batch);
            // })->onQueue('vials');
        });
    }

    // Relationships
    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }

    public function vials()
    {
        return $this->hasMany(Vial::class);
    }

    public function scanLogs()
    {
        return $this->hasManyThrough(ScanLog::class, Vial::class);
    }

    // Attributes
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date->isPast();
    }

    public function getDaysUntilExpiryAttribute()
    {
        if ($this->is_expired) {
            return 0;
        }
        return now()->diffInDays($this->expiry_date);
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
        if ($this->total_vials == 0) return 0;
        return round(($this->scanned_vials_count / $this->total_vials) * 100, 2);
    }

    public function getCoaUrlAttribute()
    {
        return $this->coa_document 
            ? asset('storage/' . $this->coa_document)
            : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    public function scopeBySku($query, $skuId)
    {
        return $query->where('sku_id', $skuId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('batch_number', 'like', "%{$search}%")
              ->orWhere('lot_number', 'like', "%{$search}%")
              ->orWhere('notes', 'like', "%{$search}%");
        });
    }

    // Helper Methods
    public function isExpiringSoon($days = 30)
    {
        return !$this->is_expired && $this->days_until_expiry <= $days;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'green',
            'expired' => 'red',
            'recalled' => 'orange',
            'pending' => 'yellow',
            default => 'gray',
        };
    }

    public function getCompletionPercentageAttribute()
    {
        return $this->scan_rate;
    }
}
