<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Excel;
use DB;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\ShipmentTypes;
use App\Models\Product;
use App\Models\PricesListProducts;//GCUAMEA
use App\Models\PricesLists;//GCUAMEA
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Validator;

    /*nrl*/
use App\Models\QuestionAnswer;
use App\Models\Review;
use App\Mail\QuestionsResponse;
use Illuminate\Support\Facades\Mail;
    /*end nrl*/

use File;

class ProductController extends Controller
{
	public function __construct(){
		$this->middleware('auth:admin-web');
	}
    
    //List of products
    public function listProducts()
    {
        $brands = new Brand();
        $data = [];
        $data['status'] = Product::$status;
        $data['imageType'] = Product::$image_type;
        $data['imageTypeText'] = Product::$image_type_text;
        $data['categories'] = Category::parents();
        $data['brands'] = $brands->getBrandsInCategory();
    	return view('admin.product.list',$data);
    }

	//Product Detail
    public function detail(Product $product){
        $data                        = [];
        $data['status']              = Product::$status;
        $data['imageType']           = Product::$image_type;
        $data['categories']          = Category::where('parent_id',null)->pluck('name','id');
        $data['product']             = $product;
        $data['shipment_types']      = ShipmentTypes::all();
        $data['shipment_types_json'] = ShipmentTypes::all()->toJson();
        $data['saved_images']        = json_encode($product->images->pluck('id'));
        $data['pricesLists']          = PricesLists::pluck('name','id');//GCUAMEA
        return view('admin.product.detail',$data);
    }

