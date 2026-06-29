@extends('adminlte::page')

@section('title', 'Pending Leave Requests')

@section('content')
@include('partials.flash-messages')

 <section class="content">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Pending Leave Requests</h3>
          <div class="card-tools">
           {{--  <a type="button" href="{{ route('bidtracker.create') }}" class="btn btn-tool" title="Add Bid">
              <i class="fas fa-plus"></i> Create Project
            </a> --}}
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th>
                          Submitted By
                      </th>
                      <th>
                      	  Request Type
                      </th>
                      <th>
                          Start
                      </th>
                      <th>
                          End
                      </th>
                      <th>
                          Total Hours
                      </th>
                      <th>
                          Notes
                      </th>
                      <th>
                          
                      </th>
                  </tr>
              </thead>
              <tbody>
              @foreach($requests as $request)
                  <tr>
                      <td>
                           {{ $request->user->name }}      
                      </td>
                      <td>
                           {{ $request->requesttype->type }}      
                      </td>
                      <td>
                      	@if($request->allDay == 1)
                            {{ \Carbon\Carbon::make($request->start)?->format('m/d/y') ?? 'N/A' }}
                         @elseif($request->allDay == 0)
                            {{ \Carbon\Carbon::make($request->start)?->format('m/d/y h:i A') ?? 'N/A' }}
                        @endif
                      </td>
                      <td>
                          @if($request->allDay == 1)
                            {{ \Carbon\Carbon::make($request->end)?->format('m/d/y') ?? 'N/A' }}
                         @elseif($request->allDay == 0)
                            {{ \Carbon\Carbon::make($request->end)?->format('m/d/y h:i A') ?? 'N/A' }}
                        @endif
                      </td>
                      <td>
                      	@if($request->allDay == 1)
                          {{ ((\Carbon\Carbon::parse($request->start)->diffInDays(\Carbon\Carbon::parse($request->end)) + 1) * 8) }}
                        @elseif($request->allDay == 0)
                        {{ \Carbon\Carbon::parse($request->start)->diffInHours(\Carbon\Carbon::parse($request->end)) }}
                        @endif
                      </td>
                       <td>
                          {{ $request->reason }}
                      </td>
                      <td class="d-flex gap-2">
                    <!-- Approve Button -->
                    <form action="{{ route('request.approve', $request->id) }}"class ="pr-3" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="1"/>
                        <button type="submit" class="btn btn-success btn-sm" title="Approve">
                            <i class="fas fa-check-square"></i> 
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <form action="{{ route('request.reject', $request->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="2"/>
                        <button type="submit" class="btn btn-danger btn-sm" title="Reject">
                            <i class="fas fa-window-close"></i>
                        </button>
                    </form>
                </td>
                  </tr>                
                  @endforeach              
              </tbody>
          </table>
        </div>
      </div>
    </section>

@stop

@section('css')
@stop

@section('js')
@stop