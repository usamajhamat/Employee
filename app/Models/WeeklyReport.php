<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
     use HasFactory;

    protected $table = 'weekly_reports';

    protected $fillable = [
        'company_name',
        'date',
        'total_candidates',
        'interviewed',
        'passed',
        'hostel',
        'walkin',
    ];

    protected $casts = [
        'date' => 'date',
        'total_candidates' => 'integer',
        'interviewed' => 'integer',
        'passed' => 'integer',
        'hostel' => 'integer',
        'walkin' => 'integer',
    ];
}
