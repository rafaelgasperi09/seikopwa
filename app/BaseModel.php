<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function files(){
        return File::whereTabla($this->getTable())->whereRegistroId($this->getKey())->get();
    }
}
