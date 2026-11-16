@extends('adminlte::page')

@section('title', 'PowerBI Item Sales History') 

@section('content_header')


@include('partials.flash-messages')
    
@stop

@section('content')

 <section class="content">
      <div class="card">
        <div class="card-header" style="height: 100px;">

              <div class="row flex-nowrap pt-4">
    <div class="col"><h5><small>Customer:</small> {{ $custid }}</h5></div>
    <div class="col"><h5><small>Item:</small> {{ $itemid }}</h5></div>
    <div class="col"><h5><small>UnitPrice:</small> ${{ number_format($firstitem->unit_price, 2) }}</h5></div>
    <div class="col"><h5><small>Total Bought:</small> {{ $totalqty }}</h5></div>
    <div class="col"><h5><small>Total Paid:</small> ${{ $totalprice }}</h5></div>
  </div>

          <div class="card-tools">
      
          </div>
        </div>
        <div class="card-body p-0">

             <table class="table table-striped border-secondary">
              <thead>
                  <tr>
                      <th style="width: 30%">
                          Invoice Number
                      </th>
                      <th style="width: 30%">
                          Invoice Date
                      </th>
                      <th style="width: 20%">
                          Qty Shipped
                      </th>
                      <th  style="width: 20%">
                          Price
                      </th>
  
                  </tr>
              </thead>
              <tbody>
@foreach($itemsales as $sales)

                 <tr>
                      <td>
                          {{ $sales->invoice_no }}
                      </td>
                      <td>
                         {{ $sales->invoice_date }}
                      </td>
                      <td>
                          {{ (int) $sales->qty_shipped }}
                      </td>
                      <td>
                          {{ number_format($sales->extended_price, 2) }}
                      </td>

               </tr>                
              @endforeach                
              </tbody>
          </table>
        </div>
      </div>
    </section>










{{--  <div class="row">
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
</div> --}}

    {{-- @section('plugins.Datatables', true) --}}


@stop
@section('css')
<style>
    /* Change odd row color */
.table-striped tbody tr:nth-of-type(odd) {
  background-color: #3d444a;
}
</style>
@stop




