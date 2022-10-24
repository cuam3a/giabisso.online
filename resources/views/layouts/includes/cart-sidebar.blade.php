<div class="cart-sidebar">
    <h4 class="wborder col-12">TU ORDEN</h4>
    <div class="cart-content">
        <div class="item title">
           <div class="name col-12">Productos</div>
        </div>
        @foreach($cartBasket as $cartItem)
            <div class="item">
                <div class="name col-7">{{$cartItem->qty}}x {{$cartItem->name}}</div>
                <div class="price col-5">{{dinero($cartItem->price*$cartItem->qty)}}</div>
            </div>
        @endforeach
    </div><!--/cart-content-->
    <div class="shipping">
        <div class="item">
            <div class="name">Envío</div>
            <div class="price">{{dinero(Session::get('shipping'))}}</div>
        </div>
        <div class="col-md-12">
            <div class="name"><small style="font-size:13px">Tiempo de entrega: {{Session::get('shipping_description')}}</small></div>
        </div>
        @if($free_shipment)
            @if($free_shipment->value =='ON')
                <div style="color:#5aa407;padding:0 20px 20px;"><b>Envío gratis</b> en compras mayores a {!!dinero($amount->value)!!}</div>
            @endif
        @endif
        <!--
        <div id="accordion" role="tablist">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h5 class="mb-0">
                        <a data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="true" aria-controls="collapseOne">
                        <input class="form-check-input" type="radio" name="metodo1" id="metodo1" value="1" checked>
                        Método 1
                        <div class="arrow"></div>   
                        </a>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamu</div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingTwo">
                <h5 class="mb-0">
                    <a class="collapsed" data-toggle="collapse" href="#collapseTwo" role="button" aria-expanded="false" aria-controls="collapseTwo">
                    <input class="form-check-input" type="radio" name="metodo1" id="metodo1" value="1" checked>
                    Método 2
                    <div class="arrow"></div>   
                    </a>
                </h5>
                </div>
                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamu</div>
                </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingThree">
                <h5 class="mb-0">
                    <a class="collapsed" data-toggle="collapse" href="#collapseThree" role="button" aria-expanded="false" aria-controls="collapseThree">
                    <input class="form-check-input" type="radio" name="metodo1" id="metodo1" value="1" checked>
                    Método 3
                    <div class="arrow"></div>   
                    </a>
                </h5>
                </div>
                <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamu</div>
                </div>
            </div>-->
    </div>
    <div class="total"> 
        <div class="name">Total</div>
        <div class="price">{{dinero(Session::get('total'))}}</div>
    </div>
    <div class="conditions">
        <div class="form-check text">   
            <input name="invoice_chk" type="hidden" required>
            <input class="form-check-input" type="checkbox" id="conditions" name="conditions" required>
            <label class="form-check-label" for="conditions">He leído y acepto los <br><a target="blank" href="{{route('privacy')}}">Términos y condiciones</a></label>
        </div>      
    </div>
    <a href="" class="btn btn-primary btn-block void btnPay">CONTINUAR</a>
</div>