    //Method that duplicate product
    public function duplicate(Product $product){
        $dirProductsUploads='/uploads/products/';
        if($product->id){
            $newProduct=$product->replicate();
            $newProduct->sku='';
            $newProduct->product_number=null;
            $newProduct->status=Product::$status_text['inactivo'];
            if($product->image!=''){
                $fileInfo = pathinfo(storage_path().$product->image);
                $uniqueFileName = uniqid().File::extension($fileInfo['filename']);                
                $imageSuccess=File::copy(public_path().$product->image,public_path().$dirProductsUploads.$uniqueFileName.'.'.$fileInfo['extension']);
                if($imageSuccess){
                    $newProduct->image=$dirProductsUploads.$uniqueFileName.'.'.$fileInfo['extension'];
                }
            }
            $newProduct->save();

            foreach($product->images()->get() as $img){
                $newImg=$img->replicate();
                $newImg->id=null;
                $newImg->product_id=$newProduct->id;

                $fileImg = pathinfo(storage_path().$img->path);
                $uniqueImgName = uniqid().File::extension($fileInfo['filename']);                
                $imgSuccess=File::copy(public_path().$img->path,public_path().$dirProductsUploads.$uniqueImgName.'.'.$fileInfo['extension']);
                if($imgSuccess){
                    $newImg->path=$dirProductsUploads.$uniqueImgName.'.'.$fileInfo['extension'];
                }
                $newImg->save();
            }
            return redirect()->route('admin-product-edit',$newProduct->id);
        }
    }
    //Method that save changes or a new product
    public function save(Request $request,Product $product){

        $priceR = $request->regular_price;
        if($request->regular_price != ''){
            $request->merge(['regular_price' => str_replace(",", "", $request->regular_price)]);
        }

        if($request->offer_price != ''){
            $request->merge(['offer_price' => str_replace(",", "", $request->offer_price)]);
        }
        else{
            $request->offer_price = 0;    
        }
            

        $data = $request->all();{ //validaciones
            $validator = Validator::make($data, [
                'name'             => 'required',
                'sku'              => 'required',
                'category_id'      => 'required',
                'regular_price'    => 'required|numeric',
                'stock'            => 'required|numeric',
                'image'            => 'max:2048',
                'regular_price'    => 'required|numeric',
                'offer_price'      => 'nullable|numeric',
                'offer_date_start' => 'required_if:offer_price,>,1',
                'offer_date_end'   => 'required_if:offer_price,>,1',
                'width'            => 'required_with:height|required_with:length|required_with:weight',
                'height'           => 'required_with:width|required_with:length|required_with:weight',
                'length'           => 'required_with:width|required_with:height|required_with:weight',
                'weight'           => 'required_with:width|required_with:height|required_with:length'],
                [   
                    'image.max' => 'El tamaño de la imagen es mayor a 2mb, intenta con otra.',
                ]);
            $validator->validate();
        }

        $offer = 0; /*trabajando...*/
        if(  isset($request->offer_date_start) && isset($request->offer_date_end) ){
            $hoy          = date('Y-m-d H:i:s');            
            $fecha_inicio =  Carbon::createFromFormat('d/m/Y H:i', $request->offer_date_start)->format('Y-m-d H:i:s');
            $fecha_final  =  Carbon::createFromFormat('d/m/Y H:i', $request->offer_date_end)->format('Y-m-d H:i:s');
                if ($hoy >= $fecha_inicio && $hoy <= $fecha_final)
                    $offer = 1 ;
        }

        $product->name             = $request->name;
        $product->sku              = $request->sku;
        $product->product_number   = $request->product_number;
        $product->slug             = $product->name;//valor default se seteara nuevo valor en el modelo 
        $product->category_id      = isset($request->subcategory) ? $request->subcategory : $request->category_id;
        $product->regular_price    = $request->regular_price;
        $product->offer_price      = $request->offer_price;
        $product->final_price      = ($offer > 0) ? $request->offer_price : $request->regular_price;
        $product->stock            = $request->stock;
        $product->description      = $request->description;
        $product->specifications   = $request->specifications; 
        $product->offer_date_start = !isset($request->offer_date_start) ? null : Carbon::createFromFormat('d/m/Y H:i', $request->offer_date_start)->format('Y-m-d H:i:s');
        $product->offer_date_end   = !isset($request->offer_date_end) ? null : Carbon::createFromFormat('d/m/Y H:i', $request->offer_date_end)->format('Y-m-d H:i:s');
        
        $product->offer            = $offer;
        $product->width            = $request->width;
        $product->height           = $request->height;
        $product->length           = $request->length;
        $product->weight           = $request->weight;
        $product->dimension_unit   = $request->length_unit;
        $product->weight_unit      = $request->weight_unit;


        $product->shipment_id = $request->shipment_id;
            
        if(!$request->shipment_cost && $request->shipment_id){
            $shipment = ShipmentTypes::where('id',$request->shipment_id)->first();
            $product->shipment_cost = $shipment->cost;
        }
        else{
            $product->shipment_cost = $request->shipment_cost;
        }
        
    
        if($request->hasFile('image')) {
            if($product->image) Storage::disk('public_uploads')->delete($product->image);  
            $product->image = '/uploads/'.$request->file('image')->store('products','public_uploads');            
            $product->image_name = $request->file('image')->getClientOriginalName();
            $product->image_type = Product::$image_type_text['local'];
        }
        if($request->has('brand')) {
            $brand = Brand::find($request->brand);//si no encuentro marca, se añade nueva
            if(!$brand){
                $brand = new Brand();
                $brand->name = $request->brand;
                $brand->save();
            }
            $product->brand()->associate($brand);
        }
        //Liquidacion
        if($request->liquidado){
            $product->liquidado = 1;
        }else{
            $product->liquidado = 0;
        }
        $product->liquidado_price = $request->liquidado_price;
        //dd($product);
        $product->save();

        $images = json_decode($request->saved_images, TRUE);//se obtienen ids y se le asigna producto id
        Image::whereIn('id', $images)->update(['product_id' => $product->id]);

        //AGREGAR A LISTA DE PRECIOS GCUAMEA
        $listPrice = PricesLists::get();
        foreach($listPrice as $item){
            //dd("ENTRO");
            $exist = PricesListProducts::where("product_id",'=', $product->id)->where("price_list_id",'=', $item->id)->get();
            if ($exist->isEmpty()) {
                $newProduct = new PricesListProducts;
                $newProduct->price_list_id = $item->id;
                $newProduct->product_id = $product->id;
                $newProduct->price = $request->regular_price;
                $newProduct->save();
            }
        }

        
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Producto guardado!'); //<--FLASH MESSAGE
        session()->flash('flash_message',$product->name); //<--FLASH MESSAGE

        // Redirects
        if($request->option == 'update'){
            return redirect()->route('admin-product-edit', ['product' => $product->id]);
        }else if($request->option == 'update_exit'){
            return redirect()->route('admin-product-list');
        }
    	
    }
    
