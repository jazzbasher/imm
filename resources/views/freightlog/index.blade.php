@extends('adminlte::page')

@section('title', 'Freight Log') 

@section('content_header')
@include('partials.flash-messages')
    <a href="{{ route('freightlog.create') }}"><button type="button" class="btn btn-warning text-nowrap"><i class="fas fa-plus mr-2"></i>New Entry</button></a>
@stop

@section('content')
    {{-- Activate the plugin for this specific view --}}
    @section('plugins.Datatables', true)

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped compact hoverable bordered compressed/>
@stop