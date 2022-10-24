<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\Brand;

class BrandController extends Controller
{
	public function __construct(){
		$this->middleware('auth:admin-web');
	}
    
    public function getBrandsInCategory(Request $request)
    {
        $brand = new Brand();
        $brands = $brand->getBrandsInCategory($request->category);
    	return response()->json($brands);
    }


	
}