<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WifiToken extends Model
{
    protected $table = 'wifi_tokens';

    protected $fillable = [
        'mac',
        'token',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}