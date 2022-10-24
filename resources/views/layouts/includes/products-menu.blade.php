
@php($obj_category = new App\Models\Category)
<div class="products-menu">
    <div class="section categories categories-mob desktop">
        <div class="title">Categorías</div>
        <div class="content">
            <ul class="list-unstyled">
                    <li><a href="{{route('category-products',['slug' => ''])}}" @if(!isset($slug)) class="active" @endif>Todas</a>
                @foreach($categories as $category)
                    @php($subcategories = $obj_category->subCategoriesWithProductsAndStock($category->id))
                    <li><a href="{{route('category-products',['slug' => $category->slug])}}" @if(isset($slug) && $slug == $category->slug) class="active" style="display:inline-block; width:auto" @endif>{{$category->name}} @if(count($subcategories) > 0 && $category->slug != $slug) <i class="la la-angle-right"></i> @else <a href="" style="display:inline-block; width:auto; float:right" class="active toggle-list-product"><i class="la la-angle-right"></i></a> @endif</a>
                        <ul class="submenu list-unstyled" @if($category->slug == $slug) style="display:block" @else style="display:none" @endif>
                            @foreach($subcategories as $subcategory)
                                <li><a href="{{route('category-products',['slug' => $category->slug,'child' => $subcategory->slug])}}" @if(isset($child) && $child == $subcategory->slug) class="active" @endif>{{$subcategory->name}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="section prices d-none d-sm-block">
        <div class="title">Precio</div>
        <div class="content">
            <div class="form-row">                                
                <div class="form-group col-md-6">
                    <label for="min-price">Desde</label>
                    <input class="form-control" name="minprice" type="number" min="0" value="{{@$_GET['minprice']}}">
                </div>
                <div class="form-group col-md-6">
                    <label for="max-price" >Hasta</label>
                    <input  class="form-control" name="maxprice" type="number" min="0" value="{{@$_GET['maxprice']}}">
                </div>
            </div>
        </div>
    </div>

    <!--
    <div class="section prices">
        <div class="title">Precio</div>
        <div class="content">
            <ul class="list-unstyled">
                <li>
                    <label class="check_container">$100 - $200
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </li>
                <li>
                    <label class="check_container">$250 - $300
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </li>
                <li>
                    <label class="check_container">$350 - $500
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </li>
            </ul>
        </div>
    </div>-->
    <!--<div class="section">
        <div class="title">Descuento</div>
        <div class="content">
            <ul class="list-unstyled">
                 <li>
                    <label class="container"><b>10%</b> de descuento o más
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </li>
                <li>
                    <label class="container"><b>25%</b> de descuento o más
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </li>
                <li>
                    <label class="container"><b>50%</b> de descuento o más
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </li>
                <li>
                    <label class="container"><b>75%</b> de descuento o más
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </li>
            </ul>
        </div>
     </div>-->
    <div class="desktop">
        <button id="btnFilter" class="btnFilter btn btn-block btn-primary d-flex justify-content-between"><span>Filtrar</span><span class="fa fa-search" style="margin-top:5px;"></span></button>
    </div>
    
</div>

