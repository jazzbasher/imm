@extends('adminlte::page')

@section('title', 'PayPeriod User Report')

@section('plugins.Datatables', true)

@section('content')
@include('partials.flash-messages')


@if($usertimeclock->isNotEmpty())

     @php
    // Define table headers
    $heads = [
        'Date',
        'Clock In',
        'Lunch In',
        'Lunch Out',
        'Clock Out',
        'Total Lunch',
        'Net Hours',
        ['label' => 'View', 'no-export' => true, 'width' => 5],
    ];

    // Optional JQuery configurations passed into the plugin
    $config = [
        'order' => [[0, 'asc']],
        'lengthChange' => false,
        'paging' => false,
        'info'  => false,
        'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    ];
    @endphp


    {{-- Render Component --}}
    <div class="card border border-dark p-2 m-1" style="background-color: #343C45; border-style: solid;">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <h4> {{ $username }} </h4>
                </div>
                <div class="col-3">
                    <small>{{ $userlunchdesc }} </small>
                </div>
                <div class="col-6">
                    <h5 class="text-secondary"> Punches for PayPeriod {{ $periodstart }} - {{ $periodend }}</h5>
                </div>
            </div>
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable bordered compressed>
                 @foreach($usertimeclock as $clock)
                    <tr>
                        

                        <td>

                            {{ $clock->clock_in ? \Carbon\Carbon::parse($clock->clock_in)->format('m/d D') : '' }}


                            
                        </td>
                        <td>
                            {{ $clock->clock_in ? \Carbon\Carbon::parse($clock->clock_in)->format('g:i a') : '' }}
                        </td>
                        
                        <td>
                           @if($userlunchcode === 3)
                           {{ $clock->lunch_in ? \Carbon\Carbon::parse($clock->lunch_in)->format('g:i a') : '' }}
                           @else
                                Exempt
                            @endif
                        </td> 
                        <td>
                            @if($userlunchcode === 3)
                            {{ $clock->lunch_out ? \Carbon\Carbon::parse($clock->lunch_out)->format('g:i a') : '' }}
                            @else
                                Exempt
                            @endif
                        </td> 

                            @if (\Carbon\Carbon::parse($clock->clock_in)->isSameDay(\Carbon\Carbon::parse($clock->clock_out)))
                        <td>
                            {{ $clock->clock_out ? \Carbon\Carbon::parse($clock->clock_out)->format('g:i a') : '' }}
                            @else
                                <td style="background-color: #47161e;">
                            {{ $clock->clock_out ? \Carbon\Carbon::parse($clock->clock_out)->format('m/d g:i a') : '' }}
                            @endif
                            
                        </td>
                        <td>

                            {{ $clock->lunchtotal * 60 }}
                            {{-- @if($userlunchcode === 3)
                            {{ $clock->lunch_in && $clock->lunch_out ? \Carbon\Carbon::parse($clock->lunch_in)->diffInMinutes( \Carbon\Carbon::parse($clock->lunch_out)) : '' }} 
                            @else
                            'NA'
                            @endif --}}
                        </td> 
                        
                            @if($clock->nethours >= 9.5 )
                            <td style="background-color: #47161e;">
                            @else
                            <td>
                            @endif
                            {{ sprintf('%02d:%02d', floor($clock["nethours"]), round(($clock["nethours"] - floor($clock["nethours"])) * 60)) }}

                        </td>                                                              
                        
                        <td>                          
                            
                            <a class="text-decoration-none" href="{{ route('clockevent.details', ['id' => $clock->id]) }}"> 
                              <i class="far fa-eye" style="color: #BB86FC;"></i></a>

                        </td>
                    </tr>
              @endforeach

            </x-adminlte-datatable>
             @if($clockindups->isNotEmpty())
              @foreach($clockindups as $dup)
                <ul style="background-color: #47161e;">
                Multiple clock ins on {{ \Carbon\Carbon::parse($dup->date)->format('m/d/Y') }}
            </ul>
              @endforeach
              @endif
        </div>
    </div>
@else
    No Time Clock Data
@endif

@if($usercalendarhours->isNotEmpty())
    @php
    $calheads = [
        'Start',
        'End',
        'Ttl Hours',
        'Type',
        'Notes',
        ['label' => 'View', 'no-export' => true, 'width' => 5],
    ];

    $calconfig = [
        'order' => [[0, 'asc']],
        'lengthChange' => false,
        'paging' => false,
        'info'  => false,
        'columns' => [null, null, null, null, null, ['orderable' => false]],
    ];
    @endphp

     <div class="card border border-dark p-2 m-1" style="background-color: #343C45; border-style: solid;">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <h4> {{ $username }} </h4>
                </div>
                <div class="col-9">
                    <h5 class="text-secondary"> Leave Requests for PayPeriod {{ $periodstart }} - {{ $periodend }}</h5>
                </div>
            </div>
            <x-adminlte-datatable id="table2" :heads="$calheads" :config="$calconfig" striped hoverable bordered compressed>
                 @foreach($usercalendarhours as $calendar)
                    <tr>
                        <td>
                            {{ $calendar->start ? \Carbon\Carbon::parse($calendar->start)->format('m/d D') : '' }}
                        </td>
                        <td>
                            {{ $calendar->end ? \Carbon\Carbon::parse($calendar->end)->format('m/d D') : '' }}
                        </td>
                        <td>
                            {{ $calendar->cldrhours }}
                        </td>
                        <td>
                             @if($calendar->type === 1)
                                Vacation
                             @elseif($calendar->type === 2)
                                Illness
                             @else
                                {{ $calendar->type }}
                             @endif
                        </td>
                        <td>
                            {{ $calendar->reason }}
                        </td>
                        <td>
                            0
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>


@else
    No Calendar Data
@endif




@stop

@section('css')
@stop

@section('js')
@stop