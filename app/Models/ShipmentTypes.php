<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTypes extends Model
{
    protected $table = "shipment_types";

    protected $fillable = [
        'name',
        'cost',
        'default',
        'description'
    ];

    public static $status = [
        0 => 'Inactivo',
        1 => 'Activo'
    ];


}
