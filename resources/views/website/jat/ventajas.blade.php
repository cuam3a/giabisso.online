@extends('layouts.jat.website')

@section('content')
<div class="">
        <img src="/img/ventajas.png" height="800" style="width:100%">
    </div>
@stop

@section('js')
<script>
 $('.collapse').on('show.bs.collapse', function () {
    $(this).siblings('.card-header').addClass('active');
  });

  $('.collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.card-header').removeClass('active');
  });
</script>
@stop