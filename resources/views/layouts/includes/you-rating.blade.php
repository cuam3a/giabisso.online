<!-- The Modal -->
<div class="modal" id="modal-rating">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Evalua este producto </h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
        <div class="row">
            <div class="col-12">
                <form id="form-rating" action="{{ route('add-rating') }}" method="post">

                    {{ csrf_field() }}
            
                    <input type="hidden" name="id" id="id" value="@if (  count($product->myReview) ) {{ $product->myReview[0]['id'] }} @else 0 @endif" >

                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}" >


                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <select class="rateInput align-self-center" id="valor" name="valor">
                                <option value=""></option>
                                @if ( count($product->myReview) )
                                    @for( $i = 1 ; $i < 6 ; $i++)
                                        <option value="{{ $i }}" @if ($i == $product->myReview[0]['rating']) selected @endif >{{ $i }}</option>
                                    @endfor
                                @else
                                    @for( $i = 1 ; $i < 6 ; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <textarea class="form-control" name="review" id="review">@if (  count($product->myReview)) {{ $product->myReview[0]['review'] }}@endif</textarea>
                        </div>


                    </div>

            </form>

            </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn-save-rating"> <i class="fa fa-save"></i> Guardar </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar </button>
      </div>

    </div>
  </div>
</div>

