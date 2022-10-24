<div class="mobile">
    <div class="col-xs-12 filters-div sticky" style="display:flex">
        <a class="btn btn-primary d-flex filter-button btnMobileOrder">Ordenar Por <i class="fa fa-chevron-down"></i></a>
        <a class="btn btn-primary d-flex filter-button btnMobileFilters">Precio <i class="fa fa-chevron-down"></i></a>
    </div>
</div>

<div class="products-menu">
    <div class="section mobile filter-options" id="filter-options" style="visibility:hidden">
        <div class="title"><a class="btn btn-xs" id="btn-close-filter-options" onclick="closeFilter('filter-options')"><i class="fa fa-times"></i></a> Precio</div>
            <div class="content">
                <div class="form-row">                                
                    <div class="form-group col-md-6">
                        <label for="min-price">Desde</label>
                        <input class="form-control" id="mob-minprice" type="number" min="0" value="{{@$_GET['minprice']}}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="max-price" >Hasta</label>
                        <input  class="form-control" id="mob-maxprice" type="number" min="0" value="{{@$_GET['maxprice']}}">
                    </div>
                </div>
            </div>
            <button id="btnFilter" data-is-mobile="true" class="btnFilterMob btnFilter btn btn-block btn-primary d-flex justify-content-between"><span>Filtrar</span><span class="fa fa-search" style="margin-top:5px;"></span></button>
        </div>
    </div>
</div>
<div class="products-menu">
    <div class="section mobile order-options" id="order-options" style="visibility:hidden">
        <div class="title"> <a class="btn btn-xs" id="btn-close-order-options" onclick="closeFilter('order-options')"> <i class="fa fa-times"></i></a>Ordenar por:</div>
            <div class="content">
                <div class="form-row">                                
                    <select class="form-control sltOrderProducts" placeholder="Ordenar por:">
                        @foreach($filterOrderBy as $value => $text)
                            <option value="{{$value}}" @if(isset($_GET['orderby']) && $_GET['orderby'] == $value) selected @endif>{{$text}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function closeFilter(id){
        document.getElementById(id).style.visibility = "hidden";
    }
</script>