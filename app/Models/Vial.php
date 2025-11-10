<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Vial extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'batch_id',
        'vial_number',
        'unique_code',
        'qr_code',
        'qr_code_image',
        'is_scanned',
        'first_scan_at',
        'scan_count',
        'status',
        'volume',
        'location',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'is_scanned' => 'boolean',
        'first_scan_at' => 'datetime',
        'scan_count' => 'integer',
        'volume' => 'decimal:2',
        'metadata' => 'array',
    ];

    protected $appends = [
        'verification_url',
        'qr_code_url'
    ];

    // Relationships
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class);
    }

    public function latestScan()
    {
        return $this->hasOne(ScanLog::class)->latestOfMany();
    }

    // Attributes
    public function getVerificationUrlAttribute()
    {
        return url('/verify/' . $this->unique_code);
    }

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code_image 
            ? asset('storage/' . $this->qr_code_image)
            : null;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'available' => 'green',
            'used' => 'blue',
            'damaged' => 'red',
            'recalled' => 'orange',
            'pending' => 'yellow',
            default => 'gray',
        };
    }

    public function getTimeSinceFirstScanAttribute()
    {
        if (!$this->first_scan_at) return null;
        return $this->first_scan_at->diffForHumans();
    }

    // Scopes
    public function scopeScanned($query)
    {
        return $query->where('is_scanned', true);
    }

    public function scopeUnscanned($query)
    {
        return $query->where('is_scanned', false);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('vial_number', 'like', "%{$search}%")
              ->orWhere('unique_code', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    // Helper Methods
    public function scan($ipAddress = null, $userAgent = null, $metadata = [], $userId = null)
    {
        $isFirstScan = !$this->is_scanned;

        // Update vial scan status
        if ($isFirstScan) {
            $this->update([
                'is_scanned' => true,
                'first_scan_at' => now(),
            ]);
        }

        // $this->increment('scan_count');

        return [
            'is_first_scan' => $isFirstScan,
            'scan_log' => $scanLog,
            'vial' => $this,
        ];
    }

    public function markAsUsed()
    {
        return $this->update(['status' => 'used']);
    }

    public function markAsDamaged($notes = null)
    {
        return $this->update([
            'status' => 'damaged',
            'notes' => $notes ?? $this->notes,
        ]);
    }

    public function markAsAvailable()
    {
        return $this->update(['status' => 'available']);
    }
}
