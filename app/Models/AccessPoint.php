<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessPoint extends Model
{
    protected $fillable = [
        'ap_id',
        'ap_name',
        'ap_mac',
        'add_date',
        'last_bytes_rx',
        'last_bytes_tx',
        'last_pkts_rx',
        'last_pkts_tx',
        'last_load_percentage',
        'last_free_ram',
        'last_total_ram',
        'last_used_ram',
        'last_used_ram_percentage',
        'last_uptime',
        'last_timestamp',
        'no_of_clients_connected',
        'public_ip',
        'status',
        'last_api_updated_at',
        'company_id',
        'firmware',
        'location_id',
        'created_by',
        'updated_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function clients()
    {
        return $this->hasMany(AccessPointClientStatus::class, 'ap_id', 'ap_id');
    }

    public function users()
    {
        return $this->hasMany(ApUserData::class, 'ap_id', 'ap_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'email');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'email');
    }
}