@extends('adminlte::page')

@section('title', 'AD Remittance Report') 

@section('content_header')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('remitpost') }}
@endsection

@include('partials.flash-messages')
    
@stop

@section('content')


<section class="content" style="margin-top: 5px;">
    <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="far fa-calendar"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Check Date</span>
                <span class="info-box-number">
                  {{ $reportdate }}
                </span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-receipt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Number Of Invoices</span>
                <span class="info-box-number">{{ $totalinvoices }}</span>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-md-up"></div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-check-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Invoice Amount</span>
                <span class="info-box-number">${{ number_format($totalremittance, 2, '.', ',') }}</span>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            @if($origin == 'Industrial, Safety and Construction')
                <a href="{{ route('remit.spreport', ['reportdate' => $reportdate]) }}">
            @elseif($origin == 'Service Provider Program')
                <a href="javascript:history.back()">
            @endif
            @if($origin == 'Industrial, Safety and Construction')
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="far fa-user-circle"></i></span>
              <div class="info-box-content">
                    <span class="info-box-text">Service Provider Report</span>
                    <span class="info-box-number"><small>Invoice Count</small> <span class="badge badge-warning ml-1">{{ $spcount }}
                    </span></span>
                </div>
            </div>

            @elseif($origin == 'Service Provider Program')
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-parachute-box"></i></span>
              <div class="info-box-content">
                    <span class="info-box-text">ISC Report</span>
                    <span class="info-box-number"><small>Invoice Count</small> <span class="badge badge-warning ml-1">{{ $spcount }}</span></span>
              </div>
            </div>
                @endif

        </a>
          </div>
     
        </div>

 <div class="row">
    @if($origin == 'Industrial, Safety and Construction')
        <form action="{{ route('remit.export') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="date" value="{{ $reportdate }}">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-file-export"></i>
                Export To AD ISC Upload File
            </button>
        </form>
    @elseif($origin == 'Service Provider Program')
        <form action="{{ route('remit.serviceprovider') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="date" value="{{ $reportdate }}">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-file-export"></i>
                Export To AD SP Upload File
            </button>
    @endif
</div>

@section('plugins.Datatables', true)

    <x-adminlte-card title="AD . {{ $origin }}" header-class="text-center" theme="secondary"> 
    <x-adminlte-datatable id="remit" class="with-buttons" :heads="$heads" :config="$config" striped compact with-buttons hoverable bordered compressed/>
    </x-adminlte-card>
@stop
</div>
</section>


