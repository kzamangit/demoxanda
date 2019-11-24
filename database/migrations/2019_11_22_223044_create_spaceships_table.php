<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpaceshipsTable extends Migration
{
    /**
     * Run the migrations to create table spaceships.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spaceships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('class');
            $table->integer('crew');
            $table->string('image');
            $table->decimal('value',10,2);
            $table->string('status');
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
        Schema::dropIfExists('spaceships');
    }
}
