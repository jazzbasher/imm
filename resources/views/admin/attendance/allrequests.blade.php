
@extends('adminlte::page')

@section('title', 'PayPeriod User Report')

@section('plugins.Datatables', true)

@section('content')
@include('partials.flash-messages')


@if($requests->isNotEmpty())
    @php
    $calheads = [
        'Name',
        'Start',
        'End',
        'Ttl Hours',
        'Type',
        'Notes',
        ['label' => 'Edit', 'no-export' => true, 'width' => 5],
    ];

    $calconfig = [
        'order' => [[1, 'asc']],
        'lengthChange' => false,
        'paging' => false,
        'info'  => false,
        'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    ];
    @endphp

    <div class="card border border-dark p-2 m-1" style="background-color: #343C45; border-style: solid;">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <h4><h4><i class="far fa-fw fa-calendar-alt text-info pr-1"></i> All Approved Future Requests </h4>
                </div>
                <div class="col-9">
                    <h5 class="text-secondary"> something here</h5>
                </div>
            </div>
            <x-adminlte-datatable id="table2" :heads="$calheads" :config="$calconfig" striped hoverable bordered compressed>
                @foreach($requests as $request)
                    <tr>
                        <td>
                            {{ $request->user->name }}
                        </td>
                        <td>
                            {{ $request->start ? \Carbon\Carbon::parse($request->start)->format('m/d D') : '' }}
                        </td>
                        <td>
                            {{ $request->end ? \Carbon\Carbon::parse($request->end)->format('m/d D') : '' }}
                        </td>
                       
                      <td>
                        @if($request->allDay == 1)
                          {{ ((\Carbon\Carbon::parse($request->start)->diffInDays(\Carbon\Carbon::parse($request->end)) + 1) * 8) }}
                        @elseif($request->allDay == 0)
                        {{ \Carbon\Carbon::parse($request->start)->diffInHours(\Carbon\Carbon::parse($request->end)) }}
                        @endif
                        </td>
                        <td>
                             @if($request->type === 1)
                                Vacation
                             @elseif($request->type === 2)
                                Illness
                             @else
                                {{ $request->type }}
                             @endif
                        </td>
                        <td>
                            {{ $request->reason }}
                        </td>
                        <td>
                            <a class="text-decoration-none" href="{{ route('calendar.details', ['id' => $request->id, 'period' => 'current', 'user' => $request->user->name ]) }}"> 
                              <i class="fas fa-pen text-info"></i></a>
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