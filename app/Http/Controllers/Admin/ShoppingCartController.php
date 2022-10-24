<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use App\Models\ShoppingCart;

class ShoppingCartController extends Controller
{
    public function __construct(){
		$this->middleware('auth:admin-web');
    }
    
    public function listShoppingCart()
    {
        $data = [];
        $data['carts'] = ShoppingCart::all();
    	return view('admin.carts.list',$data);
    }

        /* Return json of customers */
        public function ajaxCarts(Request $request)
        {
            $datatable = $request['datatable'];
            $pagination = $datatable['pagination'];
            $perPage = intval($pagination['perpage']);
            $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
            $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;
    
            $sort = $datatable['sort'];
            $order['dir'] = $sort['sort'];
            $order['column'] = $sort['field'];
    
            if($order['dir'] == null){ $order['dir'] = 'asc';$order['column'] = 'shoppingcart.identifier';} 
    
            $query =  isset($datatable['query']) ? $datatable['query'] : ["generalSearch" => null];
            $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";
    
            //Obtener datos dependiendo a los parametros
            $data = ShoppingCart::listado($perPage, $pages, $start, $search, $order);
            return response()->json($data);
        }

        public function exportCarts(){
            $data = ShoppingCart::listado(1, 1, 1, "", ['column' => 'email','dir' =>'asc']);
            $total= count($data['data'])+3;
            return Excel::create('HEC-Carritos olvidados', function($excel) use ( $data,$total) {
                $excel->setCreator('Home Express Center')->setCompany('Home Express Center');
                $excel->sheet('Listado de clientes', function($sheet) use ( $data,$total) {
                    $sheet->cells('A3'.':D'.$total, function($cells) {
                        $cells->setValignment('top');      
                    });
                    $sheet->loadView('admin.carts.export',[
                        'carts' => $data['data']
                    ]);
                });
            })->download('xls');
        }
}