<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
           $table->increments('id');
            $table->string('name');
            $table->string('sku',30);
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->string('brand')->nullable();
            $table->float('regular_price');
            $table->float('offer_price')->nullable();
            $table->timestamp('offer_date_start')->nullable();
            $table->timestamp('offer_date_end')->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->string('image_name')->nullable();
            $table->unsignedInteger('image_type')->default(0);//local
            $table->tinyInteger('status')->default();
            $table->timestamps();

            //Foreign keys
            $table->foreign('category_id')->references('id')->on('category')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
