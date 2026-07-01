@extends('adminlte::page')

@section('title', 'Leave Event Edit')



@section('content')
@include('partials.flash-messages')

  <section class="content">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">     
        <div class="col-md-6">                            
          <div class="card">
            <div class="card-body text-center">

              @foreach($cals as $cal)

                <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  <h4>{{ $cal->user->name }}</h4> 
                </div>

                @if($cal->allDay === 1)

                  <p class="text-warning mb-3"><small><i class="far fa-calendar pr-2"></i></small>All Day/Multiple Days Leave</p>

                @elseif($cal->allDay === 0)

                  <p class="text-warning mb-3"><small><i class="fas fa-history pr-2"></i></small>Partial Day Leave</p>

                @endif

                <form action="{{ route('edit.leaverequest', ['id' => $cal->id, 'period' => $period, 'user' => $user]) }}" method="post">
                  @csrf

                  @if($cal->allDay === 1)

                    <div class="form-group">
                      <label for="title">Start Date</label>
                        <input type="date" class="form-control" id="start" name="start" value="{{\Carbon\Carbon::parse($cal->start)->format('Y-m-d')}}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                    </div>    
                    <div class="form-group">
                      <label for="title">End Date</label>
                        <input type="date" class="form-control" id="end" name="end" value="{{\Carbon\Carbon::parse($cal->end)->format('Y-m-d') }}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                    </div>

                  @elseif($cal->allDay ===0)

                    <div class="form-group">
                      <label for="title">Start Time</label>
                        <input type="datetime-local" class="form-control" id="start" name="start" value="{{\Carbon\Carbon::parse($cal->start)->format('Y-m-d\TH:i') }}" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                    </div>
                    <div class="form-group">
                      <label for="title">End Time</label>
                        <input type="datetime-local" class="form-control" id="end" name="end" value="{{\Carbon\Carbon::parse($cal->end)->format('Y-m-d\TH:i') }}" autocomplete="off"  onclick="this.showPicker()" onfocus="this.showPicker()">
                    </div>

                  @else

                  @endif

                  <div class="form-group">
                    <label for="title">Type</label>
                      <input type="text" class="form-control" id="type" name="type" value="{{ $cal->requesttype->type }}"
                          autocomplete="off" readonly disabled>
                  </div>              
                  <div class="form-group">
                    <label for="title">Approved By</label>
                      <input type="text" class="form-control" id="manager_id" name="manager_id" value="{{ $cal->manager->name }}" readonly disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="title">User Notes</label>
                      <input type="text" class="form-control" id="reason" name="reason" value="{{ $cal->reason }}" autocomplete="off">
                  </div>

                  <br/>
                  <input type="hidden" name="allDay" value ="{{ $cal->allDay }}">
                  <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                  <button type="submit" class="btn btn-danger">Edit This Leave Event</button>
                </form>
                </div>
              <div class="card-footer">
                <form action="{{ route('destroy.leaverequest', ['period' => $period, 'user' => $user]) }}" method="POST" onsubmit="return confirm('This action will permanantly delete this leave event.  Are you sure??');">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value ="{{ $cal->id }}">
                    <button type="submit" class="btn btn-link"><small style="color:red;">Delete This Leave Event</small></button>
                </form>
              </div>

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