<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Auth;
use Carbon\Carbon;
use App\Models\Carousel;
use App\Models\Product;

class CarouselController extends Controller
{
	public function __construct(){
		$this->middleware('auth:admin-web');
    }
    
    //List of carousels
    public function listCarousels()
    {
        $data = [];
        $data['carousels'] = Carousel::orderBy('order')->get();//todas categorias
        $data['carousels_all'] = collect(Carousel::get())->keyBy('id');//todas categorias
    	return view('admin.config.carousels',$data);
    }
    public function saveCarousel(Request $request)
    {   
        
        $carousel = Carousel::updateOrCreate(['id' => $request->id],['name' => $request->name]);
        $data = [];
        $data['id'] = $carousel->id; 
        $data['carousels'] = collect(Carousel::get())->keyBy('id');
        if($carousel->wasRecentlyCreated){//si es reciente se le asigna ultimo lugar
            $carousel->order = Carousel::max('order')+1;
            $carousel->save();
            $data['type'] = 'newCarousel';
        }else{
            $data['type'] = 'editCarousel';
        }         
        $data['html'] = View::make('admin.config.chunkCarousel',['carousel' => $carousel])->render();
        return response()->json($data);
    }

    public function deleteCarousel(Request $request)
    {   
        $data = [];
        $carousel = Carousel::find($request->id);
        if($carousel){            
            $carousel_id = $carousel->id;
            $carousel->delete();
            $data['id'] = $carousel_id; 
        }
        $data['carousels'] = collect(Carousel::get())->keyBy('id');
        return response()->json($data);
    }

    public function saveCarouselOrderBy(Request $request){
        $carousels = $request->carousels;
        foreach($carousels as $toUpdate){
            $carousel = Carousel::find($toUpdate['id']);
            $carousel->order = $toUpdate['order'];
            $carousel->save();
        }
        return response()->json(true);
    }

    public function carouselDetail(Carousel $carousel){
        $data['carousel'] = $carousel;
        $data['products'] = $carousel->products ? json_decode($carousel->products) : [];
        return view('admin.config.carousel-detail',$data);
    }

    public function getProducts(Request $request)
    {
        $perPage = 5;
        $start = $request->page;       
        $search = $request->has('search') ? $request->search : '';
        $products = Product::select(['id', 'name', 'image','sku'])->where('name','like','%'.$search.'%');
        $data['results'] = $products->get();        
        $products = $products->skip(($start-1)*$perPage)->take($perPage);
        $data['total_count'] = $products->count();
        return response()->json($data);
    }

    public function saveCarouselProducts(Request $request){
        if($request->has('carousel')){
            $carousel = Carousel::findOrFail($request->carousel);
            if($request->has('products')){
                $carousel->products = $request->products;
                $carousel->save();
                session()->flash('flash_type','success'); //<--FLASH MESSAGE
                session()->flash('flash_title','Listo'); //<--FLASH MESSAGE
                session()->flash('flash_message','Se actualizarón los productos de "'.$carousel->name.'"'); //<--FLASH MESSAGE
                return redirect()->route('admin-carousels-list');
            }   
        }
        session()->flash('flash_type','danger'); //<--FLASH MESSAGE
        session()->flash('flash_title','Error'); //<--FLASH MESSAGE
        session()->flash('flash_message','Ocurrio un error intente más tarde'); //<--FLASH MESSAGE
        return redirect()->back();
    }
}