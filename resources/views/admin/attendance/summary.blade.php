@extends('adminlte::page')

@section('title', 'PayPeriod Attendance Summary')

@section('plugins.Datatables', true)

@section('content_top_nav_right')
            {{ Breadcrumbs::render('payperiodsummary', $period) }}
@endsection

@section('content')
@include('partials.flash-messages')

    @php
    $heads = [
        'Name',
        'Clocked Net Hours',
        'Leave Hours Used',
        'Discrepancies',
        ['label' => 'View', 'no-export' => true, 'width' => 5],
    ];

    $config = [
        'order' => [[0, 'asc']],
        'lengthChange' => false,
        'paging' => false,
        'columns' => [null, null, null, null, ['orderable' => false]],
    ];
    @endphp

    <div class="card border border-dark p-2" style="background-color: #343C45; border-style: solid;">
        <div class="card-body">
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable bordered compressed>
                @foreach($merged as $k => $times)
                    <tr>
                        <td>
                            {{ $k }}
                        </td>
                        <td>
                            @isset($times["nethours"])
                                {{ sprintf('%02d:%02d', floor($times["nethours"]), floor(($times["nethours"] - floor($times["nethours"])) * 60)) }}                             
                            @else
                                0
                            @endisset
                        </td>
                        <td>
                            @isset($times["net_calendarhours"])
                                {{ $times["net_calendarhours"] }}
                            @else
                                0
                            @endisset
                        </td> 
                        <td class="text-center">
                            @if (($times["net_longclocks"] ?? 0) + ($times["net_clockdups"] ?? 0) > 0)
                                <i class="fas fa-check text-danger"></i>
                            @else
                            
                            @endif
                        </td>                                                                                       
                        <td class="text-center">                                                      
                            <a class="text-decoration-none" href="{{ route('attendance.details', ['period' => $period, 'id' => $k]) }}"> 
                            <i class="far fa-eye" style="color: #778CF7;"></i></a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop