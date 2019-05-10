<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'initial',
		'lastname',
		'lastname2',
		'mobile',
		'phone',
		'email',
		'password',
		'username',
		'accountEnable',
		'accountActivated'
    ];

	
	protected $table = 'sysUsers';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	
	
	public function groups(){
		
		return $this->belongsToMany('App\groups','sysUserGroup','userId','groupId');
		
	}
	
	
	
}
