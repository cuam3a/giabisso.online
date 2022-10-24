<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('state_id');
            $table->string('code', 3);
            $table->string('name', 50);
            $table->unsignedTinyInteger('active')->default(1);

            $table->foreign('state_id')->references('id')->on('state')->onDelete('cascade');
        });

        //Llenar datos de Estados y Ciudades
        Artisan::call('db:seed', array('--class' => 'StatesTableSeeder'));
        Artisan::call('db:seed', array('--class' => 'CitiesTableSeeder'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city');
    }
}
