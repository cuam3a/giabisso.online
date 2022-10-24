<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableValuesInTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('f_city')->nullable()->change();
            $table->unsignedInteger('f_state')->nullable()->change();
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['orders_state_foreign','orders_city_foreign']);
            $table->unsignedInteger('f_city')->nullable(false)->change();
            $table->unsignedInteger('f_state')->nullable(false)->change();
        });
    }
}
