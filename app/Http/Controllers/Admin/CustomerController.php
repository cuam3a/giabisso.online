<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Excel;
use DB;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\PricesLists;
use App\Models\PricesListProducts;

class CustomerController extends Controller
{
	public function __construct(){
		$this->middleware('auth:admin-web');
	}
    
    //List of orders
    public function listCustomers()
    {
        $data = [];
    	return view('admin.customer.list',$data);
    }

	//Customer Detail
    public function detail(Customer $customer)
    {   
        $data = [];
        $data['customer'] = $customer;
        $data['priceList'] = PricesLists::get();//GCUAMEA
        
    	return view('admin.customer.detail',$data);
    }
    
    /* Return json of customers */
    public function ajaxCustomers(Request $request)
    {
        $datatable = $request['datatable'];
        $pagination = $datatable['pagination'];
        $perPage = intval($pagination['perpage']);
        $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
        $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

        $sort = $datatable['sort'];
        $order['dir'] = $sort['sort'];
        $order['column'] = $sort['field'];

        if($order['dir'] == null){ $order['dir'] = 'asc';$order['column'] = 'customer.id';} 

        $query =  isset($datatable['query']) ? $datatable['query'] : ["generalSearch" => null];
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";

        //Obtener datos dependiendo a los parametros
        $data = Customer::listado($perPage, $pages, $start, $search, $order);
        return response()->json($data);
    }

    //export excel clients
    public function exportClients(){
        $data = Customer::all();

        return Excel::create('JAT-Clientes', function($excel) use ( $data) {
            //$excel->setCreator('Home Express Center')->setCompany('Home Express Center');
            $excel->setCreator('Justo a Tiempo')->setCompany('Justo a Tiempo');
            $excel->sheet('Listado de clientes', function($sheet) use ( $data) {
                $sheet->loadView('admin.customer.exportcustomer',[
                    'customer' => $data
                ]);
            });
        })->download('xls');
    }

    //export excel orders for customer
    public function exportOrders($customer){
        $data = Customer::find($customer)->orders;
        
        return Excel::create('HEC-Pedidos', function($excel) use ( $data) {
            $excel->setCreator('Home Express Center')->setCompany('Home Express Center');
            $excel->sheet('Listado de pedidos', function($sheet) use ( $data) {
                $sheet->loadView('admin.customer.export',[
                    'orders' => $data
                ]);
            });
        })->download('xls');
    }

    /* Return json of products */
    public function ajaxProductCustomerViews(Request $request)
    {
        $datatable = $request['datatable'];
        $pagination = $datatable['pagination'];
        $perPage = intval($pagination['perpage']);
        $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
        $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

        $sort = $datatable['sort'];
        $order['dir'] = $sort['sort'];
        $order['column'] = $sort['field'];

        if($order['dir'] == null){ $order['dir'] = 'asc';$order['column'] = 'product.name';} 

        $query =  isset($datatable['query']) ? $datatable['query'] : ["Status" => "","Category" => "0", "Subcategory" => "0", "generalSearch" => null];
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";
        $searchByProductNumber = array_key_exists('searchByProductNumber',$query) ? $query['searchByProductNumber'] : "";

        //Obtener datos dependiendo a los parametros
        $data = Product::listado($perPage, $pages, $start, $search, $order, $category, $status, $subcategory, $brand, $searchByProductNumber);
        return response()->json($data);
    }

    //INICIO GCUAMEA
    //List Price
    public function pricesLists()
    {   
        $data = [];
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
    	return view('admin.customer.pricesLists',$data);
    }

    /* Return json of list prices */
    public function ajaxPricesList(Request $request)
    {
        $datatable = $request['datatable'];
        $pagination = $datatable['pagination'];
        $perPage = intval($pagination['perpage']);
        $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
        $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

        $sort = $datatable['sort'];
        $order['dir'] = $sort['sort'];
        $order['column'] = $sort['field'];

        if($order['dir'] == null){ $order['dir'] = 'asc';$order['column'] = 'prices_lists.id';} 

        $query =  isset($datatable['query']) ? $datatable['query'] : ["generalSearch" => null];
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";

        //Obtener datos dependiendo a los parametros
        $data = PricesLists::listado($perPage, $pages, $start, $search, $order);
        return response()->json($data);
    }

