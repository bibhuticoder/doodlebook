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
            $table->boolean('infinite')->nullable();
            $table->int('interval')->nullable();
            $table->string('sequence')->nullable();
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
        Schema::dropIfExists('doodles');
    }
}
