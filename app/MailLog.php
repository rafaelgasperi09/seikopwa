<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    use SoftDeletes;
    protected $table = 'mail_log';
    protected $guarded = ['id'];

    public function creador(){
        return $this->belongsTo('App\User','user_id');
    }

    public function to(){
        return $this->belongsTo('App\User','to');
    }
}
