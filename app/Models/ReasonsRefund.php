<?php

namespace App\Models;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Share;

class ReasonsRefund extends Model
{
    protected $table = "reasons_refund";
    protected $fillable = [
        'descripcion'
    ];


}
