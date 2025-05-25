<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkInfo extends Model
{
    use HasFactory;

protected $table = 'network_infos';

   protected $guarded = []; // Allow mass assignment for all fieldss

}
