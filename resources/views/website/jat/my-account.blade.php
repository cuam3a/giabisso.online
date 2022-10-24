@php $customer = new App\Models\Customer(); @endphp
@extends('layouts.jat.website')
@section('extra-head')
    <link rel="stylesheet" href="{{ asset('/website/css/my-account.css') }}">
    <link rel="stylesheet" href="/js/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('/website/css/refunds.css') }}">
    
    
@stop
<style>
    .rotate {   transform:rotate(90deg); }
    #nav-tab .nav-item{
        width:20%;
    }
    .m-badge{
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        -ms-border-radius: 10px;
        -o-border-radius: 10px;
        border-radius: 10px;
        background: #eaeaea;
        color: #FFF;
        font-size: .8rem;
        line-height: 20px;
        min-height: 20px;
        min-width: 20px;
        vertical-align: middle;
        text-align: center;
        display: inline-block;
        padding: 2px 10px;
        white-space: nowrap;
        font-weight: 300;
        -webkit-font-smoothing: antialiased;
    }
    .m-badge--info{
        background-color: #36a3f7
    }
    .m-badge--danger{
        background-color: #f4516c
    }
    .m-badge--success{
        background-color: #34bfa3
    }
    .m-badge--default{
        background-color: #ffb822
    }
    .m-badge--warning{
        background-color: #f4516c
    }
    .del-refund{
        cursor:pointer;
    }
