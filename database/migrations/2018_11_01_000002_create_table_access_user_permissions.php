<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAccessUserPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
      Schema::create('accessUserPermissions', function (Blueprint $table) {
            $table->increments('id');
		  	$table->integer('userId');
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
         Schema::dropIfExists('accessUserPermissions');
    }
}
