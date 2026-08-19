@extends('adminlte::page')

@section('title', 'Vendors Logins') 

@section('content_header')
@include('partials.flash-messages')
    
@stop

@section('content')
 <div class="row justify-content-center">
    <div class="col-3">
        <h4>Vendors Login Credentials</h4>                              
    </div>
    {{-- <div class="col-4 text-right">
        <a href="{{ route('admap.create') }}"><button type="button" class="btn btn-warning text-nowrap"><i class="fas fa-plus mr-2"></i>Add Vendor</button></a> 
    </div> --}}

</div>

    @section('plugins.Datatables', true)

    <x-adminlte-datatable id="table5" :heads="$heads" :config="$config" striped compact hoverable compressed/>
@stop