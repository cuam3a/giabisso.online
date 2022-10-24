<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShipmentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$data = array(
            array('name'=>'terrestre', 'text'=> 'Terrestre', 'description'=> '5 a 7 días', 'default' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
            array('name'=>'aereo', 'text' => 'Aéreo', 'description' => '2 a 3 días', 'default' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now())
        );

        DB::table('shipment_types')->insert($data);*/

        DB::unprepared("INSERT INTO `shipment_types` (`id`, `name`, `text`, `description`, `default`) VALUES
			(1, 'terrestre', 'terrestre', '5 a 7 días', 1 ),
			(2, 'aereo', 'Aéreo', '2 a 3 días', 0 )");
    }
}
