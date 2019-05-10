<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysMailLog', function (Blueprint $table) {
            $table->increments('id');
			$table->string('email',200);
			$table->string('name',200)->nullable();
			$table->string('fromName',100);
			$table->string('fromEmail',200);
			$table->text('message');
			$table->string('subject',200)->nullable();
			$table->dateTime('lastOpenedDate')->nullable();
			$table->integer('openedTimes')->nullable();
			$table->string('ip',80)->nullable();
			$table->text('userAgent')->nullable();
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
        Schema::dropIfExists('sysMailLog');
    }
}
