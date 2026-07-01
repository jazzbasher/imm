@extends('adminlte::page')

@section('title', 'Attendance Dash')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('punchdetails') }}
@endsection

@section('content')
@include('partials.flash-messages')

  <section class="content" style="margin-top: 5px;">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">         
        <div class="col-md-6">   
          <div class="card">
            <div class="card-body text-center">

              @foreach($event as $punch)

                <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  <h4>{{ $punch->user->name }}</h4>
                </div>
                    
                <form action="{{ route('edit.timepunch', ['id' => $punch->id, 'period' => $period, 'user' => $user]) }}" method="post">
                @csrf

                  <div class="form-group">
                    <label for="title">Clock In</label>
                      <input type="datetime-local" class="form-control" id="clock_in" name="clock_in" value="{{\Carbon\Carbon::parse($punch->clock_in)->format('Y-m-d\TH:i')}}" autocomplete="off"  onclick="this.showPicker()" onfocus="this.showPicker()">
                  </div>

                  @if($punch->user->lunch_code === 3)

                    <div class="form-group">
                      <label for="title">Lunch In</label>
                        <input type="time" class="form-control" id="lunch_in" name="lunch_in" value="{{$punch->lunch_in ? \Carbon\Carbon::parse($punch->lunch_in)->format('H:i') : ''}}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                    </div>
                    <div class="form-group">
                      <label for="title">Lunch Out</label>
                        <input type="time" class="form-control" id="lunch_out" name="lunch_out" value="{{$punch->lunch_out ? \Carbon\Carbon::parse($punch->lunch_out)->format('H:i') : ''}}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                    </div>

                  @endif

                  <div class="form-group">
                    <label for="title">Clock Out</label>
                      <input type="datetime-local" class="form-control" id="clock_out" name="clock_out" value="{{$punch->clock_out ? \Carbon\Carbon::parse($punch->clock_out)->format('Y-m-d\TH:i') : ''}}" autocomplete="off"  onclick="this.showPicker()" onfocus="this.showPicker()">
                  </div>
                  <br/>
                   
                  <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                    <button type="submit" class="btn btn-danger">Edit This Punch</button>
                </form>
                </div>
                <div class="card-footer">
                <form action="{{ route('destroy.timepunch', ['period' => $period, 'user' => $user]) }}" method="POST" onsubmit="return confirm('This action will permanantly delete this time punch event.  Are you sure??');">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value ="{{ $punch->id }}">
                    <button type="submit" class="btn btn-link"><small style="color:red;">Delete This Punch</small></button>
                </form>

                @endforeach
                </div>             
              </div>       
            </div>
          </div>
    </section>

@stop

@section('css')
@stop

@section('js')

@stop