<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSliderImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('slider_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('path')->nullable();
            $table->string('image_type')->nullable();
            $table->string('image_name')->nullable();
            $table->tinyInteger('order');
            $table->tinyInteger('status')->default();
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
        Schema::dropIfExists('slider_images');
    }
}
