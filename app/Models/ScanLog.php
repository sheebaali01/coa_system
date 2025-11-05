<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vial_id',
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'country',
        'city',
        'latitude',
        'longitude',
        'scan_source',
        'metadata',
        'scanned_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'scanned_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function vial()
    {
        return $this->belongsTo(Vial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Attributes
    public function getLocationStringAttribute()
    {
        $parts = array_filter([$this->city, $this->country]);
        return implode(', ', $parts) ?: 'Unknown';
    }

    // Scopes
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('scanned_at', '>=', now()->subDays($days));
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scanned_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('scanned_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('scanned_at', now()->month)
                    ->whereYear('scanned_at', now()->year);
    }
}
