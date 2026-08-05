@extends('adminlte::page')

@section('title', 'Deena Report') 

@section('content_header')
@include('partials.flash-messages')
    
@stop

@section('content')
 <div class="row text-center">
    <div class="col-12">
        <h4>Because Deena couldn't possibly be higher maintenance</h4>                              
    </div>

</div>

    @section('plugins.Datatables', true)

    <x-adminlte-datatable id="table4" :heads="$heads" :config="$config" with-buttons striped compact hoverable compressed/>
@stop