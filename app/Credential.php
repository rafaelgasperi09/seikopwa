<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credential extends BaseModel
{

    protected $table = 'credentials';
    protected $fillable=['user_id','encrypted_password'];
}
