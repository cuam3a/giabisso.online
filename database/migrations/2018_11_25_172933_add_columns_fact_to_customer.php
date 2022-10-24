<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsFactToCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->string('f_name')->nullable()->after('phone');
            $table->string('f_rfc')->nullable()->after('f_name');
            $table->string('f_email',60)->nullable()->after('f_rfc');
            $table->string('f_address')->nullable()->after('f_email');
            $table->string('f_zipcode')->nullable()->after('f_address');
            $table->unsignedInteger('f_city')->after('f_zipcode');
            $table->unsignedInteger('f_state')->after('f_city');
            $table->decimal('credit')->after('f_state');
            $table->integer('credit_days')->after('credit');
            $table->integer('credit_status')->defualt(0)->after('credit_days');

            $table->foreign('f_state')->references('id')->on('state')->onDelete('no action');
            $table->foreign('f_city')->references('id')->on('city')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn(['f_name','f_rfc','f_email','f_address','f_zipcode','f_city','f_state','credit','credit_days']);
        });
    }
}
