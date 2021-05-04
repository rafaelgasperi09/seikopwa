<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MontacargaUser extends Model
{
    protected $connection='crm';
    protected $table='users';

    public function roles(){
        return $this->belongsToMany(MontacargaRol::class,'role_user','user_id','role_id');
    }

    public function getFullNameAttribute() {

        if($this->roles()->first())
         return $this->name.' ('.$this->roles()->first()->name.')'; //Change the format to whichever you desire

        return $this->name;
    }
}
