@extends('adminlte::page')

@section('title', 'Freight Charge Report')

@section('plugins.Datatables', true)

@section('content')
@include('partials.flash-messages')

<section class="content">
    <div class="container-fluid">
      <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-tabs-one-smallengine-tab" data-toggle="pill" href="#custom-tabs-one-smallengine" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Last Payperiod</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-one-powered-tab" data-toggle="pill" href="#custom-tabs-one-powered" role="tab" aria-controls="custom-tabs-one-powered" aria-selected="false">Itemized Line Charge
              </a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-one-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-one-smallengine" role="tabpanel" aria-labelledby="custom-tabs-one-smallengine-tab">  
            	<div class="card mb-4">
                <div class="card-header">
                  <h3 class="card-title">Freight Charges By Rep <small class="text-warning">{{ \Carbon\Carbon::parse($previouspayperiod['start_date'])->format('m/d/y') }} - {{ \Carbon\Carbon::parse($previouspayperiod['end_date'])->format('m/d/y') }}</small></h3>
                </div>
                <div class="card-body p-0">
                  <table class="table table-striped" role="table">
                    <thead>
                      <tr>
                        <th scope="col">Rep</th>
                        <th scope="col">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($logs as $log)
                        <tr class="align-middle">
                          <td>
                              {{ $log->outsidesales->name }}
                          </td>
                          <td>
                          		$ {{ $log->total }}
                          </td>
                        </tr>
                      @endforeach                 
                    </tbody>
                  </table>
                </div> 
              </div>
         	  </div>
            <div class="tab-pane fade" id="custom-tabs-one-powered" role="tabpanel" aria-labelledby="custom-tabs-one-powered-tab">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    @php
                    $heads = [
                        'Date',
                        'Customer ID',
                        'Buyer',
                        'PO',
                        'Amount',
                        'Order #',
                        'Note'
                    ];

                    $config = [
                        'order' => [[0, 'asc']],
                        'destroy' => true,
                        'searching' => false,
                        'lengthChange' => false,
                        'paging' => false,
                        'info'  => false,
                        'columns' => [null, null, null, null, null, null, null],
                    ];
                    @endphp                  
                            	
                    <table class="table">
                      <tbody>
                        @foreach($keyed as $key => $pa)     
                          <tr class="accordion-toggle" data-toggle="collapse" data-target="#child-{{ str_replace(' ', '', $key) }}">                                 
                            <td class="bla"><i class="fas fa-plus"></i></td>
                            <td class="bla"><H3>{{ $key }}</H3></td>
                          </tr>

                          <!-- Nested Row (Accordion) -->
                          <tr>
                            <td colspan="2" class="p-0">
                              <div class="collapse card" id="child-{{ str_replace(' ', '', $key) }}">
                                <x-adminlte-datatable id="tabl" :heads="$heads" :config="$config" striped hoverable bordered compressed>                           
                                     @foreach($pa as $p)
                                        <tr>
                                          <td>{{ \Carbon\Carbon::parse($p->date)->format('m/d/y') }}</td>  
                                          <td>{{ $p->customer_id }}</td>                          
                                          <td>{{ ucwords(strtolower($p->buyer)) }}</td>                                       
                                          <td>{{ $p->po }}</td>
                                          <td>{{ $p->amount }} </td>
                                          <td>{{ $p->order_no }}</td>
                                          <td>{{ $p->notes }}</td>
                                        </tr>
                                      @endforeach
                                </x-adminlte-datatable>
                              </div>                   
                            </td>
                          </tr>

                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>         
              </div>
            </div>
          </div>   
        </div>
      </div>
    </div>  
  </section>

@stop

@section('css')
@stop

@section('js')
<script>
$(document).ready(function() {
    // When the collapsible element starts showing
    $('.collapse').on('show.bs.collapse', function () {
        $(this).closest('tr').prev('.accordion-toggle')
               .find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
               $(this).closest('tr').prev('.accordion-toggle')
               .find(".bla").removeClass("bla").addClass("yel");

    });

    // When the collapsible element starts hiding
    $('.collapse').on('hide.bs.collapse', function () {
        $(this).closest('tr').prev('.accordion-toggle')
               .find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
               $(this).closest('tr').prev('.accordion-toggle')
               .find(".yel").removeClass("yel").addClass("bla");
               
    });
});
</script>


@stop