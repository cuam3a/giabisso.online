<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('instructions_place',150)->nullable()->after('phone');
            $table->string('neighborhood',150)->after('phone');
            $table->string('between_streets',150)->nullable()->after('phone');
            $table->string('suit_number',20)->nullable()->after('phone');
            $table->string('street_number',20)->after('phone');
            $table->string('cell_phone', 30)->nullable()->after('phone');
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
            $table->dropColumn(['cell_phone','street_number','suit_number',
                                'between_streets','neighborhood','instructions_place']);
        });
    }
}
