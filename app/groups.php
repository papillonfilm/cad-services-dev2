<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class groups extends Model
{
    //
	protected $table = 'sysGroups';
	
	
	public function groupApplications(){
		
		return $this->hasMany('App\groups','sysUserGroup','userId','groupId');
		
	}
	
	
	
}
