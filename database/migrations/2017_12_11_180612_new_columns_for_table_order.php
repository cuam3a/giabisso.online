<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewColumnsForTableOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->unsignedInteger('state')->after('phone');
            $table->unsignedInteger('city')->after('phone');
            $table->unsignedInteger('shipping')->default(0)->after('invoice_require');

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
        Schema::table('order', function (Blueprint $table) {
            $table->dropForeign('order_city_foreign');
            $table->dropForeign('order_state_foreign');
            $table->dropColumn(['city','state','shipping']);
        });
    }
}
