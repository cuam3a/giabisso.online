<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColumnNotNullableTableOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_detail', function (Blueprint $table) {

            //$table->dropForeign('order_detail_order_id_foreign');
            //$table->unsignedInteger('order_id')->nullable(false)->change();
            //$table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_detail', function (Blueprint $table) {
           /* $table->dropForeign('order_detail_order_id_foreign');
            $table->unsignedInteger('order_id')->nullable()->change();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');*/
        });
    }
}