    //Method that save changes or a new product
    public function addImage(Request $request)
    {  
        $images = [];
        $product = null;
        $index = 0;
        if($request->has('id')){
            $product = Product::find($request->id);
            $index = $product->images()->max('order');
        }
        if($request->has('saved_images')){
            $images = json_decode($request->saved_images,true);
        }
        if($request->hasFile('images')){
            foreach($request->file('images') as $reqimage){
                $image = new Image();
                if($reqimage->getClientSize() > 1024 * 1024 * 2) {
                    return response()->json(array("error" => "El tamaño de la imagen es mayor a 2mb, intenta con otra."));
                }
                if($product){ $image->product_id = $request->id; }
                $image->name = $reqimage->getClientOriginalName();
                $image->path = '/uploads/'.$reqimage->store('/products','public_uploads');
                $image->type = Product::$image_type_text['local'];
                $image->size = $reqimage->getClientSize();
                $image->order = $index++;
                $image->save();
                $images[] = $image->id;            
            }
        }
        if($product != null){ 
            $images = $product->images->pluck('id');
        }
        return response()->json($images);
    }
    //Method that save changes or a new product
    public function removeImage(Request $request)
    {          
        $images = [];
        if($request->has('saved_images')){
            $images = json_decode($request->saved_images,true);
        }
        if($request->has('key')){
            $img = Image::find($request->key);
            if($img !== null && file_exists(public_path().$img->path)){
                unlink(public_path().$img->path);
                unset($images[$img->id]);
                $img->delete();
            }
        }
        $product = Product::find($request->id);
        if($product != null){ 
            $images = $product->images->pluck('id');
        }
        return response()->json($images);
    }

    //Method that saves images order
    public function sortImages(Request $request)
    {  
        if($request->has('sortItems')){
            foreach($request->sortItems as $index => $item){
                $img = Image::find($item['key']);
                if($img != null){                    
                    $img->order = $index;
                    $img->save();
                }
            }
        }

        $images = [];
        $product = Product::find($request->id);
        if($product != null){ 
            $images = $product->images->pluck('id');
        }
        return response()->json($images);
    }

    /* Return json of products */
    public function ajaxProducts(Request $request)
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
        $category = array_key_exists('Category',$query) ? $query['Category']: "0";
        $subcategory = array_key_exists('Subcategory',$query) ? $query['Subcategory']: "0";
        $status = array_key_exists('Status',$query) ? $query['Status']: "";
        $brand = array_key_exists('Brand',$query) ? $query['Brand']: "";
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";
        $searchByProductNumber = array_key_exists('searchByProductNumber',$query) ? $query['searchByProductNumber'] : "";

        //Obtener datos dependiendo a los parametros
        $data = Product::listado($perPage, $pages, $start, $search, $order, $category, $status, $subcategory, $brand, $searchByProductNumber);

