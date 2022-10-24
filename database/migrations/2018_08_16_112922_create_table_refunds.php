<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRefunds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->integer('quantity')->default(0);
            $table->unsignedInteger('reason_id');
            $table->text('comment')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product')->onDelete('no action');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('no action');
            $table->foreign('reason_id')->references('id')->on('reasons_refund')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}

