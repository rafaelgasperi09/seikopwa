<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Tecnico extends BaseModel
{
    protected $table = 'tecnicos';
    use Notifiable;
    
   public function getFullNameAttribute() {
        return $this->first_name.' '.$this->last_name; //Change the format to whichever you desire
    }
}

