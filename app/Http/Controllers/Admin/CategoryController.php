<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Auth;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
	public function __construct(){
		$this->middleware('auth:admin-web');
    }
    
    //List of categories
    public function listCategories()
    {
        $data = [];
        $data['categories'] = Category::where('parent_id',null)->orderBy('order','ASC')->get();//solo categorias padre
        $data['categories_all'] = Category::categoriesAll();//todas categorias
    	return view('admin.category.list',$data);
    }

    public function saveCategory(Request $request)
    {   
        $category = Category::updateOrCreate(['id' => $request->id],
        ['name' => $request->name,
         'icon' => $request->icon,
         'parent_id' => $request->parent_id,
         'slug' => str_slug($request->name)]);
         
        $data = [];
        $data['id'] = $category->id; 
        $data['categories'] = Category::categoriesAll();
        $data['html'] = '';
        if($category->wasRecentlyCreated){//Nueva categoria o subcategoria
            $data['type'] = 'newSubcategory';
            $data['html'] = View::make('admin.category.chunkChild',['subcategory' => $category])->render();
            if($category->parent_id == null){
                $category->order = Category::max('order')+1;
                $category->save();
                $data['type'] = 'newCategory';
                $data['html'] = View::make('admin.category.chunkParent',['category' => $category])->render();
            }
        }else{
            $data['type'] = 'editSubcategory';
            if($category->parent_id == null) $data['type'] = 'editCategory';
        }
        return response()->json($data);
    }

    public function deleteCategory(Request $request)
    {   
        $category = Category::find($request->id);
        $category_id = $category->id;
        $category->delete();

        $data = [];
        $data['id'] = $category_id; 
        $data['categories'] = Category::categoriesAll();
        return response()->json($data);
    }

    public function saveCategoryOrderBy(Request $request){
        $categories = $request->categories;
        foreach($categories as $toUpdate){
            $category = Category::find($toUpdate['id']);
            $category->order = $toUpdate['order'];
            $category->save();
        }
        return response()->json(true);
    }
    
    public function getSubcategories(Request $request)
    {
        $subcategories = Category::where('parent_id',$request->category)->pluck('name','id');
    	return response()->json($subcategories);
    }
}
