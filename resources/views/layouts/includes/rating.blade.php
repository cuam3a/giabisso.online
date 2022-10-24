<div class="list-forum ratings">
    <div class="container">
        <div class="row">
             <div class="col-12">
            <div class="item-list">
            
                @foreach ( $product->reviews as $review  )

                <div class="item d-flex flex-row flex-wrap">
                    <div class="col-12">
                        <h5>{{ $review->customer->name }} <small> <b> {{ $review->created_at }} </b> </small> </h5>

                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-row ">
                            <select class="averageRating">

                                <option value=""></option>
                                @for( $i = 1 ; $i < 6 ; $i++)
                                    <option value="{{ $i }}" @if ($i == $review->rating) selected @endif >{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="col-12">
                        @if ($review->status == 1)
                            <p> {{ $review->review }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            </div>  
        </div>
    </div>
</div>