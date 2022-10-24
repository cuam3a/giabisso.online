<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = "config";

    protected $fillable = [
        'name',
        'value',
        'type'
    ];

    public static $status = [
        0 => 'Inactivo',
        1 => 'Activo'
    ];


}
