<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConfigDroptableSeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('status')->default();
            $table->timestamps();
        });

        Schema::dropIfExists('seo');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keywords');
            $table->string('description');
            $table->tinyInteger('status')->default();
            $table->timestamps();
        });

        Schema::dropIfExists('config');
    }
}
