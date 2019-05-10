<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sysApplications', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->text('description')->nullable();
			$table->string('iconPath');
			$table->string('author')->nullable();
			$table->string('version')->nullable();
			$table->string('appKey');
			$table->string('location');
			$table->integer('addedBy');
			$table->integer('categoryId');
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
        Schema::dropIfExists('sysApplications');
    }
}