        return response()->json($data);
    }

    //Delete product
    public function deleteProduct(Product $product)
    {   
        $name = $product->deleteProduct();
        PricesListProducts::where('price_list_id','=',$product->id)->delete();//GCUAMEA
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Producto eliminado!'); //<--FLASH MESSAGE
        session()->flash('flash_message',$name.' se eliminó correctamente'); //<--FLASH MESSAGE
    	return redirect()->route('admin-product-list');
    }

    //Change status to product
    public function changeStatus(Product $product)
    {   
        $product->changeStatus();
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Producto actualizado!'); //<--FLASH MESSAGE
        session()->flash('flash_message',$product->sku.' '.$product->name.' cambio de estatus'); //<--FLASH MESSAGE
    	return redirect()->route('admin-product-edit', ['product' => $product->id]);
    }

    public function importerView()
    {
    	return view('admin.product.importer');
    }
    
    public function importProducts(Request $request)
    {   
        \Config::set('excel.import.startRow', 2);
        $excel_mimetypes = array(
            'application/vnd.ms-office',
            'application/vnd.ms-excel', // xls
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
        );
        if(!in_array($request->File('excelFile')->getMimeType(), $excel_mimetypes)) {//es de tipo Excel?
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Archivo incorrecto'); //<--FLASH MESSAGE
            session()->flash('flash_message','Tipo de archivo incompatible'); //<--FLASH MESSAGE
            return redirect()->back();
        }
        $results = Excel::load($request->File('excelFile'), function($reader) { })->get();//se obtienen datos de excel
        //dd($results);   
        $results = collect($results)->filter(function($item) {
                return $item->nombre!= null;
            });
            $grupos = $results->mapToGroups(function ($item, $key) {
                // When category is 'Parent/Child'
                    $arr_category = explode(' / ',$item['categoriasubcategoria']);

                    $item['category_id'] = '';
                    // Insert or get Parent category
                    $item['brand_id'] = null;
                    if($item['marca'] != null){

                        $brand = Brand::whereRaw('LOWER(name) = ?', [mb_strtolower($item['marca'])])->first();//se busca categoria
                        if(!$brand){
                            $brand = new Brand;
                            $brand->name = $item['marca'];
                            $brand->save();
                        }
                        $item['marca'] = $brand->id;
                    }

                    // Insert or get Parent category
                    if(count($arr_category) > 0){
                        // $arr_category[0] = utf8_decode($arr_category[0]);
                        $category = Category::whereRaw('LOWER(name) = ?', [mb_strtolower($arr_category[0])])->first();//se busca categoria
                        
                        if(!$category){
                            $category = new Category;
                            $category->icon = 'la la-bars';
                            $category->name = $arr_category[0];
                            $category->slug = str_slug($arr_category[0]);
                            $category->save();
                        }
                        $item['category_id'] = $category->id;
                    }
                    
                    // Insert or get Child category
                    if(count($arr_category) == 2){
                        // $arr_category[1] = utf8_decode($arr_category[1]);
                        $subcategory = Category::whereRaw('LOWER(name) = ?', [mb_strtolower($arr_category[1])])->first();//se busca categoria
                        if(!$subcategory){
                            $subcategory = new Category;
                            $subcategory->icon = 'la la-bars';
                            $subcategory->name = $arr_category[1];
                            $subcategory->slug = str_slug($arr_category[1]);
                            $subcategory->parent_id = $category->id;
                            $subcategory->save();
                        }
                        $item['category_id'] = $subcategory->id;

                        //dd($item['categoriasubcategoria'],$arr_category,$category,$item['category_id'],$subcategory->parent_id );
                    }

                    // Group by last item on exploded string
                    if($item['inicio_promocion'] != null){
                        $item['inicio_promocion'] = gettype($item['inicio_promocion']) == 'string'  ? Carbon::createFromFormat('d/m/Y H:i', $item['inicio_promocion']) :  $item['inicio_promocion']->format('Y-m-d H:i:s');
                    } 
                    if($item['fin_promocion'] != null){
                        $item['fin_promocion'] = gettype($item['fin_promocion']) == 'string'  ? Carbon::createFromFormat('d/m/Y H:i', $item['fin_promocion']) :  $item['fin_promocion']->format('Y-m-d H:i:s');                       
                    }
                    $group_category = $arr_category[count($arr_category)-1];
                    return [$group_category //se agrupan productos por categoria
                                => ['name' => $item['nombre'],
                                    'sku' => trim($item['sku'],' '),
                                    'product_number' => $item['no._articulo'],
                                    'description' => $item['descripcion'],
                                    'specifications' => $item['especificaciones'],
                                    'brand_id' => $item['marca'],                             
                                    'regular_price' => (float)$item['precio_regular'],
                                    'offer_price' => (float)$item['precio_promocion'],   
                                    'final_price' => (float)$item['precio_promocion'] ? (float)$item['precio_promocion'] : (float)$item['precio_regular'],                              
                                    'offer_date_start' => $item['inicio_promocion'],
                                    'offer_date_end' => $item['fin_promocion'],   
                                    'stock' => (int)$item['inventario'],
                                    'slug' => str_slug($item['nombre']),                               
                                    //'imageurl' => explode("|", $item['imageurl'])[0],//primera imagen
                                    // 'image' => '/uploads/products/'.$item['imagefilename'],//primera imagen
                                    // 'image_name' => $item['imagefilename'],
                                    // 'image_type' => Product::$image_type_text['local'],
                                    'status' => $item['estatus'] ? Product::$statusWP[$item['estatus']] : Product::$statusWP['Activo'],
                                    'category_id' => $item['category_id']
                                    //'shippingclass' => $item['shippingclass'],                   
                                ]];
        });
        $counter = [];
        $counter['updated'] = $counter['new'] = $counter['error'] = 0;
        foreach($grupos as $category => $products){//por cada categoria
            foreach($products as $producto){//guardado de productos
                if($producto['sku'] != '' || $producto['sku'] != null){
                    $prod = Product::updateOrCreate(['sku' => $producto['sku']],$producto);
                    if($prod->wasRecentlyCreated) $counter['new']++;
                    else $counter['updated']++; //se actualizó
                }else{
                    $counter['error']++;//producto sin sku
                }
            }
        }
        try{
         Product::productosParaBaja();
        }catch(\Exception $e){
            \Log::error('Fallo funcion productos para baja '.err);
        }
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Listo'); //<--FLASH MESSAGE
        session()->flash('flash_message','Productos actualizados'); //<--FLASH MESSAGE

        session()->flash('counter_type','success'); //<--FLASH MESSAGE
        session()->flash('counter_message','Productos nuevos '.$counter['new'].'<br>'//<--FLASH MESSAGE
                                           .'Productos actualizados '.$counter['updated'].'<br>'
                                           .'Productos no importados '.$counter['error']); 
        return redirect()->back();
    }

    public function getBrands(Request $request)
    {
        $perPage = 5;
        $start = $request->page;       
        $search = $request->has('search') ? $request->search : '';
        $brands = Brand::select(['id', 'name'])->where('name','like','%'.$search.'%')->orderBy('name');
        
        $brands = $brands->skip(($start-1)*$perPage)->take($perPage);
        $data['total_count'] = $brands->count();
        $data['results'] = $brands->get();
        return response()->json($data);
    }

    public function getImageFromUrl($url)
    {   
        if($url == ''){
            return '';
        }
        
        $contents = @file_get_contents($url);
        $headers = get_headers($url);
        if (substr($headers[0], 9, 3) == "200") { 
            $name = substr($url, strrpos($url, '/') + 1);
            $file = Storage::disk('public_uploads')->put('/products/'.$name, $contents);
            return $name;
        } else { 
            return '';
        }   
    }

    //export products
    public function exportProducts(){
        $status = Input::get('estatus');
        $category = Input::get('category');
        $subcategory = Input::get('subcategory');
        $data = Product::listado( 0, 0, 0, "", null ,$category, $status, $subcategory);

        return Excel::create('HEC-Productos', function($excel) use ( $data) {
            $excel->setCreator('Home Express Center')->setCompany('Home Express Center');
            $excel->sheet('Listado de productos', function($sheet) use ( $data) {
                $sheet->loadView('admin.product.export',[
                    'products' => $data["data"],
                    ]);
            });
        })->download('xls');
    }

    /*nrl*/
    public static function _offers(){
        Product::productosParaBaja();
        \Log::info("finished");
    }

    public function questionsAnswers(){ /*trabajando...*/
        $data['questions']     = QuestionAnswer::pending();
        $data['questions_all'] = QuestionAnswer::response();
        return view('admin.questions.questions',$data);        
    }

    public function saveAnswer(Request $request){
        $qst         = QuestionAnswer::find($request->id);
        $qst->answer = $request->answer;
        $qst->status = 1;
        $r           = $qst->save();
        if( $r > 0 ){

            $msg = ( $request->edition == 1 ) ? "Respuesta editada correctamente" : "Respuesta realizada con éxito" ;

            if($request->edition == 0)
                Mail::to($qst->email)->send(new QuestionsResponse($qst));
            

            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Preguntas y respuestas'); //<--FLASH MESSAGE
            session()->flash('flash_message', $msg); //<--FLASH MESSAGE
        }else{
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Preguntas y respuestas'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'La acción no pudo ser realizada'); //<--FLASH MESSAGE
        }
        return redirect()->route("admin-questions-answers");
    }

    public function trashQuestion($id){
        $r = QuestionAnswer::destroy($id);
        if( $r > 0 ){
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Preguntas y respuestas'); //<--FLASH MESSAGE
            session()->flash('flash_message', "pregunta eliminada correctamente"); //<--FLASH MESSAGE
        }else{
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Preguntas y respuestas'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'La pregunta no pudo ser eliminada'); //<--FLASH MESSAGE
        }
        return redirect()->route("admin-questions-answers");
    }

    public function ratings(){
        $data['ratings']     = Review::all();
        return view('admin.reviews.reviews',$data);         
    }

    public function changeRating($id,$status){
        $re = Review::find($id);
        $re->status = $status;
        $r = $re->save();

        $msg = ( $status == 1 ) ? "Comentario visible para los usuarios" : "Comentario oculto par los usuarios" ; 

        if( $r > 0 ){
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Valoración'); //<--FLASH MESSAGE
            session()->flash('flash_message', $msg); //<--FLASH MESSAGE
        }else{
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Valoración'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'No se pudo cambiar el estado del comentario'); //<--FLASH MESSAGE
        }
        return redirect()->route("admin-ratings");
    }

    //GCUAMEA
    public function ajaxGetPriceList(Request $request){
        $List = DB::table('prices_lists_products')
            ->select('prices_lists_products.id','prices_lists_products.product_id')
            ->where('product_id','=',$request->id)
            ->join('prices_lists','prices_lists_products.price_list_id','=','prices_lists.id')->get();
        //dd($List);
        return response()->json($List);
    }

    public function ajaxgetPrice(Request $request){
        $price = 0;
        $priceListItem = PricesListProducts::where("product_id","=",$request->product_id)->where("price_list_id","=",$request->price_list_id)->first();
        if($priceListItem != null){
            $price = $priceListItem->price;
        }
        return $price;
    }

    public function ajaxUpdatePriceList(Request $request){
        //dd($request);
        $priceListItem = PricesListProducts::where("product_id","=",$request->product_id)->where("price_list_id","=",$request->price_list_id)->first();
        if($priceListItem != null){
            $priceListItem->price = $request->price;
            $priceListItem->save();
        }
    }

    public function ajaxPriceUpdatePriceList(Request $request){
        //dd($request);
        $priceListItem = PricesListProducts::where("id","=",$request->id)->first();
        if($priceListItem != null){
            $priceListItem->price = $request->price;
            $priceListItem->save();
        }
    }
    
    public function ajaxLiquidacionStatus(Request $request){
        //dd($request);
        $product = Product::where("id","=",$request->id)->first();
        if($product != null){
            if($request->liquidado == "true"){
                $product->liquidado = 1;
            }else{
                $product->liquidado = 0;
            }
            $product->save();
        }
    }

    public function ajaxLiquidacionPrice(Request $request){
        //dd($request);
        $product = Product::where("id","=",$request->id)->first();
        if($product != null){
            $product->liquidado_price  = $request->liquidado_price;
            $product->save();
        }
    }
}