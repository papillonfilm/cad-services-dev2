<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAccessRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
      Schema::create('accessRoutes', function (Blueprint $table) {
            $table->increments('id');
		  	$table->string('applicationName');
		  	$table->string('url');
		  	$table->string('description',250)->nullable();
		  	$table->string('method')->nullable();
		  	
		  	$table->integer('addedBy');
		 
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
         Schema::dropIfExists('accessRoutes');
    }
}
