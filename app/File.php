<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table='files';
    protected $guarded=['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
