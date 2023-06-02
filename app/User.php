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
        'name', 'email', 'password','first_name','last_name','crm_user_id','crm_clientes_id','tipo',
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
        }

    }

    public function roles(){
        return $this->belongsToMany(Rol::class,'role_users','user_id','role_id');
    }

    public function persistences(){
        return $this->hasMany(Persistence::class);
    }

    public function isOnGroup($group_name)
    {
        $sentryUser = Sentinel::findUserById($this->id);
        $group = Sentinel::findRoleBySlug($group_name); 
        return $sentryUser->inRole($group);
    }

    public function isCliente()
    {
        $sentryUser = Sentinel::findUserById($this->id);
        if(!empty($sentryUser->crm_cliente_id) or !empty($sentryUser->crm_clientes_id))
            return true;
        else
            return false;
    }

    public function getFullName(){
        return $this->first_name.' '.$this->last_name;
    }

    public function getFullNameAttribute() {
        return $this->first_name.' '.$this->last_name; //Change the format to whichever you desire
    }



    public function clientes(){
        return Cliente::whereIn('id',explode(',',$this->crm_clientes_id))->get();
    }

    public function isSupervisor()
    {
        $a=$this->isOnGroup('supervisor');
        $b=$this->isOnGroup('supervisor-alquiler');
        $c=$this->isOnGroup('supervisor-servicio-tecnico');
        $d=$this->isOnGroup('supervisor-repuestos');
        $r=$this->roles;
        
        if($a or $b or $c or $d)
            return true;

        return false;
    }

    public function scopeIsActive($query)
    {
      return $query->join('activations', 'users.id','=','activations.user_id')->where('activations.completed',1);
    }
    
    public function scopeFilterClientes($query)
    {
        $cu=current_user();
        if($cu->isSupervisor())
            return $query->whereNull('crm_clientes_id');

        return $query;
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByRoles($query,array $roles)
    {
        return $query->whereHas('roles',function ($q) use($roles){
            $q->whereIn('role_users.role_id',$roles);
        });

    }

   
}
