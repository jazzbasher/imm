@extends('adminlte::page')

@section('title', 'Time Off Request')

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
                    <h3>New Leave Request</h3>
        
        <form action="{{ route('leaverequest.store') }}" method="POST">
            @csrf

             <div class="form-group">
        <label>Select Duration</label>
        
        <!-- Option 1: Free -->
        <div class="form-check">
            <input type="radio" 
                   id="allday" 
                   name="allDay" 
                   value="1" 
                   class="time-toggle"
                   {{ old('allday', $allDay ?? 'active') === 'active' ? 'checked' : '' }}>
            <label for="allday">All Day (or mutliple days)</label>
        </div>

        <!-- Option 2: Pro -->
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

            <div class="form-group">
                <label for="title">Start Date</label>
                <input type="date" class="form-control" id="start" name="start" onchange="calculateWorkData()" value="{{ old('start') }}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
            </div>



               <div id="starttime" style="display: none; text-align: right;">
        <label for="starttime">Start Time</label>
        <input type="time" name="starttime" value="{{ old('starttime') }}" id="eventtime">
    </div>




            <div class="form-group">
                <label for="title">End Date</label>
                <input type="date" class="form-control" id="end" name="end" onchange="calculateWorkData()" value="{{ old('end') }}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
            </div>



           <div id="endtime" style="display: none; text-align: right;">
        <label for="endtime">End Time</label>
        <input type="time" name="endtime" value="{{ old('endtime') }}" id="eventtime">
    </div>


  <!-- Output: Total Work Days -->
    <div class="form-group">
        <label for="total_days">Total Requested Days:</label>
        <input type="text" id="total_days" name="total_days" class="form-control" readonly>
    </div>

    <!-- Output: Total Work Hours -->
    <div class="form-group">
        <label for="total_hours">Total Requested Hours (8 hrs/day):</label>
        <input type="text" id="total_hours" name="total_hours" class="form-control" readonly>
    </div>


            <div class="form-group">
                <label for="title">Type</label>
                	<select name="type"  class="form-control">
                    <option value="1" @selected(old('type') === '1')>Vacation</option>
                    <option value="2" @selected(old('type') === '2')>Sick Leave</option>
                </select>
             </div>

            <div class="form-group">
                    <label for="title">Notes (Optional)</label>
                     <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Type comments here..." autocomplete="off">{{ old('reason') }}</textarea>
                          {{-- <input type="text" class="form-control" id="comments" name="comments" autocomplete="off"> --}}
            </div>
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                      <br>
                      <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                      <button type="submit" class="btn btn-primary">Submit Request</button>
            


        </form>
    </div>
                </div>
              </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
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
            if (this.value === '0') {
                detailsSection.style.display = 'block';
                detailsS.style.display = 'block';
            } else {
                detailsSection.style.display = 'none';
                detailsS.style.display = 'none';
                document.getElementById('eventtime').value = ''; // Clear input if hidden
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
    if (start > end) {
        let temp = start;
        start = end;
        end = temp;
    }

    let workDays = 0;
    
    // Loop through the days and count only weekdays (Mon-Fri)
    let currentDate = new Date(start);
    while (currentDate <= end) {
        const dayOfWeek = currentDate.getDay();
        // 0 is Sunday, 6 is Saturday
        if (dayOfWeek !== 0 && dayOfWeek !== 6) {
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
@stop