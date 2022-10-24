<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Slider extends Model
{
    protected $table = "slider_images";

    protected $fillable = [
        'description', 'path'
    ];

    public static $status = [
        0 => 'Inactivo',
        1 => 'Activo'
    ];

    public static function getSlidersRandom(){
        $static_slider_order_id = 1;
        // Get first slider with order = 1
        $first_slider = Slider::where('order', $static_slider_order_id)->where('status', 1)->get();
        // Merge first_slider at first position and after, all sliders.
        return $first_slider->merge(Slider::orderBy(DB::raw('RAND()'))->where('status', 1)->where('order','<>', $static_slider_order_id)->get());
    }


}
