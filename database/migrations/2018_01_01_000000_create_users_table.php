<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysUsers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
			$table->string('initial',10)->nullable();
			$table->string('lastname',100)->nullable();
			$table->string('lastname2',100)->nullable();
			$table->string('gender',10)->nullable();
            $table->string('email',120)->unique();
			$table->string('username',120)->unique();
            $table->string('password',100);
			$table->string('phone',30)->nullable();
			$table->string('mobile',30)->nullable();
			$table->tinyInteger('accountEnable')->default(1);
			$table->tinyInteger('accountActivated')->default(1);
			$table->timestamp('lastLogin')->nullable();
			$table->timestamp('enableOnDate')->default(date("Y-m-d H:i:s"));
			$table->timestamp('disableOnDate')->default("2038-01-01 00:00:00");
			$table->string('profilePicture',250)->nullable();
			$table->date('birthDate')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('sysUsers');
    }
}
