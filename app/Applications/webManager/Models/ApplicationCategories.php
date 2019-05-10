<?php

namespace App\Applications\WebManager\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationCategories extends Model{
    
	// Table name in database
	protected $table = 'sysApplicationCategories';
	
	// Fields that can be written using this model
	protected $fillable = ['name','description'];
	
	
}
