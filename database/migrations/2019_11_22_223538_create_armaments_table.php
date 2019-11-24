<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArmamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armaments', function (Blueprint $table) {
            
            $table->increments('id');            
            $table->integer('spaceship_id')->unsigned();
            $table->foreign('spaceship_id')
                  ->references('id')->on('spaceships')
                  ->onDelete('cascade');
            $table->string('title');
            $table->integer('qty')->unsigned();
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
        Schema::dropIfExists('armaments');
    }
}
