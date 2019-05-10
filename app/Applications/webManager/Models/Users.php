<?php

namespace App\Applications\WebManager\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //
	
	protected $table = 'sysUsers';
	
	
	//Groups that the user have access to
	public function groups(){
		
	return $this->belongsToMany('App\Applications\WebManager\Models\Groups','sysUserGroup','userId','groupId');	
 
	}
	
	
	
}
