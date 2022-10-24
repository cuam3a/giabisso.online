<div class="m-portlet--head-solid-bg m-portlet-category" data-portlet="false" data-parent="{{$subcategory->parent_id}}" data-id="{{$subcategory->id}}" data-name="{{$subcategory->name}}">
    <div class="m-portlet__head subcategory">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    {{$subcategory->name}}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-primary void editSubcategory">
                        <i class="la la-edit"></i>
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                    <a href="#" data-portlet-tool="remove" class="m-portlet__nav-link btn m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-danger void deleteCategory childCategory">
                        <i class="la la-trash"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>