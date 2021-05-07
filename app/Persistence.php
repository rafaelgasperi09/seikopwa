<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persistence extends Model
{
    protected $table='persistences';

    public function user(){
        return $this->belongsTo(User::class);
    }

}
