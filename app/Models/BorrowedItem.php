<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_name', 'asset_id', 'laboratory_id', 'date_borrowed', 'return_date', 'status'
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
