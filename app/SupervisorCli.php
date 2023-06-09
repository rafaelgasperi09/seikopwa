<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SupervisorCli extends BaseModel
{
    protected $table = 'supervisores_cli';
    use Notifiable;
    
   public function getFullNameAttribute() {
        return $this->first_name.' '.$this->last_name; //Change the format to whichever you desire
    }
}

