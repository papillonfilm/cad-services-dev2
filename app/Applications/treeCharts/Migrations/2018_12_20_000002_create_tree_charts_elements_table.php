<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreeChartsElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treeChartsElements', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('chartId');
			$table->string('parentId');
            $table->string('name', 100);
			$table->string('color')->nullable();
			$table->integer('userId');
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
        Schema::dropIfExists('treeChartsElements');
    }
}
