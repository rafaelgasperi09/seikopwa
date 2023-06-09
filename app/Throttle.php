<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Throttle extends Model
{
    protected $connection='mysql';
    protected $table='throttle';
}
