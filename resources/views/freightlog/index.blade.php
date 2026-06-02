@extends('adminlte::page')

@section('title', 'Freight Log')

@section('content_header')
    <h1>Freight Log</h1>
@stop

@section('content')
    {{-- Activate the plugin for this specific view --}}
    @section('plugins.Datatables', true)

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped compact hoverable bordered compressed/>
@stop