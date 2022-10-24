<div class="list-forum">
    <div class="container">


        <div class="row">
            <div class="col-12">
                <div class="title">
                    <h4> <b> Realiza una pregunta al vendedor </b> </h4>
                </div>
                <form id="form" action="{{ route('add-question-product') }}" method="post">

                    {{ csrf_field() }}

                    <input type="hidden" name="id" id="id" value="@if(Auth::guard('customer-web')->check()) {{ Auth::guard('customer-web')->user()->id }} @else {{0}}@endif">

                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Nombre" value="@if(Auth::guard('customer-web')->check()) {{ Auth::guard('customer-web')->user()->fullname() }} @endif" required>
                        </div>
                        <div class="form-group col-md-6">
                        <input type="email" name="email" class="form-control" placeholder="Correo" value="@if(Auth::guard('customer-web')->check()) {{ Auth::guard('customer-web')->user()->email }} @endif" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="question" rows="3" placeholder="Pregunta" required></textarea>
                    </div>
                    <div class="form-group text-right">
                        <small>Ni tu nombre ni tu correo electronico <b> serán publicados en el listado </b> </small>
                    </div>
                    <button class="btn btn-primary col-2 pull-right" id="btn-question">Hacer pregunta</button>
                </form>
            </div>
        </div>




        <div class="row">
            <div class="col-12">
                <br>
            </div>
        </div>

        <div class="row">
            <div class="col-12">

            <div class="title">
                <h4> <b> Preguntas sobre este producto </b> </h4>
            </div>
                <div class="item-list" style="max-height: 400px !important; overflow-y: scroll; ">

                    @if ( $product->questionsAnswersActivas->count() > 0 )
                        @foreach($product->questionsAnswersActivas as $question)
                            <div class="item d-flex flex-row flex-wrap">
                                
                                <div class="col-12">
                                    <p> <small style="color: #4CAF50;"> [ {{ date_format($question->created_at,'Y-m-d') }} ] </small> {{ $question->question }} : </p>
                                    <p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                                          @if ($question->answer != null ) <b> R : {{ $question->answer }} @endif </b> 
                                      </p>
                                </div>
                            </div>
                        @endforeach
                    @else 
                        <h5 class="text-center">Aún no hay preguntas para este producto </h5>
                    @endif
                </div>
            </div>
        </div>


    </div>
</div>