</style>
@section('content')
    <section class="my-account">
        <div class="container">
            @if ($errors->any())
            <div class="row">
                <div class="col-12">       
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <ul>
                            @foreach ($errors->all() as $error)
                                 <li>{{$error}}</li>
                            @endforeach 
                            </ul>
                    </div>
                </div>
            </div>
            @endif

            @if(Session::has('flash_message'))
                <div class="row">
                    <div class="col-12">       
                        <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title')}}</strong> {!!Session::get('flash_message')!!}</div>
                    </div>
                </div>
            @endif
            <nav class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{session()->has('address_tab') ? '' : 'active'}}" id="profile" data-toggle="tab" href="#mi-perfil" role="tab" aria-controls="nav-profile" aria-selected="true" style="width:20%">MI PERFIL</a>
                <a class="nav-item nav-link" id="credit" data-toggle="tab" href="#credito" role="tab" aria-controls="nav-credit" aria-selected="false">CREDITO</a>
                <a class="nav-item nav-link" href="#mis-pedidos" data-toggle="tab" role="tab" aria-selected="false">PEDIDOS</a>
                <a class="nav-item nav-link" href="#mis-pedidos-programados" data-toggle="tab" role="tab" aria-selected="false">PEDIDOS PROGRAMADOS</a>
                <!--<a class="nav-item nav-link" id="favorites" data-toggle="tab" href="#favoritos" role="tab" aria-controls="nav-favorites" aria-selected="false">FAVORITOS</a>-->
                <a class="nav-item nav-link {{session()->has('address_tab') ? 'active' : ''}}" id="address" data-toggle="tab" href="#direcciones" role="tab" aria-controls="nav-address" aria-selected="false">DIRECCIONES</a>
                <a class="nav-item nav-link" id="refunds" data-toggle="tab" href="#devoluciones" role="tab" aria-controls="nav-refunds" aria-selected="false">DEVOLUCIONES</a>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{session()->has('address_tab') ? '' : 'show active'}}" id="mi-perfil" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <form id="frmCustomerInfo" method="post" action="{{route('update-customer-profile')}}">
                        {{csrf_field()}}
                        <div class="form-row justify-content-md-between">
                            <div class="col-md-6 mb-3">
                                <label for="name">Nombre</label>
                                <input type="text" style="display:none" class="form-control" name="name" value="{{Auth::guard('customer-web')->user()->name}}" required>
                                <label for="name" style="display:block" class="lblVal">{{Auth::guard('customer-web')->user()->name}}</label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastname">Apellidos</label>
                                <input type="text" style="display:none" class="form-control"name="lastname" value="{{Auth::guard('customer-web')->user()->lastname}}"  required>
                                <label for="name" style="display:block" class="lblVal">{{Auth::guard('customer-web')->user()->lastname}}</label>
                            </div>
                        </div>
                        <div class="form-row justify-content-md-between">
                            <div class="col-md-12 mb-3">
                                <label for="email">Correo electrónico</label>
                                <input type="text" style="display:none" class="form-control" name="email" value="{{Auth::guard('customer-web')->user()->email}}" required>
                                <label for="name" style="display:block" class="lblVal">{{Auth::guard('customer-web')->user()->email}}</label>
                            </div>
                        </div>
                         <div class="form-row justify-content-md-between">
                            <div class="col-md-6 mb-3">
                                <label for="cell_phone">Celular</label>
                                <input type="text"  style="display:none" class="form-control" name="cell_phone" value="{{ @Auth::guard('customer-web')->user()->cell_phone}}">
                                <label for="name" style="display:block" class="lblVal">{{ Auth::guard('customer-web')->user()->cell_phone}}</label>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone">Teléfono</label>
                                <input type="text" style="display:none" class="form-control" name="phone" value="{{ @Auth::guard('customer-web')->user()->phone}}">
                                <label for="name" style="display:block" class="lblVal">{{ Auth::guard('customer-web')->user()->phone}}</label>
                            </div>
                        </div>
                        <hr />
                        <h4>DATOS DE FACTURACIÓN</h4>
                        <div class="form-row justify-content-md-between">
                            <div class="col-md-6 mb-3">
                                <label for="name">Razon Social</label>
                                <input type="text" style="display:none" class="form-control" name="f_name" value="{{Auth::guard('customer-web')->user()->f_name}}" required>
                                <label for="name" style="display:block" class="lblVal">{{Auth::guard('customer-web')->user()->f_name}}</label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastname">RFC</label>
                                <input type="text" style="display:none" class="form-control" name="f_rfc" value="{{Auth::guard('customer-web')->user()->f_rfc}}"  required>
                                <label for="name" style="display:block" class="lblVal">{{Auth::guard('customer-web')->user()->f_rfc}}</label>
                            </div>
                        </div>
                        <div class="form-row justify-content-md-between">
                            <div class="col-md-6 mb-3">
                                <label for="name">Dirección</label>
                                <input type="text" style="display:none" class="form-control" name="f_address" value="{{Auth::guard('customer-web')->user()->f_address}}" required>
                                <label for="name" style="display:block" class="lblVal">{{Auth::guard('customer-web')->user()->f_address}}</label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastname">C.P.</label>
                                <input type="text" style="display:none" class="form-control" name="f_zipcode" value="{{Auth::guard('customer-web')->user()->f_zipcode}}"  required>
                                <label for="name" style="display:block" class="lblVal">{{Auth::guard('customer-web')->user()->f_zipcode}}</label>
                            </div>
                        </div>
                        <div class="form-row ">
                            <div class="col-md-6 mb-3">
                            <label for="state">Estado</label>
                                <select id="f_state" class="form-control" name="f_state" required hidden>
                                    @foreach($states as $id => $name)
                                    <option value="{{$id}}" {{ ( $id == Auth::guard('customer-web')->user()->f_state) ? 'selected' : '' }}>{{$name}}</option>
                                    @endforeach
                                </select>
                                <label for="name" style="display:block" class="lblVal">{{$f_state_customer->name}}</label>
                            </div>
                            <div class="col-md-6 mb-3">
                            <label for="city">Ciudad</label>
                                <select id="f_city" class="form-control" name="f_city" required hidden>
                                    <option selected>Elige una ciudad</option>
                                </select>
                                <label for="name" style="display:block" class="lblVal">{{$f_city_customer->name}}</label>
                            </div>
                        </div>
                        <hr />
                        
                        <a class="btn btn-primary btnEdit" style="color:white">Editar</a>
                        <button class="btn btn-primary btnSave" style="display:none" type="submit">Guardar</button>
                    </form>
                </div>
                <div class="tab-pane fade nav-orders" id="credito" role="tabpanel" aria-labelledby="nav-credit-tab">
                    <div >
                        <h4>CREDITO</h4>
                        <br>
                        <div class="form-row justify-content-md-between">
                            <div class="col-md-3 mb-3">
                                <label for="name" class="font-weight-bold">Estado</label>
                                <label for="name" style="display:block" class="text-center">{{Auth::guard('customer-web')->user()->getCreditstatus()}}</label>
                            </div>
                            <div class="col-md-9 mb-9"></div>
                            <div class="col-md-3 mb-3">
                                <label for="name" class="font-weight-bold">Credito</label>
                                <label for="name" style="display:block" class="text-center">$ {{Auth::guard('customer-web')->user()->credit}}</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="lastname" class="font-weight-bold">Días de Credito</label>
                                <label for="name" style="display:block" class="text-center">{{Auth::guard('customer-web')->user()->credit_days}}</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="lastname" class="font-weight-bold">Pendiente de Pago</label>
                                <label for="name" style="display:block" class="text-center">$ {{Auth::guard('customer-web')->user()->getSaldo()}}</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="lastname" class="font-weight-bold">Disponible</label>
                                <label for="name" style="display:block" class="text-center">$ {{Auth::guard('customer-web')->user()->getAvailable()}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade nav-orders" id="mis-pedidos" role="tabpanel" aria-labelledby="nav-orders-tab">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                        <label class="label">Estado</label>
                        <select class="form-control form-control-sm" id="selectEstado">
                            <option value="Todos" {{ ( $filtro == 'Todos') ? 'selected' : '' }}>Todos</option>
                            <option value="Pagado" {{ ( $filtro == 'Pagado') ? 'selected' : '' }}>Pagado</option>
                            <option value="Pendiente" {{ ( $filtro == 'Pendiente') ? 'selected' : '' }}>Pendiente</option>
                        </select>
                        </div>
                    </div>
                    <hr/>
                    @if($orders->count()>0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Folio</th>
                                    <!--<th>Productos</th>-->
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Ver detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{$order->date()}}</td>
                                        <td>{{$order->folio()}}</td>
                                        <!--<td>{{$order->order_details->count()}}</td>-->
                                        <td>${{$order->total}} MXN</td>
                                        <td>{{$order->getPaymentTextAttribute()}}</td>
                                        <td style="width:40%">
                                            <div class="form-inline col-md-12">
                                                <div class="col-md-6">
                                                    <a href="{{route('payment-detail',[$order->id, $order->email])}}"><i class="fa fa-search"></i></a>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="{{route('order-export-pdf',['id'=> $order->id])}}"><i class="fa fa-download"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                     @else
                        <div class="col-12 col-sm-12 d-flex flex-row align-items-center border no-products mt-4">
                                <div class="p-3"><img src="/website/img/sin-prods_pedidos.png"></div>
                                <div class="d-flex flex-column">
                                    <h4 class="font-weight-bold">No tiene pedidos</h4>
                                    <p>Consulta nuestros productos y promociones!</p>
                                </div>
                            </div>
                     @endif
                </div>
                <div class="tab-pane fade nav-orders" id="mis-pedidos-programados" role="tabpanel" aria-labelledby="nav-orders-tab">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                        
                        </div>
                    </div>
                    <hr/>
                    @if($orders_programming->count()>0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Folio</th>
                                    <th>Productos</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Ver detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders_programming as $order)
                                    <tr>
                                        <td>{{$order->date()}}</td>
                                        <td>{{$order->folio()}}</td>
                                        <td>{{$order->countDetails($order->id)}}</td>
                                        <td>${{$order->totalOrder($order->id)}} MXN</td>
                                        <td>{{$order->getPaymentTextAttribute()}}</td>
                                        <td style="width:40%">
                                            <div class="form-inline col-md-12">
                                                <div class="col-md-6">
                                                    <a href="{{route('payment-detail',[$order->id, $order->email])}}"><i class="fa fa-search"></i></a>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="{{route('order-export-pdf',['id'=> $order->id])}}"><i class="fa fa-download"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                     @else
                        <div class="col-12 col-sm-12 d-flex flex-row align-items-center border no-products mt-4">
                                <div class="p-3"><img src="/website/img/sin-prods_pedidos.png"></div>
                                <div class="d-flex flex-column">
                                    <h4 class="font-weight-bold">No tiene pedidos</h4>
                                    <p>Consulta nuestros productos y promociones!</p>
                                </div>
                            </div>
                     @endif
                </div>
                <div class="tab-pane fade nav-favorites" id="favoritos" role="tabpanel" aria-labelledby="nav-favorites-tab">
                    <div class="row products-favorite">
                        @if(Session::has('flash_message_favorite'))
                            <div class="alert alert-{{Session::get('flash_type')}} alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button><strong>{{Session::get('flash_title_favorite')}}</strong> {{Session::get('flash_message_favorite')}}.</div>
                        @endif
                        @if($favorites->count()>0)
                        <div class="col-md-12 title-row hidden-sm-down">
                            <div class="col-7">PRODUCTO</div>
                            <div class="col-2 price">PRECIO</div>
                        </div>
                            @foreach($favorites as $favorite)
                            <div class="col-md-12">
                                <div class="item flex-row">
                                    <div class="col-4 col-md-2 image">
                                        <img src="{{ $favorite->product->image }}" alt="Truper" class="img-fluid">
                                    </div>
                                    <div class="col-8 col-md-5 description">
                                        <div class="text">{{$favorite->product->name}}</div>
                                        <h4 class="title">{{$favorite->product->brand->name}}</h4>
                                    </div>
                                    <div class="col-2 prices">                 
                                        @if($favorite->product->offer_price && $favorite->product->offer > 0 )
                                            <div class="offer_price">${{$favorite->product->regular_price}} <span class="currency">MXN</span></div>
                                        @endif 
                                        <div class="regular_price @if($favorite->product->offer_price && $favorite->product->offer > 0) withOffer @endif">${{$favorite->product->offer_price}} <span class="currency">MXN</span></div>
                                    </div>
                                    <div class="col-md-3 actions">
                                        <a href="{{ route('product-detail', ['id' => $favorite->product->id,'slug' => $favorite->product->slug]) }}" class="btn btn-primary">Ver detalle</a>
                                        <a href="#" class="btn btn-default void btnDeleteFav" data-url="{{ route('update-favorites',['favorite' => $favorite->product->id])}}"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                         @else
                            <div class="col-12 col-sm-12 d-flex flex-row align-items-center border no-products mt-4">
                                    <div class="p-3"><img src="/website/img/sin-prods_favoritos.png"></div>
                                    <div class="d-flex flex-column">
                                        <h4 class="font-weight-bold">No tiene favoritos</h4>
                                        <p>Consulta nuestros productos y promociones!</p>
                                    </div>
                                </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade nav-address {{session()->has('address_tab') ? 'show active' : ''}}" id="direcciones" role="tabpanel" aria-labelledby="nav-address-tab">
                    <div class="row">
                        <div class="col-md-6">
                            @if($address_book->count()>0)
                            
                                @foreach($address_book as $address)
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="card-title">
                                                <h6><strong>{{$address->address_name}}</strong></h6>
                                                <div class="actions">                                                
                                                    <a href="#" class="edit-address" data-toggle="tooltip" data-placement="top" 
                                                        data-id="{{$address->id}}" 
                                                        data-address-name="{{$address->address_name}}" 
                                                        data-name="{{$address->name}}" 
                                                        data-lastname="{{$address->lastname}}" 
                                                        data-address="{{$address->address}}" 
                                                        data-street-number="{{$address->street_number}}" 
                                                        data-suit-number="{{$address->suit_number}}" 
                                                        data-between-streets="{{$address->between_streets}}" 
                                                        data-neighborhood="{{$address->neighborhood}}" 
                                                        data-city="{{$address->city}}" 
                                                        data-state="{{$address->state}}" 
                                                        data-zipcode="{{$address->zipcode}}" 
                                                        data-phone="{{$address->phone}}" 
                                                        data-cell-phone="{{$address->cell_phone}}" 
                                                        data-instructions-place="{{$address->instructions_place}}" 

                                                        title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                    <a class="del-address" data-toggle="tooltip" data-target="#modalRUsure" data-toggle="modal" href="#" data-id="{{$address->id}}" data-placement="top" title="Eliminar"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                            <ul style="list-style-type: none;">
                                                <li>{{$address->name}} {{$address->lastname}}</li>
                                                <li>{{$address->address}} {{$address->street_number}} {{$address->suit_number}} {{$address->neighborhood}} {{$address->between_streets}} {{$address->zipcode}}
                                                {{$address->instructions_place}}</li>
                                                <li>{{$address->City['name']}}, {{$address->State['name']}}</li>
                                                <li>{{$address->phone}} {{$address->cell_phone}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 col-sm-12 d-flex flex-row align-items-center border no-products mt-4">
                                    <div class="p-3"><img src="/website/img/sin-prods_pedidos.png"></div>
                                    <div class="d-flex flex-column">
                                        <h4 class="font-weight-bold">No tienes direcciones</h4>
                                        <p>Consulta nuestros productos y promociones!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <form id="frmAddAddress" action="{{route('save-customer-address')}}" method="POST">
                                {{ csrf_field() }}

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address">Nombre de dirección:</label>
                                        <input type="text" class="form-control" name="address_name" placeholder="Ej. Casa, oficina...">
                                    </div>
                                </div>     
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="address">Nombre(s):</label>
                                        <input type="text" class="form-control" name="name">
                                        <input type="hidden" class="form-control" name="id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">Apellido</label>
                                        <input type="text" class="form-control" name="lastname">
                                    </div>
                                </div>                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="address">Teléfono</label>
                                        <input type="text" class="form-control" required maxLenght="10" name="phone">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">Celular</label>
                                        <input type="text" class="form-control"required maxLenght="10" name="cell_phone">
                                    </div>
                                </div>                                

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address">Dirección</label>
                                        <input type="text" class="form-control" name="address">
                                    </div>
                                </div>     
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="street_number">Número exterior</label>
                                        <input type="text" class="form-control" name="street_number">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="suit_number">Número interior</label>
                                        <input type="text" class="form-control" name="suit_number">
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="between_streets">Entre calles</label>
                                        <input type="text" class="form-control" name="between_streets"> 
                                    </div>
                                </div>
                               
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="neighborhood">Colonia</label>
                                        <input type="text" class="form-control" name="neighborhood">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="state">Estado</label>
                                        <select name="state" id="state" class="form-control">
                                            @foreach($states as $id => $name)
                                                <option value="{{$id}}">{{$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>            
                                    <div class="form-group col-md-6">
                                        <label for="city">Ciudad</label>
                                        <select name="city" id="city" class="form-control">
                                            <option selected>Elige una ciudad</option>
                                        </select>
                                    </div>                        
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="zipcode">Código postal</label>
                                        <input type="text" class="form-control" name="zipcode">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="instructions_place">Indicaciones</label>
                                    <textarea  id="instructions_place" class="form-control" name="instructions_place" rows="2"></textarea>
                                </div>
                                <a class="btn btn-warning pull-right" style="margin-left:10px; display:none" id="btn-cancel">CANCELAR</a> 
                                 <button class="btn btn-primary pull-right" type="submit" id="btn-save-address">AGREGAR DIRECCIÓN</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade nav-refunds" id="devoluciones" role="tabpanel" aria-labelledby="nav-refunds-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary pull-right btnRefund btn-common"  data-url = "{{route('refunds-products')}}" type="submit">Solicitar devolución</button>
                        </div>
                    </div>
                    <div class="row mt-4">

                    @if($refunds->count()>0)
                    <div class="table-responsive">
                        <table class="table table-hover"¡¡¡>
                        <thead>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Motivo</th>
                            <th>Comentario</th>
                            <th>Respuesta</th>
                            <th>Estatus</th>
                            <th>Opciones</th>
                        </tr>
                        <tbody>
                            @foreach($refunds as $refund)
                                <tr>
                                    <td>{{$refund->date}}</td>
                                    <td>
                                        <a href="{{route('product-detail',['id' => $refund->product_id, 'slug' => $refund->slug])}}">
                                            @if($refund->status > 0) <b>{{$refund->quantity}} x </b> @endif <span class="m-card-user__name">{{$refund->product}}</span>
                                        </a>
                                    </td>
                                    <td>{{$refund->reason}}</td>
                                    <td>{{$refund->comment}}</td>
                                    <td>{{$refund->comment_admin}}</td>
                                    <td>
                                        <span class="m-badge m-badge--{{$customer->getRefundStatus($refund->status)['class']}}">{{$customer->getRefundStatus($refund->status)['text']}}</span>
                                    </td>
                                    <td>
                                        <a href="{{route('payment-detail', ["order" => $refund->order_id, "email" => $refund->email])}}" class="btn" title="Ver pedido"><i class="fa fa-search"></i></a>
                                        @if($refund->status == 1)
                                        <a class="del-refund" data-refund-id="{{$refund->id}}"><i class="fa fa-times"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                    @else
                    <div class="col-12 col-sm-12 d-flex flex-row align-items-center border no-products mt-4">
                            <div class="p-3"><img src="/website/img/sin-prods_pedidos.png"></div>
                            <div class="d-flex flex-column">
                                <h4 class="font-weight-bold">Aún no tienes devoluciones</h4>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalRUsure" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <input type="hidden" name="delete_id" value="" \><!--Utilizado para eliminar categoria -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        ¿Esta seguro?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <form method="GET" action="">
                        <button type="button" class="btn btn-secondary cancel btn-no" data-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary btn-yes">
                            Aceptar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
<script src="/js/select2/js/select2.min.js"></script>
<script>
var edit_city;
var edit_mode = false;
var edit_customer_mode = false;

$( document ).ready(function() {
    
});

var setModalInfo = function(info){
    $("#modalRUsure .modal-body").html(`Se ${info.action} la dirección.<br> ¿Desea continuar?`);
    $("#modalRUsure form").attr('action', info.url);
}
//Link
$( ".link-order" ).on('click', 'td:not(.refund)',function() {
    var tr = $(this).closest('tr')
    window.location = tr.data('url');  
});



$( ".btnDeleteFav" ).click(function() {
   var url = $(this).data('url');
   var form = $('<form>').attr('action', url).attr('method', 'get').append($('<input>').attr('type', 'hidden').attr('name', 'hashtag').attr('value', '#favoritos'))
    $('body').append(form);
    $(form).submit();  
});

var hash = window.location.hash;
  hash && $('.nav-tabs a[href="' + hash + '"]').tab('show');
   $('.nav-tabs a').click(function (e) {
    $(this).tab('show');
    var scrollmem = $('body').scrollTop();
    window.location.hash = this.hash;
    $('html,body').scrollTop(scrollmem);
  });
$( ".account_tab" ).click(function() {
    var link = $(this).attr('href').split('#')[1];
    $('.nav-tabs a[href$='+link+']').trigger('click');
});

$(".btnEdit").on("click", function() {
    
    // $(this).html('Editar información');
    // $(this).removeClass('btnSave').addClass('btnEdit');
    // $('#mi-perfil input').css('display','none');
    // $('#mi-perfil .lblVal').css('display','block');
    $(".btnSave").show();
    $('#mi-perfil .lblVal').css('display','none');
    $('#mi-perfil input').css('display','block');
    $('#mi-perfil select').prop('hidden',false);
    $(this).hide();
    edit_customer_mode = true;
    $('#f_state').trigger('change');
});

$('#state').on('change', function (e) {
    var who = '';
    
    $.ajax({
        method: "POST",
        url: "{{route('get-cities')}}",
        data: {
          '_token': '{!! csrf_token() !!}',
          'state': $(this).val()
        }
    }).done(function(ciudades) {
        $('#'+who+'city option').remove();
        $.each(ciudades, function(key,text) {
            var newOption = new Option(text,key, false, false);
            $('#'+who+'city').append(newOption);
        });

        if(edit_mode){
            $("#city").val(edit_city);
        }
        

    });
});

$('#f_state').on('change', function (e) {
    var who = '';
    
    $.ajax({
        method: "POST",
        url: "{{route('get-cities')}}",
        data: {
          '_token': '{!! csrf_token() !!}',
          'state': $(this).val()
        }
    }).done(function(ciudades) {
        $('#f_city option').remove();
        $.each(ciudades, function(key,text) {
            var newOption = new Option(text,key, false, false);
            $('#f_city').append(newOption);
        });

        
        if(edit_customer_mode){
            debugger
            $("#f_city").val("{{Auth::guard('customer-web')->user()->f_city}}");
        }

    });
});


$("#state").select2({
    placeholder: 'Elige un estado',
    language: "es"
});

$("#city").select2({
    placeholder: 'Elige una ciudad',
    language: "es"
});

$.extend($.validator.messages, {
    maxLength: jQuery.validator.format("Este campo debe tener menos de {0} caracteres")
});

$.validator.messages.required = 'Campo requerido';
$("#frmAddAddress").validate({
    rules: {
        address_name: {
            required: true,
        },
        name: {
            required: true,
        },
        lastname: {
            required: true,
        },
        phone: {
            required: true,
            maxlength:20
        },
        address: {
            required: true
        },
        between_streets: {
            required: true
        },
        street_number:{
            required: true
        },
        cell_phone: {
            required: false,
            maxlength:20
            // number:true,
            // digits:true
        },
        state: {
            required: true
        },
        city: {
            required: true
        },
        neighborhood: {
            required: true
        },
        email: {
            required: true,
            maxlength:60,
            email:true
        },
        zipcode: {
            required: true,
            number:true,
            maxlength:5,
            minlength:5,
            digits:true
        },
        f_name: {
          required: true,
          maxlength:50
        },
        f_rfc: {
          required: true,
          maxlength:50
        },
        f_address: {
          required: true,
          maxlength:50
        },
        f_zipcode: {
          required: true,
          maxlength:50
        },
        f_state: {
          required: true

        },
        f_city: {
          required: true
          
        }
    },
    messages:{
    email: {
        required: "Campo requerido",
        maxlength: "Este campo debe ser no mayor a 60 caracteres",
        email: "Correo electrónico inválido"
    },
    phone: {
        required: "Campo requerido",
        maxlength: "Este campo debe tener máximo 20 caracteres"
    },
    cell_phone: {
        maxlength: "Este campo debe tener máximo 20 caracteres"
    },
    zipcode: {
        required: "Campo requerido",
        maxlength: "Este campo debe tener a 5 digitos",
        minlength: "Este campo debe tener a 5 digitos",
        number: "Solo números",
        number: "Solo números"
    },
    f_rfc: {
        required: "Campo requerido",
        maxlength: "Este campo debe tener máximo 13 digitos",
        minlength: "Este campo debe tener al menos 11 digitos"
    },
    f_email: {
        required: "Campo requerido",
        maxlength: "Este campo debe ser no mayor a 50 caracteres",
        email: "Correo electrónico inválido"
    },
    f_phone: {
        required: "Campo requerido",
        maxlength: "Este campo debe tener a 10 digitos",
        number: "Solo números",
        digits: "Solo números"
    },
    f_name: {
        required: "Campo requerido"
    },
    f_rfc: {
        required: "Campo requerido"
    },
    f_address: {
        required: "Campo requerido"
    },
    f_zipcode: {
        required: "Campo requerido"
    },
    f_state: {
        required: "Campo requerido"
    },
    f_city: {
        required: "Campo requerido"
    }
    }
});/* validate for submit*/

/* Validación de boton */
var $inputs = $('#frmAddAddress input');
$inputs.on("keyup", function () {
        valido = true;
        $inputs.each(function () {
            valido *= $(this).valid();
            return valido;
        });
        if(valido){
        $("#frmCreateOrder").removeAttr("disabled");
        }else{
        $("#frmCreateOrder").attr('disabled',true);
        }
});
/* /Validación de boton */

$(".toggle-address").on("click",function(){
    $(this).parent().parent().parent().children('.hide-address').toggle();

    $(this).children(".arrow").toggle();
})

$(".del-address").on("click",function(){
    var id = $(this).data('id');
    var info = {}
    route_del_slider = "{{ route('del-customer-address', ['id' => 'id_replace']) }}";
    info.url = route_del_slider.replace('id_replace', id);
    info.msg = 'Eliminar dirección'
    info.action = 'eliminará'

    // <a data-id="1" data-target="#modalRUsure" data-toggle="modal" href="#" data-action="eliminará" data-description="Promoción Mayo" data-title="Eliminar slider" title="Eliminar slider" class="del-slider m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="la la-times"></i></a>

    setModalInfo(info);

    $("#modalRUsure").modal('toggle');

})

$("#btn-save-address").on("click",function(){
    $("#btn-save-address").text('AGREGAR DIRECCIÓN');
    $("#btn-save-address").removeClass('btn-info').addClass('btn-primary');
    // $(this).hide();
    $("#btn-cancel").hide();
    edit_mode = false;
})

$("#btn-cancel").on("click",function(){
    $("#btn-save-address").text('AGREGAR DIRECCIÓN');
    $("#btn-save-address").removeClass('btn-info').addClass('btn-primary');
    $(this).hide();
    edit_mode = false;
    $("#frmAddAddress")[0].reset();
    $("input[name=id]").val('');
})

$(".edit-address").on("click",function(){
    edit_mode = true;
    $("#btn-cancel").show();
    data = $(this).data();
    console.log(data);

    $("#btn-save-address").text('EDITAR DIRECCIÓN');
    $("#btn-save-address").removeClass('btn-primary').addClass('btn-info');

    $("input[name=id]").val(data.id);
    $("input[name=name]").val(data.name);
    $("input[name=address_name]").val(data.addressName);
    $("input[name=lastname]").val(data.lastname);
    $("input[name=address]").val(data.address);
    $("input[name=street_number]").val(data.streetNumber);
    $("input[name=suit_number]").val(data.suitNumber);
    $("input[name=between_streets]").val(data.betweenStreets);
    $("input[name=neighborhood]").val(data.neighborhood);
    $("#state").val(data.state).trigger('change');
    edit_city = data.city;
    $("input[name=zipcode]").val(data.zipcode);
    $("input[name=phone]").val(data.phone);
    $("input[name=cell_phone]").val(data.cellPhone);
    $("#instructions_place").val(data.instructionsPlace);
})

$(".btnRefund").on("click",function(){
    window.location = $(this).data('url');
})

$(".del-refund").on("click",function(){
        swal({
        title: '¿Cancelar?',
        text: "Se cancelará la devolución ¿Desea continuar?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, cancelarla',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){   
                var newForm = $('<form>', {
                    'action': "{{route('refunds-customer-delete')}}",
                    'method': 'POST'
                });
                var token = '{{csrf_field()}}';

                var newInput = $('<input>',{
                    type : "hidden",
                    name : "refund_id",
                    value : $(this).data('refund-id')
                });
                newForm.append(newInput, token);
                newForm.appendTo('body').submit();
            }
        });
})

//GCUAMEA
$("#selectEstado").on("change", function(){
    let val = $(this).val();
    window.location.href = "{{route('my-account-jat')}}?filtro="+val+"#mis-pedidos";
})

</script>
@stop