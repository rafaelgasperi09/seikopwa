<?php

namespace App;

use Carbon\Carbon;
use Sentinel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use Notifiable;
    use HasPushSubscriptions; // add the trait to your class
    protected $table ='users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','first_name','last_name','crm_cliente_id','crm_user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always Store Hashed passwords
     * @param [type] $password [description]
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getLastLoginAttribute($attr) {
        if(isset($attr)){
            return Carbon::parse($attr)->format('d-m-Y'); //Change the format to whichever you desire
        }else{
            return 'Nunca';
        }

    }

    public function roles(){
        return $this->belongsToMany(Rol::class,'role_users','user_id','role_id');
    }

    public function isOnGroup($group_name)
    {
        $sentryUser = Sentinel::findUserById($this->id);
        $group = Sentinel::findRoleByName($group_name);
        return $sentryUser->inRole($group);
    }

    public function isCliente()
    {
        $sentryUser = Sentinel::findUserById($this->id);
        $group = Sentinel::findRoleById(3);
        return $sentryUser->inRole($group);
    }

    public function getFullName(){
        return $this->first_name.' '.$this->last_name;
    }

    public function cliente(){
        return Cliente::find($this->crm_cliente_id);
    }
}
