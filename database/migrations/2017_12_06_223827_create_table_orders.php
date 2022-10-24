<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('customer_id')->nullable();
            $table->string('name', 150);
            $table->string('lastname', 120);
            $table->string('email',60);
            $table->string('f_name')->nullable();
            $table->string('f_rfc')->nullable();
            $table->string('f_email',60)->nullable();
            $table->string('f_address')->nullable();
            $table->unsignedInteger('f_city');
            $table->unsignedInteger('f_state');
            $table->string('tracking_number')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('payment_status')->default(0);
            $table->date('payment_date')->nullable();
            $table->double('subtotal',2)->default(0);
            $table->double('total',2)->default(0);
            $table->timestamps();

            $table->foreign('f_state')->references('id')->on('state')->onDelete('no action');
            $table->foreign('f_city')->references('id')->on('city')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
