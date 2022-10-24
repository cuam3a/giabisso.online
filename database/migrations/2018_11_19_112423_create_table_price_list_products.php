<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePriceListProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices_lists_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('price_list_id');
            $table->unsignedInteger('product_id');
            $table->float('price');
            //Foreign keys
            $table->foreign('product_id')->references('id')->on('product')->onDelete('CASCADE');
            $table->foreign('price_list_id')->references('id')->on('prices_lists')->onDelete('CASCADE');
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
        Schema::dropIfExists('prices_lists_products');
    }
}
