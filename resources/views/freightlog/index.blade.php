@extends('adminlte::page')

@section('title', 'Freight Log') 

@section('content_header')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('freightlog') }}
@endsection

@include('partials.flash-messages')
    
@stop

@section('content')
 <div class="row pb-5">
    <div class="col-4">
        <a href="{{ route('freightlog.create') }}"><button type="button" class="btn btn-warning text-nowrap"><i class="fas fa-plus mr-2"></i>New Entry</button></a>                                    
    </div>
    <div class="col-4">
            <h4>Log Entries for {{ $thismonth }}</h4>
    </div>
    <div class = "col-4">
        @if($viewparam == 1)
            <a href="{{ route('freightlog.lastmonth') }}" class ="d-inline-block mt-2" style="color: #ffc107;"><< Previous Month</a> 
        @else
            <a href="{{ route('freightlog') }}" class ="d-inline-block mt-2" style="color: #ffc107;">Current Month >></a> 
        @endif
    </div>
</div>

    @section('plugins.Datatables', true)

    <x-adminlte-datatable id="table1" class="with-buttons" :heads="$heads" :config="$config" striped compact with-buttons hoverable bordered compressed/>
@stop