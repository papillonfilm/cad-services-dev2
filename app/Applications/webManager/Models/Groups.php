<?php

namespace App\Applications\WebManager\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    //
	protected $fillable = ['name','description','addedBy'];
	
	protected $table = 'sysGroups';
	
	
	public function applications(){
		 
		return $this->belongsToMany('App\Applications\WebManager\Models\Application','sysGroupApplication','groupId','applicationId');
		
	}
	
	public function users(){
		return $this->belongsToMany('App\Applications\WebManager\Models\Users','sysUserGroup','groupId','userId');	
	
	}
	
	
}
