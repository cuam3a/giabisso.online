<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDescriptionToSeoRenamedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename("keywords", "seo");

        Schema::table('seo', function (Blueprint $table) {
            $table->string('description')->nullable()->after('keywords');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename("seo", "keywords");

        Schema::table('keywords', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
