<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_name', 'asset_id', 'laboratory_id', 'date_borrowed', 'due_date', 'status'
    ];

    // Relationship to Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // Relationship to Laboratory
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
