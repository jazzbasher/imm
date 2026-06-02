@extends('adminlte::page')

@section('title', 'TimeClock')

@section('content_header')
    <h1>Employee Time Clock</h1>
@stop

@section('content')
    <div class="flex flex-col items-center justify-center p-8 bg-gray-50 rounded-xl mb-6">
            <p class="text-sm text-gray-500 mb-2">Current Status:
            <span class="px-3 py-1 rounded-full text-sm font-semibold mb-6 {{ $currentAttendance ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"><b>
                {{ $currentAttendance ? 'Clocked In' : 'Clocked Out' }}
            </b></span></p>

            <form action="{{ route('attendance.toggle') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger px-8 py-4 text-white font-bold rounded-lg transition shadow-md {{ $currentAttendance ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }}">
                    {{ $currentAttendance ? 'Clock Out Now' : 'Clock In Now' }}
                </button>
            </form>
        </div>
        <div style="padding-bottom: 50px">
        </div>

        <!-- History Log -->
        {{-- <h3 class="text-lg font-semibold mb-4 text-gray-700">Your Recent Activity</h3> --}}
        <div class="overflow-x-auto">
            <table class="table">
                <thead class="thead-dark">
                    <tr class="border-b text-gray-600 uppercase text-xs">
                        <th class="py-2">Date</th>
                        <th class="py-2">DoW</th>
                        <th class="py-2">Clock In</th>
                        <th class="py-2">Clock Out</th>
                        <th class="py-2">Worked TIme</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @foreach ($history as $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">{{ \Carbon\Carbon::parse($log->clock_in)->toFormattedDateString() }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->clock_in)->dayName }}</td>
                            <td class="py-3 text-green-600">{{ \Carbon\Carbon::parse($log->clock_in)->format('g:i A') }}</td>
                            <td class="py-3 text-red-600">
                                {{ $log->clock_out ? \Carbon\Carbon::parse($log->clock_out)->format('g:i A') : 'Active' }}
                            </td>
                            @if(!empty($log->clock_out))
                            <td>{{ round(\Carbon\Carbon::parse($log->clock_in)->floatDiffInHours($log->clock_out), 2) }}</td>
                            @else
                            <td></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
   
@stop