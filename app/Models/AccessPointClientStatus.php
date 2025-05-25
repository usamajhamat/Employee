<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessPointClientStatus extends Model
{
    protected $table = 'ap_clients';

    protected $fillable = [
        'ap_id',
        'ap_mac',
        'ssid',
        'client_mac',
        'signal_strength',
        'idle_time',
        'bytes_rx',
        'bytes_tx',
        'pkts_rx',
        'pkts_tx',
        'status',
        'company_id',
        'created_by',
        'updated_by',
    ];

    public function accessPoint()
    {
        return $this->belongsTo(AccessPoint::class, 'ap_id', 'ap_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}