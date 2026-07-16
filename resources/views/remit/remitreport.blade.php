@extends('adminlte::page')

@section('title', 'AD Remittance Report') 

@section('content_header')
@include('partials.flash-messages')
    
@stop

@section('content')
 <div class="row">

    <div class="col-4">
        
            <h4>AD Remittance Report for {{ $reportdate }}</h4>
    
    </div>

</div>

@section('plugins.Datatables', true)

    <x-adminlte-datatable id="remit" class="with-buttons" :heads="$heads" :config="$config" striped compact with-buttons hoverable bordered compressed/>
@stop


