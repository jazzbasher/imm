@extends('adminlte::page')

@section('title', 'Time Off')

@section('content')
@include('partials.flash-messages')
 <section class="content">
      <div class="card">
        <div class="card-header">
          {{-- <a type="button" href="{{ route('timeoff.requestform') }}" class="btn btn-tool" title="New Leave Request">
              <i class="fas fa-plus"></i> New Request
            </a> --}}
            <a href="{{ route('timeoff.requestform') }}"><button type="button" class="btn btn-warning text-nowrap"><i class="fas fa-plus mr-2"></i>New Leave Request</button></a>
          <div class="card-tools">
            
          </div>
        </div>
        <div class="card-body">
            <!-- Calendar DOM Element -->
            <div id="calendar"></div>
         </div>
      </div>
    </section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            // Apply the Bootstrap 4 Theme
            themeSystem: 'bootstrap', 
            
            // Configure the layout header
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            
            initialView: 'dayGridMonth',
            editable: false,
            displayEventTime: true, 
            displayEventEnd: true, 
            eventColor: '#ffc107',
            eventTextColor: 'black',
            eventTimeFormat: { 
                hour: 'numeric',
                minute: '2-digit',
                meridiem: false 
            },


            eventContent: function(arg) {
                // Check if the event has time and is not all-day
                if (arg.event.start && !arg.event.allDay) {
                    let timeText = arg.timeText; // Standard format might be "10:00 - 11:00"
      
                    // Remove spaces around the dash
                    let modifiedTime = timeText.replace(/\s*-\s*/g, '-');
      
                    return { html: '<div class="fc-event-time">' + modifiedTime + '</div><div class="fc-event-title">' + arg.event.title + '</div>' };
                }
            },
              
            events: '/api/events', 
            
            eventClick: function(info) {
            alert('Event: ' + info.event.title);
            // Bootstrap $('#modalId').modal('show') here
            }

        });

        calendar.render();

    });
</script>

@stop

@section('css')
    <!-- FontAwesome CSS (Required for FullCalendar Bootstrap icons) -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <style>
        .fc-event-time, .fc-event-title {
padding: 0 1px;
white-space: normal;
font-size: 12px !important;
font-weight: normal !important;
}
</style>

@stop

@section('js')
<script src="https://jquery.com"></script>
<script src="{{ asset('js/main.js') }}"></script>


@stop