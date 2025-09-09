<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_name',
        'report_type',
        'start_date',
        'end_date',
        'report_parameters',
        'status',
        'file_path',
        'schedule_frequency',
        'last_generated_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'report_parameters' => 'array',
        'last_generated_at' => 'datetime'
    ];

    // Constants for report types
    const TYPE_MONTHLY = 'monthly';
    const TYPE_WEEKLY = 'weekly';
    const TYPE_ANNUAL = 'annual';
    const TYPE_CUSTOM = 'custom';

    // Constants for report status
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    // Constants for schedule frequency
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_MONTHLY = 'monthly';
    const FREQUENCY_ANNUAL = 'annual';
}