    public function agrgarLista(Request $request)
    {
        $priceList = PricesLists::updateOrCreate(['id' => $request->id],
        ['name' => $request->name]);

        $products = DB::select('select * from product');

        foreach($products as $item){
            $newProduct = new PricesListProducts;
            $newProduct->price_list_id = $priceList->id;
            $newProduct->product_id = $item->id;
            $newProduct->price = $item->regular_price;
            $newProduct->save();
        }
        session()->flash('flash_message',' se agrego correctamente'); //<--FLASH MESSAGE
        return redirect()->route('admin-prices-list');
    }

    public function deletePriceList(PricesLists $pricesList)
    {
        $name = $pricesList->deletePriceList();
        
        $PricesListProducts = PricesListProducts::where('price_list_id','=',$pricesList->id)->delete();
        //dd($PricesListProducts);
        //$PricesListProducts->deletePricesListProducts();
        session()->flash('flash_message',$name.' se elimino correctamente'); //<--FLASH MESSAGE
        return redirect()->route('admin-prices-list');
    }
    
    public function pricesListEdit(PricesLists $pricesList)
    {
        $data = [];

        $List = DB::table('prices_lists')
            ->where('price_list_id','=',$pricesList->id)
            ->join('prices_lists_products','prices_lists_products.price_list_id','=','prices_lists.id')->get();

        if(count($List) > 0){
            $data["id"] = $pricesList->id;
            $data["nameList"] = $pricesList->name;
            $data["List"] = $List;
            return view('admin.customer.priceslistsEdit',$data);
        }else{
            return redirect()->route('admin-prices-list');
        }
    }

    public function ajaxPriceListProducts(Request $request){
        $id = $request->id;
        $datatable = $request['datatable'];
        $pagination = $datatable['pagination'];
        $perPage = intval($pagination['perpage']);
        $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
        $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

        $sort = $datatable['sort'];
        $order['dir'] = $sort['sort'];
        $order['column'] = $sort['field'];

        if($order['dir'] == null){ $order['dir'] = 'asc';$order['column'] = 'prices_lists_products.id';} 

        $query =  isset($datatable['query']) ? $datatable['query'] : ["generalSearch" => null];
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";

        //Obtener datos dependiendo a los parametros
        $data = PricesListProducts::listado($perPage, $pages, $start, $search, $order, $id);
        return response()->json($data);
    }

    public function ajaxPriceListProductsEdit(Request $request){
        /*$id = $request->id;
        $value = $request->value;

        $pricesListProduct = PricesListProducts::find($id);
        if($pricesListProduct != null){ 
            $pricesListProduct->update(['price' => $value]);
        }*/
        $idList = $request->idList;
        $nameList = $request->nameList;
        $data = json_decode($request->data, true);
        
        $pricesList = PricesLists::find($idList);
        if($pricesList != null){ 
            $pricesList->update(['name' => $nameList]);
        }

        foreach($data as $item){
            $pricesListProduct = PricesListProducts::find($item['id']);
            if($pricesListProduct != null){ 
                $pricesListProduct->update(['price' => $item['price']]);
            }
        }

        session()->flash('flash_message',' Se guardo Lista de precios correctamente'); //<--FLASH MESSAGE
        //return redirect()->route('admin-prices-list');
    }

    //GCUAMEA
    public function save(Request $request){
        
        $customer = Customer::find($request->customer_id);

        $customer->update(['price_list_id' => $request->price_list_id]);
        $customer->update(['credit' => $request->credit]);
        $customer->update(['credit_days' => $request->credit_days]);
        $customer->update(['credit_status' => $request->credit_status]);
        session()->flash('flash_message',' Se guardo Cliente correctamente'); //<--FLASH MESSAGE
        return redirect()->route('admin-customer-detail',$customer);
    }
}