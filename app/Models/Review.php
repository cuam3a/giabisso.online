<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model{
	protected $table = "reviews";

	public function producto(){
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function customer(){
		return $this->belongsTo('App\Models\Customer', 'customer_id');
	}

}
