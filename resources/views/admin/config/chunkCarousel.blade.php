<div class="m-portlet m-portlet--head-solid-bg m-portlet--head-sm m-portlet--sortable m-portlet-carousel parent-carousel" data-portlet="true" data-id="{{$carousel->id}}" data-name="{{$carousel->name}}">
    <div class="m-portlet__head ui-sortable-handle carousel">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="la la-bars"></i>
                </span>
                <div class="divCarousel"></div>
                <h3 class="m-portlet__head-text">
                    {{ $carousel->name }}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{route('admin-carousel-detail',[$carousel->id])}}" class="m-portlet__nav-link btn m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-info">
                        <i class="flaticon-interface-9"></i>
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-primary void editCarousel">
                        <i class="la la-edit"></i>
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                <a href="#" class="m-portlet__nav-link btn m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-danger void deleteCarousel">
                        <i class="la la-trash"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body children">
    </div>
</div>