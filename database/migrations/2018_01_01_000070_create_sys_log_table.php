<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysLog', function (Blueprint $table) {
            $table->increments('id');
			$table->string('type',50);
			$table->string('category',100);
			$table->string('url');
			$table->integer('userId');
			$table->text('description');
			$table->string('ip',20);
			$table->text('userAgent');
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
        Schema::dropIfExists('sysLog');
    }
}
