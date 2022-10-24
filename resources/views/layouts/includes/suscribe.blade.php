@if(Auth::guard('customer-web')->check() == null || !Auth::guard('customer-web')->user()->suscribed())
    <div class="suscribe">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h4 class="title">Entérate de las mejores promociones de Justo a Tiempo aquí</h4>
                    <form id="frmSuscribe">
                        <div class="input-group">
                            <input type="text" name="email" class="form-control" placeholder="Sólo ingresa aquí tu correo electrónico" aria-label="Search" aria-describedby="menu-search">
                            <button type="submit" class="input-group-addon" id="menu-search">
                                ¡Suscríbete!
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif