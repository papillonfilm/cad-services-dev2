<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageCenter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        
		Schema::create('sysMessageCenter', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->text('message')->nullable();
			$table->integer('userId')->nullable();
			$table->integer('groupId')->nullable();
			$table->integer('applicationId')->nullable();
			$table->dateTime('startDate')->nullable();
			$table->dateTime('endDate')->nullable();
			$table->integer('showTimes')->nullable();
			$table->integer('showedTimes')->default(0);
			$table->integer('createdBy');
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
         Schema::dropIfExists('sysMessageCenter');
    }
}
