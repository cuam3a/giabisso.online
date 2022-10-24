<?php

namespace App\Models;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Share;

class Product extends Model
{
    protected $table = "product";
    protected $fillable = [
        'name', 'sku', 'description', 'specifications','category_id','brand_id','regular_price',
        'offer_price','final_price','stock','image','image_name','image_type','status','slug',
        'product_number','offer_date_start','offer_date_end','liquidado','liquidado_price'
    ];
    protected $dates = ['offer_date_start','offer_date_end'];

    public static $status = [
        0 => 'Inactivo',
        1 => 'Activo'
    ];

    public static $statusWP = [
        "Inactivo" => 0,
        "Activo" => 1
    ];

    public static $status_text = [//Texto que se mostrara a cliente
        'inactivo' => 0,//valor default en bd
        'activo' => 1
    ];

    public static $image_type = [
        'local',
        'url'
    ];

    public static $statusMsg = [
        'Habilitar',
        'Deshabilitar'
    ];

    public static $image_type_text = [
        'local' => 0,
        'url' => 1
    ];

    protected $appends = [
        'offer_price',
        'regular_price',
        'status_msg'
    ];

    public static $social_media = [
        'facebook' => ['label' => 'Facebook', 'icon' => 'facebook', 'target' => true],
        'twitter' => ['label' => 'Twitter', 'icon' => 'twitter', 'target' => true],
        'pinterest' => ['label' => 'Pinterest', 'icon' => 'pinterest', 'target' => false],
        'email' => ['label' => 'Correo', 'icon' => 'envelope', 'target' => false],
    ];
            
    public static $filterOrderBy = [//'columna-orden'
        'name-asc' => 'Nombre (ascendente)',//final_price* se refiere al valor append que compara precio regular y de oferta
        'name-desc'=> 'Nombre (descendente)',
        'final_price-asc' => 'Menor precio',
        'final_price-desc' => 'Mayor precio'
    ];

    public function breadcrumb(){
        $cat = $this->category;
        $breadcrumb = [];
        while($cat){
            array_unshift($breadcrumb,$cat);
            $cat = $cat->parent_category;
        }
        return $breadcrumb;
    }

