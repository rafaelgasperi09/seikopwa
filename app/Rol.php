<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends BaseModel
{
    protected $table='roles';

    public function getFullNameAttribute() {
        return $this->name.' ('.$this->tipo.')'; //Change the format to whichever you desire
    }
}
