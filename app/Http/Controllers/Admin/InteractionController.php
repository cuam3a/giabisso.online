<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use App\Models\Category;
use App\Models\CategoryCustomerViews;
use App\Models\Product;
use App\Models\ProductCustomerViews;

class InteractionController extends Controller
{
	public function __construct(){
		$this->middleware('auth:admin-web');
	}

    public function categories(){
		//$data['categories'] = Category::where('parent_id','<>',null)->pluck('name','id');
		$data['categories'] =  DB::table('category')
								->join('category as parent', function($join)
								{
									$join->on('category.parent_id', '=', 'parent.id');
								})->select('category.id',DB::raw('CONCAT(parent.name," / " ,category.name) AS name'))->orderBy('name','asC')->get();

		return view('admin.customer.interaction-categories',$data);
	}
	public function products(){
		$data['categories'] = Category::pluck('name','id');
		$data['status'] = Product::$status;		
        $data['imageType'] = Product::$image_type;
        $data['imageTypeText'] = Product::$image_type_text;
		return view('admin.customer.interaction-products',$data);
	}

	/* Return json of customers with category */
	public function ajaxCategoryCustomerViews(Request $request)
	{
		$datatable = $request['datatable'];
		$pagination = $datatable['pagination'];
		$perPage = intval($pagination['perpage']);
		$start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
		$pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

		$sort = $datatable['sort'];
		$sort['dir'] = $sort['sort'];
		$sort['column'] = $sort['field'];

		if($sort['dir'] == null){ $sort['dir'] = 'asc';$sort['column'] = 'customer.id';} 
		$query =  isset($datatable['query']) ? $datatable['query'] : ["generalSearch" => null];
		$search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";
		$category = array_key_exists('Category',$query) ? $query['Category']: "";
		$daterange = array_key_exists('daterange',$query) ? $query['daterange']: "";
		//Obtener datos dependiendo a los parametros
		$data = CategoryCustomerViews::listado($perPage, $pages, $start, $search, $sort, $category, $daterange);
		return response()->json($data);
	}

	/* Return json of customers with category */
	public function ajaxProductCustomerViews(Request $request)
	{
		$datatable = $request['datatable'];
		$pagination = $datatable['pagination'];
		$perPage = intval($pagination['perpage']);
		$start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
		$pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

		$sort = $datatable['sort'];
		$sort['dir'] = $sort['sort'];
		$sort['column'] = $sort['field'];

		if($sort['dir'] == null){ $sort['dir'] = 'asc';$sort['column'] = 'customer.id';} 
		$query =  isset($datatable['query']) ? $datatable['query'] : ["generalSearch" => null];
		$search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";		
        $searchByProductNumber = array_key_exists('searchByProductNumber',$query) ? $query['searchByProductNumber'] : "";
		
		$daterange = array_key_exists('daterange',$query) ? $query['daterange']: "";
		//Obtener datos dependiendo a los parametros
		$data = ProductCustomerViews::listado($perPage, $pages, $start, $search, $sort, $searchByProductNumber,$daterange);
		return response()->json($data);
	}

	//Export excel 'Categorias'
	public function exportCategories(Request $request){
		$category = $request->category;
		$search = $request->generalSearch;
		$sort = $request->sort;
		$daterange = $request->daterange;
		if($sort['dir'] == null){ $sort['dir'] = 'asc';$sort['column'] = 'customer.id';}
		$data = CategoryCustomerViews::listado(null , null, null,$search, $sort, $category, $daterange);
		return Excel::create('HEC-Interacción de usuarios por categoría '.$daterange, function($excel) use ( $data,$daterange) {
			$excel->setCreator('Home Express Center')->setCompany('Home Express Center');
			$excel->sheet('Por categoría', function($sheet) use ( $data,$daterange) {
				$sheet->loadView('admin.customer.export-interaction-categories',[
					'users' => $data['data'],
					'daterange' => $daterange,
				]);
			});
		})->download('xls');
	}
	//Export excel 'Products'
	public function exportProducts(Request $request){
		$search = $request->generalSearch;
		$searchByProductNumber = $request->has('searchByProductNumber') ? $request->searchByProductNumber : false;
		$sort = $request->sort;
		$daterange = $request->daterange;
		if($sort['dir'] == null){ $sort['dir'] = 'asc';$sort['column'] = 'product.id';}
		$data = ProductCustomerViews::listado(null , null, null,$search, $sort, $searchByProductNumber, $daterange);

		return Excel::create('HEC-Interaccion de usuarios por producto '.$daterange, function($excel) use ( $data,$daterange) {
			$excel->setCreator('Home Express Center')->setCompany('Home Express Center');
			$excel->sheet('Por producto', function($sheet) use ( $data,$daterange) {
				$sheet->loadView('admin.customer.export-interaction-products',[
					'daterange' => $daterange,
					'users' => $data['data']
				]);
			});
		})->download('xls');
	}
}
