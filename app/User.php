<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait; // add this trait to your user model

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'idRol'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rol(){
       return $this->belongsTo('App\UsersRole','idRol');
    }

    public function comisiones(){
        return $this->hasMany('App\Comision', 'idRepartidor');
    }

    public function ordenes(){
        return $this->belongsToMany('App\Orden', 'ordenes_repartidores','idRepartidor','idOrden');
    }   
}