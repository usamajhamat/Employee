<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApUserData extends Model
{
    protected $table = 'ap_users';

    protected $fillable = [
        'ap_id',
        'ssid',
        'ip_address',
        'mac_address',
        'auth_method',
        'created_at',
        'user_agent',
        'client_id',
        'first_name',
        'last_name',
        'picture',
        'gender',
        'email',
        'phone',
        'birthday',
        'location_name',
        'city',
        'country',
        'country_code',
        'latitude',
        'longitude',
        'logins_count',
        'terms_and_conditions',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function accessPoint()
    {
        return $this->belongsTo(AccessPoint::class, 'ap_id', 'ap_id');
    }
}