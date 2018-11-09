<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animation_details', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('infinite')->default(1);
            $table->integer('interval')->default(100);
            $table->string('sequence')->nullable();
            $table->integer('frame_width')->default(400);
            $table->integer('frame_height')->default(400);
            $table->integer('doodle_id')->nullable();
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
        Schema::dropIfExists('animation_details');
    }
}
