@extends('adminlte::page')

@section('title', 'AD Vendor Mapping') 

@section('content_header')
@include('partials.flash-messages')
    
@stop

@section('content')
 <div class="row justify-content-end">
    <div class="col-4">
        <h4>Vendor ID to AD ID Mapping</h4>                              
    </div>
    <div class="col-4 text-right">
        <a href="{{ route('admap.create') }}"><button type="button" class="btn btn-warning text-nowrap"><i class="fas fa-plus mr-2"></i>Add Vendor</button></a> 
    </div>

</div>

    @section('plugins.Datatables', true)

    <x-adminlte-datatable id="table3" :heads="$heads" :config="$config" with-buttons striped compact hoverable compressed/>
@stop