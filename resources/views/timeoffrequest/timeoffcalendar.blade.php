@extends('adminlte::page')

@section('title', 'Time Off')

@section('content')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('calendar') }}
@endsection

@include('partials.flash-messages')
 <section class="content">



<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventTitle">Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>From:</strong> <span id="eventStart"></span></p>
                <p><strong>Through:</strong> <span id="eventEnd"></span></p>
                <p><strong>Notes:</strong> <span id="eventDescription"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




      <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
            <a href="{{ route('timeoff.requestform') }}"><button type="button" class="btn btn-warning text-nowrap"><i class="fas fa-plus mr-2"></i>New Leave Request</button></a>
        </div>
            @if(auth()->check() && auth()->user()->isAdmin())
            <div class="col-md-6">
                <a  href="{{ route('timeoff.adminrequest') }}"><button type="button" class="btn btn-primary text-nowrap"><i class="fas fa-plus mr-2"></i>Enter Leave For Employee</button></a>
            </div>
            @endif
        </div>
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
            eventBorderColor: 'black',
            {{-- allDay: true, --}}
            {{-- nextDayThreshold: '00:00:00', --}}
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

            var eventObj = info.event;

            // 1. Populate standard properties
            $('#eventTitle').text(eventObj.title + " On Leave");

            if(eventObj.allDay === true) {

                $('#eventStart').text(eventObj.start.toLocaleDateString());

            } else {

                let formattedStartTime = eventObj.start.toLocaleString([], {
                        dateStyle: 'short',
                        timeStyle: 'short'
                        });

                $('#eventStart').text(formattedStartTime);
            }

            
            
            if (eventObj.end) {

                if(eventObj.allDay === true) {

                    let nativeDate = new Date(eventObj.end);
                    nativeDate.setDate(nativeDate.getDate() - 1);

                    $('#eventEnd').text(nativeDate.toLocaleDateString());

                } else {

                    let formattedEndTime = eventObj.end.toLocaleString([], {
                        dateStyle: 'short',
                        timeStyle: 'short'
                        });

                    $('#eventEnd').text(formattedEndTime);

                }

            } else {
                $('#eventEnd').text('N/A');
            }

            // 2. Populate custom columns through extendedProps
            if (eventObj.extendedProps && eventObj.extendedProps.reason) {
                $('#eventDescription').text(eventObj.extendedProps.reason);
            } else {
                $('#eventDescription').text('No description provided.');
            }

            // 3. Open the Bootstrap 4 modal
            $('#eventModal').modal('show');



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