<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $fillable = [
        'email',
        'password',
        'firstname',
        'surname',
        'phone',
        'last_logged_in',
        'locations',
        'tasks_allowed',
        'status',
        'company_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_logged_in' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'email');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'email');
    }

    public function createdCompanies()
    {
        return $this->hasMany(Company::class, 'created_by', 'email');
    }

    public function updatedCompanies()
    {
        return $this->hasMany(Company::class, 'updated_by', 'email');
    }

    public function createdLocations()
    {
        return $this->hasMany(Location::class, 'created_by', 'email');
    }

    public function updatedLocations()
    {
        return $this->hasMany(Location::class, 'updated_by', 'email');
    }

    public function createdAccessPoints()
    {
        return $this->hasMany(AccessPoint::class, 'created_by', 'email');
    }

    public function updatedAccessPoints()
    {
        return $this->hasMany(AccessPoint::class, 'updated_by', 'email');
    }

    public function createdClients()
    {
        return $this->hasMany(AccessPointClientStatus::class, 'created_by', 'email');
    }

    public function updatedClients()
    {
        return $this->hasMany(AccessPointClientStatus::class, 'updated_by', 'email');
    }
}
