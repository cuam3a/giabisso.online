<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsTableAddressBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_book', function (Blueprint $table) {
            $table->string('cell_phone',20)->nullable()->after('customer_id');
            $table->string('phone',20)->nullable()->after('customer_id');
            $table->string('lastname', 120)->after('customer_id');
            $table->string('name', 150)->after('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address_book', function (Blueprint $table) {
            $table->dropColumn(['name','lastname','cell_phone','phone']);
        });
    }
}
