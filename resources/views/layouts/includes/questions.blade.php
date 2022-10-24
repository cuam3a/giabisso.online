<div class="list-forum questions">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <hr>
                <form>
                    <h2 class="title">Preguntas y respuestas</h2>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Nombre">
                        </div>
                        <div class="form-group col-md-6">
                        <input type="text" name="email" class="form-control" placeholder="Correo">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="message" rows="3" placeholder="Mensaje"></textarea>
                    </div>
                    <button class="btn btn-primary col-2 pull-right">Enviar</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="item-list jscroll">
                @for($i=0;$i<=3;$i++)
                <div class="item d-flex flex-row flex-wrap">
                    <div class="col-6">
                        <h5>¿Qué medidas tiene el producto?</h5>
                    </div>
                    <div class="col-6">
                        <div class=" pull-right">
                            <a href="#" class="user-name">Nombre del usuario</a>
                        </div>
                    </div>
                    <div class="col-12 answer">
                        <p><b>R:</b>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p></div>
                </div>
                @endfor
                <a class="btn btn-default btn-block more-questions void" href="#">VER MÁS</a>
            </div>
        </div>
    </div>
</div>