<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAddessBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_book', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->string('address');
            $table->string('street_number',20);
            $table->string('suit_number',20)->nullable();
            $table->string('between_streets',150)->nullable();
            $table->string('neighborhood',150);
            $table->unsignedInteger('city');
            $table->unsignedInteger('state');
            $table->string('instructions_place',150)->nullable();
            $table->timestamps();

            //Foreign keys
            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade');
            $table->foreign('state')->references('id')->on('state')->onDelete('no action');
            $table->foreign('city')->references('id')->on('city')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_book');
    }
}
