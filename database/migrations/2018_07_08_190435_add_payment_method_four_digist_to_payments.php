<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentMethodFourDigistToPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('card_holder')->nulleable()->after('mp_method');
            $table->string('payment_type')->default(0)->after('mp_method');
            $table->string('last_four_digits')->default(0)->after('mp_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('card_holder');
            $table->dropColumn('payment_type');
            $table->dropColumn('last_four_digits');
        });
    }
}
