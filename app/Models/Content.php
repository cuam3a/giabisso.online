<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = "contents";

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
