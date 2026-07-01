@extends('adminlte::page')

@section('title', 'Freight Log') 

@section('content_header')
@include('partials.flash-messages')
    
@stop

@section('content')
 <div class="row">
    <div class="col-4">
        <a href="{{ route('freightlog.create') }}"><button type="button" class="btn btn-warning text-nowrap"><i class="fas fa-plus mr-2"></i>New Entry</button></a>                                    
    </div>
    <div class="col-4">
        @if($viewparam == 1)
            <h4>Log Entries for {{ \Carbon\Carbon::parse($currentpayperiod['start_date'])->format('m/d/y') }} - {{ \Carbon\Carbon::parse($currentpayperiod['end_date'])->format('m/d/y') }}</h4>
        @else
            <h4>Log Entries for {{ \Carbon\Carbon::parse($previouspayperiod['start_date'])->format('m/d/y') }} - {{ \Carbon\Carbon::parse($previouspayperiod['end_date'])->format('m/d/y') }}</h4>  
        @endif   
    </div>
    <div class = "col-4">
        @if($viewparam == 1)
            <a href="{{ route('freightlog.lastmonth') }}" class ="d-inline-block mt-2" style="color: #ffc107;"><< Previous PayPeriod</a> 
        @else
            <a href="{{ route('freightlog') }}" class ="d-inline-block mt-2" style="color: #ffc107;">Current PayPeriod >></a> 
        @endif
    </div>
</div>

    @section('plugins.Datatables', true)

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped compact hoverable bordered compressed/>
@stop