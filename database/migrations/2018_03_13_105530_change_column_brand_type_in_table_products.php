<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnBrandTypeInTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('brand');
            $table->unsignedInteger('brand_id')->nullable()->after('category_id');            
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('no action');           
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
            $table->dropForeign('product_brand_id_foreign');
            $table->dropColumn('brand_id');
            $table->string('brand')->nullable()->after('category_id');     
        });
    }
}
