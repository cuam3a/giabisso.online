<?php
namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    protected $table = "address_book";
    protected $fillable = [
        'customer_id', 'address', 'street_number','suit_number','between_streets','neighborhood','city','state','zipcode','instructions_place'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    
    public function City(){
        return $this->belongsTo('App\Models\City', 'city');
    }
    public function State(){
        return $this->belongsTo('App\Models\State', 'state');
    }
    
    public function fullname(){
        return $this->name.' '.$this->lastname;
    }

    public function fullAddress(){
        return $this->address.' '.$this->street_number.' '.$this->suit_number.' '.$this->between_streets.' <br>'.$this->City->name.', '.$this->State->name.' <br> C.P. '.$this->zipcode;
    }
}
