<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZipcodeTableCustomerAndOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('zipcode',5)->after('state');
        });

        Schema::table('address_book', function (Blueprint $table) {
            $table->string('zipcode',5)->after('state');
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
            $table->dropColumn('zipcode');
        });

        Schema::table('address_book', function (Blueprint $table) {
            $table->dropColumn('zipcode');
        });
    }
}
