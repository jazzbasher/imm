@extends('adminlte::page')

@section('title', 'PayPeriod Punch Report')

@section('content')
@include('partials.flash-messages')

  <section class="content">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">         
        <div class="col-md-6">   
          <div class="card">
            <div class="card-body text-center">
   
                <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  <h4>Choose A PayPeriod</h4>
                </div>    
                <form action="{{ route('attendance.payperiodreport') }}" method="POST">
                @csrf
                @method('POST')
                <div>
                   <div class="form-group">
                        <label for="payperiod">Start Date</label>
                        <select class="form-control" id="payperiod" name="payperiod" required>
                          <option value="" selected disabled>Select...</option>
                            @foreach($filtered as $startdate)
                              <option value="{{ $startdate }}">
                                {{ $startdate }}
                              </option>
                            @endforeach
                       </select>
                      </div>
                <button type="submit" name="dateparam" value="lastmonth" class="btn  btn-danger">Get Report</button>
              </div>
              </form>                            
            </div>     
          </div>       
        </div>
      </div>
    </div>
  </section>

@stop

