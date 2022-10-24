<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = "faq";

    protected $fillable = [
        'title',
        'content',
        'order'
    ];

    public static $status = [
        0 => 'Inactivo',
        1 => 'Activo'
    ];


}
