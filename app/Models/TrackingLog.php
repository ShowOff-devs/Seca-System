<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'laboratory_id',
        'movement_type',
        'timestamp',
        'rfid_no'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
} 