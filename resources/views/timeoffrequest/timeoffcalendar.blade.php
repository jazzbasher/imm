@extends('adminlte::page')

@section('title', 'Time Off')

@section('content_header')
@stop

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Time Off Calendar</h4>
        </div>
        <div class="card-body">
            <!-- Calendar DOM Element -->
            <div id="calendar"></div>
        </div>
    </div>
</div>

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
             eventTimeFormat: { 
            hour: 'numeric',
            minute: '2-digit',
            meridiem: false // Shows 'am' or 'pm'
        },
            
            // Point FullCalendar to your Laravel backend endpoint
            events: '/api/events', 
            
            // Optional: Handle event click interactions (e.g., displaying a Bootstrap modal)
            eventClick: function(info) {
                alert('Event: ' + info.event.title);
                // You can trigger a Bootstrap $('#modalId').modal('show') here
            }
        });

        calendar.render();
    });
</script>

@stop

@section('css')
    <!-- FontAwesome CSS (Required for FullCalendar Bootstrap icons) -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

@stop

@section('js')
<script src="https://jquery.com"></script>
<script src="{{ asset('js/main.js') }}"></script>



@stop