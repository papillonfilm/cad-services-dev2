<?php
 
namespace App\Applications\WebManager\Models;

use Illuminate\Database\Eloquent\Model;

class userGroup extends Model
{
    //
	protected $table = 'sysUserGroup';
	protected $fillable= ['groupId','userId','addedBy'];
	 
}
