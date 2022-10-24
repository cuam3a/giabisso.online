<div class="m-portlet m-portlet--head-solid-bg m-portlet--head-sm m-portlet--sortable m-portlet-category parent-category m-portlet--collapse" data-portlet="true" data-id="{{$category->id}}" data-name="{{$category->name}}">
    <div class="m-portlet__head ui-sortable-handle category">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="{{ $category->icon }}"></i>
                </span>
                <div class="divCategory"></div>
                <h3 class="m-portlet__head-text">
                    {{ $category->name }}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-primary void editCategory">
                        <i class="la la-edit"></i>
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-danger void deleteCategory">
                        <i class="la la-trash"></i>
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                    <a href="#" class="m-btn--pill btn btn-sm btn-default m-btn--wide btn-icon-align void addSubcategory"><i class="la la-plus-circle"></i> Crear subcategoría</a>
                </li>
                @if($category->subcategory->count() > 0)
                    <li class="m-portlet__nav-item">
                        <a href="#" data-portlet-tool="toggle" class="void m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="m-portlet__body children collapse">
        @foreach($category->subcategory as $subcategory)
            @include('admin.category.chunkChild')
        @endforeach
    </div>
</div>