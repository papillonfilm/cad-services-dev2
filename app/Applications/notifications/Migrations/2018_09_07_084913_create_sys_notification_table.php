<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysNotification', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('userId');
			$table->timestamp('viewDate')->nullable();
			$table->text('notification');
			$table->tinyInteger('readed')->default(0);
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
        Schema::dropIfExists('sysNotification');
    }
}
