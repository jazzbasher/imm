@extends('adminlte::page')

@section('title', 'PowerBI Item Sales History') 

@section('content_header')


@include('partials.flash-messages')
    
@stop

@section('content')
 <div class="row">
    <div class="col-4">
        <h3></h3>
        <h5>Customer ID: {{ $custid }}</h5>  
        <h5>Item ID: {{ $itemid }}</h5> 
        <br>
        <br>
        <h5>Total Invoices:  {{ $itemsales->invoicecnt }}</h5>
        <h5>Total Quantity Shipped: {{ (int) $itemsales->qtyshipped }}</h5>
        <h5>Total Price: ${{ number_format($itemsales->extendedprice, 2,'.', ',') }}</h5>                         
    </div>
</div>

    @section('plugins.Datatables', true)


@stop