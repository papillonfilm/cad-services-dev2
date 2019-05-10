<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenendEmailLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysOpenedEmail', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('emailId');
			$table->string('ip',80)->nullable();
			$table->string('userAgent',200)->nullable();
            $table->dateTime('dateAdded');
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
        Schema::dropIfExists('sysOpenedEmail');
    }
}
