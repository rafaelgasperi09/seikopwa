<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends BaseModel
{
    protected $table = 'supervisores';

    
   public function getFullNameAttribute() {
        return $this->first_name.' '.$this->last_name; //Change the format to whichever you desire
    }
}