    public function brand(){
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function favorites(){
        return $this->hasMany('App\Models\Favorite', 'product_id');
    }

    public function views(){
        return $this->hasMany('App\Models\ProductViews', 'product_id');
    }
    
    public function images(){
        return $this->hasMany('App\Models\Image', 'product_id')->orderBy('order');
    }

    public function questionsAnswers(){
        return $this->hasMany(QuestionAnswer::class, 'product_id','id');
    }

    public function questionsAnswersActivas(){
        return $this->questionsAnswers()->where("status",">",0)->orderBy("id","desc");
    }

    public function questionsPending(){
        return $this->questionsAnswers()->where("status","=",2);
    }

    public function reviews(){    /*trabajando...*/
        return $this->hasMany('App\Models\Review', 'product_id');
    }

    public function myReview(){
        return $this->reviews()->where("customer_id", Auth::guard('customer-web')->user()->id); 
    }

    public function images_preview(){
        $config = [];
        foreach($this->images as $index => $img){
            $config[$index]['caption'] = $img->name;
            $config[$index]['url'] = route('admin-product-remove-image');             
            $config[$index]['key'] = $img->id;
            $config[$index]['size'] = $img->size;
        }
        return $config;
    }

    public function getStatusTextAttribute(){   
        return self::$status[$this->attributes['status']];
    }
    
    public function getStatusMsgAttribute($value){
        if($value == 0 || $value == null) return self::$statusMsg[0];
        return self::$statusMsg[$value];
    }

    public function getOfferPriceAttribute($value) {
        if($value == 0 || $value == null){
            return 0;
        }
        return number_format($value, 2, '.', ',');
    }

    public function setSlugAttribute($value) {
        $this->attributes['slug'] = str_slug($this->name);
    }

    public function getRegularPriceAttribute($value) {
        if($value == 0 || $value == null){
            return 0;
        }
        return number_format($value, 2, '.', ',');
    }
    
    public function changeStatus(){
        $this->status =  !$this->status;
        $this->save();
        return true;
    }
    public function myfavorite(){
        $logged = false;
        if(Auth::guard('customer-web')->check() && Auth::guard('customer-web')->user()->favorites->whereIn('product_id',$this->id)->first()){
            $logged = true;
        }
        return $logged;
    }

    public function deleteProduct(){
        $name = $this->sku.' - '.$this->name;
        $this->delete();
        return $name;
    }

    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order, $category, $status, $subcategory, $brand = 0, $searchByProductNumber = false)
    {
        $query = $query->leftJoin('category as c', 'c.id', '=', 'product.category_id');
        $query = $query->leftJoin('category as cp', 'cp.id', '=', 'c.parent_id');

        if($category != '0' && $subcategory == '0'){ 
            $query = $query->where(function($query) use ($category){
                $query->where('c.id', $category)->Orwhere('c.parent_id', $category);
            });              
        } 
        
        if($subcategory != '0'){
            $query = $query->where(function($query) use ($subcategory){
                $query->where('c.id', $subcategory);
            });              
        }

        if($status != ''){
            $query = $query->where('product.status', $status);
        }
        
        if( $search != "") 
        {
            if($searchByProductNumber == 'true'){
                $query = $query->where(function($query) use ($search){
                    $query->where('product.product_number', 'like', '%'. $search .'%');
                });
            }
            else{
                $query = $query->where(function($query) use ($search){
                    $query->where('product.name', 'like', '%'. $search .'%')
                            ->orWhere('product.sku', 'like', '%'. $search .'%')
                            ->orWhere('product.description', 'like', '%'. $search .'%');
                });
            }
   
        }

        if($brand != 0){
            $query = $query->where('product.brand_id', $brand);
        }
         
        $count = $query->count(); // count total rows
        
        if( $order ){
            $query = $query->orderBy($order['column'], $order['dir']);
        }
         $counting = clone $query;
         $counting1 = clone $query;
         $actives = $counting->where('product.status', self::$status_text['activo'])->count();
         $inactives = $counting1->where('product.status', self::$status_text['inactivo'])->count();

        $page=0;
        if( $perPage != 0 ){
            $query = $query->skip(($start-1)*$perPage)->take($perPage);
            $page = ($start-1)+$perPage/$perPage;
        }

        $query = $query->select('product.id',
                                'product.image',
                                'product.image_type',
                                'product.image_name',
                                'product.product_number',
                                'product.name',
                                'product.sku',
                                'product.status',
                                'product.description',
                                'product.specifications',
                                'product.regular_price as regular_price_s',
                                'product.offer_price as offer_price_s',
                                'product.offer_date_start',
                                'product.offer_date_end',
                                'brands.name as brand',
                                DB::raw('CASE 
                                    WHEN c.parent_id is NULL THEN 
                                        CASE WHEN c.name IS NULL THEN
                                            "N/A"
                                        ELSE
                                            c.name
                                        END
                                    ELSE CONCAT(cp.name," / ",c.name)
                                END as category'),
                                'product.final_price',
                                'product.liquidado',
                                'product.liquidado_price',
                                'product.stock')->leftJoin('brands', 'product.brand_id', '=', 'brands.id')
                                ->get();
        
        $meta['field'] = $order['column'];
        $meta['start'] = $start;
        $meta['page'] = round($page);
        $meta['pages'] = $pages;
        $meta['perpage'] = $perPage;
        $meta['total'] = $count;
        $productList = $query->pluck('id');
        
        $priceLists = DB::table('prices_lists_products')
                                ->select('prices_lists.name','prices_lists_products.id','prices_lists_products.price_list_id','prices_lists_products.price','prices_lists_products.product_id')
                                ->whereIn('product_id',$productList)
                                ->join('prices_lists','prices_lists_products.price_list_id','=','prices_lists.id')->get();
        
        $results = [
            'meta' => $meta,
            'data' => $query,
            'actives' => $actives,
            'inactives' => $inactives,
            'priceLists' => $priceLists,
            
        ];
        return $results;
    }

    public function offer_startDate(){    
        if($this->offer_date_start == null) return Carbon::now()->format('d/m/Y H:i');
        return $this->offer_date_start->format('d/m/Y H:i');   
    }

    public function offer_endDate(){     
        if($this->offer_date_end == null) return Carbon::now()->format('d/m/Y H:i');
        return $this->offer_date_end->format('d/m/Y H:i');   
    }

    public function main_image() {    
        if($this->image_type == self::$image_type_text['local']) return $this->image;
        return $this->image;   
    }

    public static function scopefilterSearch($query, $slug, $child, $filters) {    
        //CATEGORIAS
        // $byCategory = $slug;//categoria
        
        // if($child!=null){//si se especifica subcategoria
        //     $byCategory = $child;
        // }

        if($slug){
            $cat = Category::where('slug',$slug)->where('parent_id', null)->firstOrFail();//si no encuentra pagina 404
            // Get only products with child category
            if($child){
                $category_children = Category::where('slug',$child)->where('parent_id','<>',null)->where('parent_id', $cat->id)->pluck('id');
            // Get all products from parent category and all children categories
            }else{
                $category_children = Category::where('parent_id',$cat->id)->pluck('id');
                $category_children = $category_children->push($cat->id);
            }
                
            //productos de categoria y subcategorias
            if(!$filters->has('s')){
                $query = $query->whereIn('category_id',$category_children);
            }   
        }
        //Parametros GET
        if($filters->has('s')){//buscador
            // Full query searching
            $query = $query->where('name','like',"%$filters->s%");
            $query = $query->where('stock','>', 0);
            $query = $query->where('status','=', 1);
            // Word searching
            // $keywords = explode(' ',$filters->s);
            // $query = $query->where(function($query) use($keywords) {
            //     foreach($keywords as $s) {
            //         $query = $query->orWhere('name','like',"%$s%");
            //     }
            // });
        }
        //Precios minimo-máximo
        if(($filters->has('minprice') && is_numeric($filters->minprice)) || ($filters->has('maxprice') && is_numeric($filters->maxprice))){ 
            
            $min = $filters->has('minprice') ? floatval($filters->minprice) : 0;
            if($min>0) $query = $query->where('final_price','>=',$min);
           
            $max = $filters->has('maxprice') ? floatval($filters->maxprice) : 0;
            if($max>0) $query = $query->where('final_price','<',$max);
        }
        //Ordenamiento
        if($filters->has('orderby') && array_key_exists($filters->orderby, self::$filterOrderBy)){
            $filterBy = explode('-',$filters->orderby);
            $query = $query->orderBy($filterBy[0],$filterBy[1]);
        }else{
            $query = $query->orderBy('name','asc');
        }

        $query = $query->where('status', 1);
        $query = $query->where('stock','>', 0);
        
        return $query->paginate(12);
    }


   /* public function shareLinks()
    {
        $view = View::make('share.facebook');
        dd($view);
        View::shouldReceive('make')
            ->with('social-share::default', [
                'service' => [ 'uri' => route('product-detail', [$this->id, $this->slug]), 'extra' => [
                    'extra1' => 'Extra 1',
                    'extra2' => 'Extra 2',
                ]],
                'url' => route('product-detail', [$this->id, $this->slug]),
                'title' => $this->name,
                'media' => '$this->main_image()',
                'sep' => '&'
            ])
            ->andReturn($view);
        Share::load(route('product-detail', [$this->id, $this->slug]))->service2();
    }*/

    public function shareLinks(){
        $shares = Share::load(route('product-detail', [$this->id, $this->slug]))->services('facebook','twitter','pinterest','email');

        $social = self::$social_media;
        foreach($social as $name => $share){
            $social[$name]['url'] = $shares[$name];
        }
        return $social;
    }

    public function getOfferPercent(){
        $offer_price = str_replace(",","",$this->offer_price);
        $regular_price = str_replace(",","",$this->regular_price);
        

        if ($this->offer > 0){
            if($offer_price > 0){
                return 100 - ceil(($offer_price * 100) / $regular_price);
            }
        }
        return 0;
    }

    public static function productosParaBaja(){
        /*Product::where("offer",1)->update(["offer" => 0, "final_price" => $this->regular_price]);
        return Product::where("offer_price",">",0)->where("offer_date_start","<=",date("Y-m-d H:i:s"))
        ->where("offer_date_end",">",date("Y-m-d H:i:s"))->update(['offer' => 1,"offer_price" => $this->regular_price]);*/

        $productos = Product::where("offer",1)->get();

        foreach ( $productos as $producto ) {
            $producto->offer       = 0;
            $producto->final_price = $producto->regular_price;
            $producto->save();
        }

        $productos =  Product::where("offer_price",">",0)->where("offer_date_start","<=",date("Y-m-d H:i:s"))
        ->where("offer_date_end",">",date("Y-m-d H:i:s"))->get();

        foreach ( $productos as $producto ) {
            $producto->offer       = 1;
            $producto->final_price = $producto->offer_price;
            $producto->save();
        }
    }


    public static function valoracion($id){
        $r = DB::select("SELECT 
            CASE WHEN rating IS NULL THEN 0 ELSE SUM(rating) / COUNT(*) END AS valoracion
            FROM reviews WHERE product_id = ? ",[$id]);
        return $r[0]->valoracion;
    }

    public static function scopeliquidacion($query, $slug, $child, $filters) {    
        //CATEGORIAS
        // $byCategory = $slug;//categoria
        
        // if($child!=null){//si se especifica subcategoria
        //     $byCategory = $child;
        // }

        /*if($slug){
            $cat = Category::where('slug',$slug)->where('parent_id', null)->firstOrFail();//si no encuentra pagina 404
            // Get only products with child category
            if($child){
                $category_children = Category::where('slug',$child)->where('parent_id','<>',null)->where('parent_id', $cat->id)->pluck('id');
            // Get all products from parent category and all children categories
            }else{
                $category_children = Category::where('parent_id',$cat->id)->pluck('id');
                $category_children = $category_children->push($cat->id);
            }
                
            //productos de categoria y subcategorias
            /*if(!$filters->has('s')){
                $query = $query->whereIn('category_id',$category_children);
            }   
    }*/
        //Parametros GET
        /*if($filters->has('s')){//buscador
            // Full query searching
            $query = $query->where('name','like',"%$filters->s%");
            $query = $query->where('stock','>', 0);
            $query = $query->where('status','=', 1);
            // Word searching
            // $keywords = explode(' ',$filters->s);
            // $query = $query->where(function($query) use($keywords) {
            //     foreach($keywords as $s) {
            //         $query = $query->orWhere('name','like',"%$s%");
            //     }
            // });
        }*/
        //Precios minimo-máximo
        /*if(($filters->has('minprice') && is_numeric($filters->minprice)) || ($filters->has('maxprice') && is_numeric($filters->maxprice))){ 
            
            $min = $filters->has('minprice') ? floatval($filters->minprice) : 0;
            if($min>0) $query = $query->where('final_price','>=',$min);
           
            $max = $filters->has('maxprice') ? floatval($filters->maxprice) : 0;
            if($max>0) $query = $query->where('final_price','<',$max);
        }
        //Ordenamiento
        if($filters->has('orderby') && array_key_exists($filters->orderby, self::$filterOrderBy)){
            $filterBy = explode('-',$filters->orderby);
            $query = $query->orderBy($filterBy[0],$filterBy[1]);
        }else{
            $query = $query->orderBy('name','asc');
        }*/

        $query = $query->where('status', 1);
        $query = $query->where('liquidado', 1);
        if($slug != null){
            $query = $query->where('category_id','=',$slug);
        }
        //$query = $query->where('stock','>', 0);
        
        return $query->paginate(12);
    }
}
