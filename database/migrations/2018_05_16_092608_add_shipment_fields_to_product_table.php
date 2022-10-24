<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShipmentFieldsToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
             $table->float('width')->nullable()->after('image_type');
             $table->float('height')->nullable()->after('width');
             $table->float('length')->nullable()->after('height');
             $table->float('weight')->nullable()->after('length');
             $table->string('dimension_unit')->nullable()->after('weight');
             $table->string('weight_unit')->nullable()->after('dimension_unit');
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
            $table->dropColumn('width');
            $table->dropColumn('height');
            $table->dropColumn('length');
            $table->dropColumn('weight');
            $table->dropColumn('dimension_unit');
            $table->dropColumn('weight_unit');
       });
    }
}
