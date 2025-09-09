<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidScan extends Model
{
    protected $fillable = [
        'rfid_no',
        'device_id',
        'scan_type',
        'scanned_at'
    ];

    protected $casts = [
        'scanned_at' => 'datetime'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'rfid_no', 'rfid_no');
    }
} 