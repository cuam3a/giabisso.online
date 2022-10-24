<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnShipmentTypeIdShipmentCostToProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->unsignedInteger('shipment_id')->nullable()->after('weight_unit');
            $table->float('shipment_cost')->nullable()->after('shipment_id');

            $table->foreign('shipment_id')->references('id')->on('shipment_types')->onDelete('SET NULL');

       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropForeign('product_shipment_id_foreign');
            $table->dropColumn('shipment_id');
            $table->dropColumn('shipment_cost');
       });
        
    }
}
