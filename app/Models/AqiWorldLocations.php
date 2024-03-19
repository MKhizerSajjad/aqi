<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AqiWorldLocations extends Model
{
    use HasFactory;
    protected $table = 'aqi_world_locations';
    public static $snakeAttributes = false;

    protected $guarded;
}
