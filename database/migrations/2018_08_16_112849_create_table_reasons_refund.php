<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReasonsRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reasons_refund', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        $data = array(
            array('description' => 'Pedido accidental', "created_at" =>  \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()),
            array('description' => 'Funcionamiento o calidad no adecuados', "created_at" =>  \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()),
            array('description' => 'El producto está dañado', "created_at" =>  \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()),
            array('description' => 'Faltan partes o accesorios', "created_at" =>  \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()),
            array('description' => 'No es el producto que pedí', "created_at" =>  \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()),
            array('description' => 'Defectuoso o no funciona bien', "created_at" =>  \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()),
            array('description' => 'Otro motivo...', "created_at" =>  \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now())
        );

        DB::table('reasons_refund')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reasons_refund');
    }
}

