<?php

use Illuminate\Database\Seeder;

class AdminModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::unprepared("INSERT INTO `admin_modules` (`id`, `name`) VALUES
			(1, 'Usuarios'),
			(2, 'Productos'),
			(3, 'Clientes'),
            (4, 'Pedidos'),
            (5, 'Preguntas frecuentes'),
            (6, 'Métodos de envío'),
            (7, 'Suscripciones'),
            (8, 'Configuración de Tienda')");

    }
}
