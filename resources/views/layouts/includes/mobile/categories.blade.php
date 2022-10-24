@php($obj_category = new App\Models\Category)
@php($cat = $obj_category->categoriesWithProductsAndStock())
<div class="products-menu">
    <div id="categories-mobile" class="section categories mobile categories-mob ">
        <div class="title"><a class="btn btn-xs" id="btn-close-categories" onclick="closeCategories()"><i class="fa fa-times"></i></a>Categorías</div>
        <div class="content">
            <ul class="list-unstyled">
                @foreach($cat as $category)
                    @php($subcategories = $obj_category->subCategoriesWithProductsAndStock($category->id))
                    @php($extra = '')
                    @if(isset($slug) && $slug == $category->slug)
                        <li> <i class="{{$category->icon}}"></i> <a href="{{route('category-products',['slug' => $category->slug])}}" class="active" style="display:inline-block; width:auto">{{$category->name}} <a href="" style="display:inline-block; width:auto; float:right" class="active toggle-list-product"><i class="la la-angle-right"></i></a>
                    @else
                        <li> <i class="{{$category->icon}}"></i>  <a href="{{route('category-products',['slug' => $category->slug])}}" style="display:inline-block; width:auto">{{$category->name}} <a href="" style="display:inline-block; width:auto; float:right" class="active toggle-list-product"><i class="la la-angle-right"></i></a>
                    @endif

                    @if(isset($slug) && $slug == $category->slug)
                        <ul class="submenu list-unstyled">
                    @else
                        <ul class="submenu list-unstyled" style="display:none">
                    @endif
                            @foreach($subcategories as $subcategory)
                                <li> <a href="{{route('category-products',['slug' => $category->slug,'child' => $subcategory->slug])}}" @if(isset($child) && $child == $subcategory->slug) class="active" @endif>{{$subcategory->name}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<script>
    function myFunction() {
        document.getElementById("categories-mobile").style.visibility = "visible";
    }

    function closeCategories(){
        document.getElementById("categories-mobile").style.visibility = "hidden";
    }
</script>