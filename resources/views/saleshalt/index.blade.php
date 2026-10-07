@extends('adminlte::page')

@section('title', 'Sales Halt') 

@section('content_header')


@include('partials.flash-messages')
    
@stop

@section('content')
 <div class="row pb-5">
    <div class="col-4">
                                          
    </div>
    <div class="col-4">
            <h4>Sales Halt</h4>
    </div>

</div>

    @section('plugins.Datatables', true)

    <x-adminlte-datatable id="table1" class="with-buttons" :heads="$heads" :config="$config" striped compact with-buttons hoverable bordered compressed/>
@stop