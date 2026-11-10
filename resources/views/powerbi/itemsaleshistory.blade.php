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
        <h6>First Invoice Date: <span class="badge bg-warning">{{ $itemsales->mindate }}</span></h6>
        <h6>Last Invoice Date: <span class="badge bg-warning">{{ $itemsales->maxdate }}</span></h6>
        <br>
        <br>
        <h5><small>Total Invoices: </small><span class="badge bg-warning"> {{ $itemsales->invoicecnt }}</span></h5>
        <h5><small>Total Quantity Shipped: </small><span class="badge bg-warning"> {{ (int) $itemsales->qtyshipped }}</span></h5>
        <h5><small>Total Price: </small><span class="badge bg-warning"> ${{ number_format($itemsales->extendedprice, 2,'.', ',') }}</span></h5>                         
    </div>
</div>

    @section('plugins.Datatables', true)


@stop