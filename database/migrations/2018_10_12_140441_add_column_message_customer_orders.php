<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMessageCustomerOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('new_message_customer')->defualt(0)->after('new_message');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('new_message_customer');
        });
    }
}
