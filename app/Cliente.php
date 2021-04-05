<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends BaseModel
{
    protected $connection='crm';
    protected $table = 'customers_exp';
    protected $primaryKey='ID_compania';

    public function equipos(){
        return $this->hasMany(Equipo::class);
    }
}
