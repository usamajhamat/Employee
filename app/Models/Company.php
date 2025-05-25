<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'status',
        'created_by',
        'updated_by',
        'is_delete',
        'system_parameters',
    ];

    protected $casts = [
        'system_parameters' => 'array',
        'is_delete' => 'boolean',
    ];

    public function accessPoints()
    {
        return $this->hasMany(AccessPoint::class, 'company_id', 'id');
    }

    public function clients()
    {
        return $this->hasMany(AccessPointClientStatus::class, 'company_id', 'id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'company_id', 'id');
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