<?php

namespace App\Applications\WebManager\Models;
 

use Illuminate\Database\Eloquent\Model;

class Application extends Model{
    //
	protected $table = 'sysApplications';
	protected $fillable = ['name','description','iconPath','author','version','addedBy','categoryId'];
	

	
	public function groups(){
	
		return $this->belongsToMany('App\Applications\WebManager\Models\Groups','sysGroupApplication','applicationId','groupId');
	 
		
	}
	

	
	
}
