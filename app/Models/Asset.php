<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'rfid_no', 'serial_number', 'type', 'date_added', 'laboratory_id', 'status'
    ];

    public function laboratory() {
        return $this->belongsTo(Laboratory::class);
    }
}
