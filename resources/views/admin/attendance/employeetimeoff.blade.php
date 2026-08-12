@extends('adminlte::page')

@section('title', 'Admin Employee Time Off')

@section('content')
@include('partials.flash-messages')

<section class="content" style="margin-top: 5px;">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12">
            <div class="container h-100 mt-5">
                <div class="row h-100 justify-content-center align-items-center">
                  <div class="col-10 col-md-8 col-lg-6">
                    <h3>Admin Portal | Enter Leave For Employee</h3>
        
                    <form action="{{ route('adminrequest.store') }}" method="POST" class="mt-5">
                        @csrf

                        <div class="form-group">
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $key => $emp)
                                    <option value="{{ $key }}" @selected(old('user_id') == $key)>
                                        {{ $emp }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Select Duration</label>
                            <div class="form-check">
                                <input type="radio" 
                                       id="allday" 
                                       name="allDay" 
                                       value="1" 
                                       class="time-toggle"
                                       {{ old('allday', $allDay ?? 'active') === 'active' ? 'checked' : '' }}>
                                <label for="allday">All Day (or mutliple days)</label>
                            </div>

                            <div class="form-check">
                                <input type="radio" 
                                       id="partial" 
                                       name="allDay" 
                                       value="0" 
                                       class="time-toggle"
                                       {{ old('allday', $allDay ?? 'active') === 'inactive' ? 'checked' : '' }}>
                                <label for="partial">Partial (hourly)</label>
                            </div>
                        </div>

                        <div class="form-group" id="partialstart" style="display: none;">
                            <label for="title">Date</label>
                            <input type="date" class="form-control" id="partialstartdata" name="partialstartdata" value="{{ old('partialstartdata') }}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                        </div>

                        <div class="form-group" id="alldaystart">
                            <label for="title">Start Date</label>
                            <input type="date" class="form-control" id="start" name="start" onchange="calculateWorkData()" value="{{ old('start') }}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                        </div>

                        <div id="starttime" style="display: none; text-align: right;">
                            <label for="starttime">Start Time</label>
                            <input type="time" name="starttime"  id="eventtime">
                        </div>

                        <div class="form-group" id="alldayend">
                            <label for="title">End Date</label>
                            <input type="date" class="form-control" id="end" name="end" onchange="calculateWorkData()" value="{{ old('end') }}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                        </div>

                        <div id="endtime" style="display: none; text-align: right;">
                            <label for="endtime">End Time</label>
                            <input type="time" name="endtime"  id="eventtimetwo">
                        </div>


                        <!-- Output: Total Work Days -->
                        <div class="form-group" id="firstday">
                            <label for="total_days">Total Requested Days:</label>
                            <input type="text" id="total_days" name="total_days" class="form-control" readonly>
                        </div>

                        <!-- Output: Total Work Hours -->
                        <div class="form-group" id="firsthours">
                            <label for="total_hours">Total Requested Hours (8 hrs/day):</label>
                            <input type="text" id="total_hours" name="total_hours" class="form-control" readonly>
                        </div>

                        <!-- Output: second hours -->
                        <div class="form-group" style="display: none;" id="secondhours">
                            <label for="second_hours">Total Requested Hours:</label>
                            <input type="text" id="second_hours" name="second_hours" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="title">Type</label>
                            	<select name="type"  class="form-control">
                                <option value="1" @selected(old('type') === '1')>Vacation</option>
                                <option value="2" @selected(old('type') === '2')>Illness</option>
                            </select>
                         </div>

                        <div class="form-group">
                                <label for="title">Notes (Optional with 50 character limit)</label>
                                 <textarea class="form-control" id="reason" name="reason" maxlength="50" rows="2" placeholder="Short and sweet note here..." autocomplete="off">{{ old('reason') }}</textarea>
                                      {{-- <input type="text" class="form-control" id="comments" name="comments" autocomplete="off"> --}}
                        </div>
                        <input type="hidden" name="manager_id" value="{{ auth()->id() }}">
                            <br>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add To Calendar</button>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </div>
    </div>
</section>

@stop

@section('css')
@stop

@section('js')
<script>
    document.querySelectorAll('.time-toggle').forEach(radio => {
        radio.addEventListener('change', function() {
            const detailsSection = document.getElementById('starttime');
            const detailsS = document.getElementById('endtime');
            const firstDays = document.getElementById('firstday');
            const firstHour = document.getElementById('firsthours');
            const secondHour = document.getElementById('secondhours');
            const secondhours = document.getElementById('second_hours');
            const eventtime = document.getElementById('eventtime');
            const eventtimetwo = document.getElementById('eventtimetwo');
            const partialstart = document.getElementById('partialstart');
            const partialstartdata = document.getElementById('partialstartdata');
            const alldaystart = document.getElementById('alldaystart');
            const alldayend = document.getElementById('alldayend');
            const primarystart = document.getElementById('start');
            const primaryend = document.getElementById('end');
            const totalDays = document.getElementById('total_days');
            const totalHours = document.getElementById('total_hours');

            if (this.value === '0') {
                detailsSection.style.display = 'block';
                detailsS.style.display = 'block';
                secondHour.style.display = 'block';
                partialstart.style.display = 'block';
                firstDays.style.display = 'none';
                firstHour.style.display = 'none';
                alldaystart.style.display = 'none';
                alldayend.style.display = 'none';
            } else {
                detailsSection.style.display = 'none';
                detailsS.style.display = 'none';
                secondHour.style.display = 'none';
                partialstart.style.display = 'none';
                firstDays.style.display = 'block';
                firstHour.style.display = 'block';
                alldaystart.style.display = 'block';
                alldayend.style.display = 'block';
               
                eventtime.value = ''; // Clear input if hidden
                eventtimetwo.value = ''; // Clear input if hidden
                secondhours.value = ''; // Clear input if hidden
                partialstartdata.value = '';
                primarystart.value = '';
                primaryend.value = '';
                totalDays.value = '';
                totalHours.value = '';
            }


        });
    });
</script>
<script>
  function calculateWorkData() {
    const startDateVal = document.getElementById('start').value;
    const endDateVal = document.getElementById('end').value;

    // Exit if one of the dates is not filled
    if (!startDateVal || !endDateVal) return;

    let start = new Date(startDateVal);
    let end = new Date(endDateVal);

    // Swap dates if start is later than end to avoid negative values

   {{--  if (start > end) {
        let temp = start;
        start = end;
        end = temp;
    } --}}

    let workDays = 0;
    
    // Loop through the days and count only weekdays (Mon-Fri)
    let currentDate = new Date(start);
    while (currentDate <= end) {
        const dayOfWeek = currentDate.getDay();
        // 5 is saturday, 6 is sunday 
        if (dayOfWeek !== 5 && dayOfWeek !== 6) {
            workDays++;
        }
        // Move to the next day
        currentDate.setDate(currentDate.getDate() + 1);
    }

    // Multiply workdays by 8 hours per day
    const totalHours = workDays * 8;

    // Update the form fields
    document.getElementById('total_days').value = workDays;
    document.getElementById('total_hours').value = totalHours;
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const startTimeInput = document.getElementById('eventtime');
    const endTimeInput = document.getElementById('eventtimetwo');
    const totalHoursInput = document.getElementById('second_hours');

    function calculateHours() {
        const startTimeVal = startTimeInput.value;
        const endTimeVal = endTimeInput.value;

        // Ensure both fields contain a valid value before executing math
        if (!startTimeVal || !endTimeVal) {
            totalHoursInput.value = '';
            return;
        }

        // Attach a dummy date structure to parse standard HTML time "HH:MM"
        const dummyDate = '1970-01-01 ';
        const startTimestamp = new Date(dummyDate + startTimeVal).getTime();
        let endTimestamp = new Date(dummyDate + endTimeVal).getTime();

        // Account for shifts crossing midnight (e.g., 10:00 PM to 06:00 AM)


       {{--  if (endTimestamp < startTimestamp) {
            endTimestamp += 24 * 60 * 60 * 1000; 
            // Add exactly 24 hours in milliseconds
        } --}}



        // Subtract timestamps to find millisecond difference
        const diffInMilliseconds = endTimestamp - startTimestamp;

        // Convert the structural milliseconds to float hours
        const diffInHours = diffInMilliseconds / (1000 * 60 * 60);

        // Populate field with a clean 2-decimal layout (e.g., 8.50)
        totalHoursInput.value = diffInHours.toFixed(2);
    }

    // Monitor value shifts on both fields
    startTimeInput.addEventListener('change', calculateHours);
    endTimeInput.addEventListener('change', calculateHours);
});
</script>
@stop