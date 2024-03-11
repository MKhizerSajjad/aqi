<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateAqi extends Model
{
    use HasFactory;
    protected $table = 'state_aqi';
    public static $snakeAttributes = false;

    protected $guarded;
}
