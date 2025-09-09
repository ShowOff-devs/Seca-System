<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidLog extends Model
{
    protected $fillable = [
        'rfid_no','device_id','scan_type','scanned_at'
    ];

    // Relationship to Asset based on rfid_no
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'rfid_no', 'rfid_no');
    }

    // Relationship to Laboratory based on device_id
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class, 'device_id', 'id');
    }
}
