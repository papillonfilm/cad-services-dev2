<?php
 
namespace App\Applications\WebManager\Models;


use Illuminate\Database\Eloquent\Model;
 
 

class groupApplications extends Model{
  
	protected $table ='sysGroupApplication';
	
	protected $fillable = ['groupId','applicationId','addedBy'];
	
	 
  
	
	
}
