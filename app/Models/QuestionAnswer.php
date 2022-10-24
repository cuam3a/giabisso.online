<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model{
    protected $table = "questions_answers";


    public function producto(){
    	return $this->belongsTo(Product::class,"product_id");
    }

    public static function todas(){
    	return QuestionAnswer::all();
    }

    public static function pending(){
    	return self::todas()->where("status",2);
    }

    public static function response(){
    	return self::todas()->where("status",1);
    }

}
