<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAccessGroupPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
      Schema::create('accessGroupPermissions', function (Blueprint $table) {
            $table->increments('id');
		  	$table->integer('groupId');
		  	$table->integer('accessRoutesId');
		  	$table->integer('accessGrantedBy');
		  	$table->tinyInteger('hasAccess');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('accessGroupPermissions');
    }
}
