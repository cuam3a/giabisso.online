<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("customer_id");
            $table->integer("product_id");
            $table->integer("rating");
            $table->text("review")->nullable();
            $table->timestamps();
        });

        Schema::table('product', function (Blueprint $table) {
            $table->integer('rating')->default(0)->after('status');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');

        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
}
