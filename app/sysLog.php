<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sysLog extends Model{
    // Model for sysLog table.
	protected $table = 'sysLog';
	protected $fillable = ['type','category','url','userId','description','ip','userAgent','created_at','updated_At'];

}
