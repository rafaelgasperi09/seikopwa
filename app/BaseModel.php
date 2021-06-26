<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function files(){
        return $this->hasMany(File::class,'registro_id')->where('files.tabla',$this->getTable());
    }
}
