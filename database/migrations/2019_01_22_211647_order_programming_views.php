<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderProgrammingViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_programming_views', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id');
            $table->unsignedInteger('product_id')->nullable();
            $table->string('sku',30);
            $table->string('name',100)->nullable();
            $table->string('category',40);
            $table->double('unit_price',2);
            $table->unsignedInteger('quantity');
            $table->date('date_send');
            $table->string('status');
            $table->timestamps();
            
            //Foreign keys
            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('CASCADE');
            $table->foreign('product_id')->references('id')->on('product')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_programming_views');
    }
}
