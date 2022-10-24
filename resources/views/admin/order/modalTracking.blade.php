<!--begin::Modal-->
<div class="modal fade" id="modalTracking" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <form id="frmTracking" action="{{ route('admin-order-save-shipping-details') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$order->id}}" \>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Envio
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="shipping" class="form-control-label">
                                Paquetería:
                            </label>
                            <select class="form-control m-input m-input--square" id="delivery_service" name="delivery_service" required>
                                @foreach($delivery_service as $id => $text)
                                    <option value="{{$id}}" @if($order->delivery_service == $id) selected @endif>{{$text}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tracking_number" class="form-control-label">
                                Número de guía:
                            </label>
                            <textarea class="form-control" id="tracking_number" name="tracking_number" required>{{$order->tracking_number or ''}}</textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--end::Modal-->