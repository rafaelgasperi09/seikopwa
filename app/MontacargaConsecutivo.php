<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MontacargaConsecutivo extends Model
{
    protected $connection='crm';
    protected $table='consecutivos';
    protected $fillable=['id'];
}
