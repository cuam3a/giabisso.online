<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryViews extends Model
{
    protected $table = "category_views";
    protected $fillable = ['id','category_id','views'];
    protected $dates = ['created_at','updated_at'];

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
