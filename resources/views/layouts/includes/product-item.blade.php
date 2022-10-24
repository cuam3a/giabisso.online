<div class="@if( (Route::current()->named('search-products') || Route::current()->named('category-products') || Route::current()->named('settlement-products') )&& (!isset($_GET['view']) || $_GET['view'] == 'grid')) col-md-4 @else col-md-12 @endif">
    <a href="{{ route('product-detail', ['id' => $product->id,'slug' => $product->slug]) }}">
        <div class="item">
            @if($product->getOfferPercent() > 0)
            <div class="discount">
                    - {{$product->getOfferPercent()}} %
            </div>
            @endif
            <div class="image">
                <img src="{{$product->image}}" alt="{{$product->slug}}" class="img-fluid">
            </div>
            <!--<div class="discount">- 40%</div>-->
            <div class="description">{{$product->name}}</div>
            <div class="text-center">
                <select class="averageRating" id="rating">
                    <option value=""></option>
                    @for( $i = 1 ; $i < 6 ; $i++)
                        <option value="{{ $i }}" @if ($i == $product->rating) selected @endif >{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="prices">
                <div class="col label-price">Precio</div>
                @if($product->liquidado == 1)
                    <div class="col regular_price @if($product->liquidado_price > 0 ) withOffer @endif">${{$product->regular_price}} <span class="currency">MXN</span></div>
                    @if($product->liquidado_price > 0)
                        <div class="col offer_price">${{$product->liquidado_price}} <span class="currency">MXN</span></div>
                    @endif
                @else                 
                    <div class="col regular_price @if($product->offer_price && $product->offer > 0 ) withOffer @endif">${{$product->regular_price}} <span class="currency">MXN</span></div>
                    @if($product->offer_price && $product->offer > 0)
                        <div class="col offer_price">${{$product->offer_price}} <span class="currency">MXN</span></div>
                    @endif

                    @if(Auth::guard('customer-web')->check())
                        @if($price_customer != null)
                            @foreach($price_customer as $item)
                                @if($item->product_id == $product->id)
                                    @if(($item->price < $product->offer_price && $product->offer > 0) || ($item->price < $product->regular_price))
                                        <div class="col text-primary"> ${{$item->price}} <span class="currency">MXN</span></div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endif

            </div>
        </div>
    </a>
</div>

