<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location']; // Update fields as needed

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
