@extends('adminlte::page')

@section('title', 'Edit User')



@section('content')
@include('partials.flash-messages')

  <section class="content">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">         
        <div class="col-md-6">   
          <div class="card">
            <div class="card-body text-center">
              @foreach($user as $employee)
                <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  <h4>{{ $employee->name }}</h4>
                </div>    
                <form action="{{ route('admin.userupdate', ['id' => $employee->id]) }}" method="POST">
                @csrf
                @method('PATCH')

                  <div class="form-group">
                    <label for="title">Name</label>
                      <input type="text" class="form-control" id="name" name="name" value="{{ $employee->name }}" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="title">email</label>
                      <input type="text" class="form-control" id="email" name="email" value="{{ $employee->email }}" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label class="pr-4"><span class="pr-4">Freightlog Entry?</span>
                      <input class="p-3" type="radio" id="freightlog" name="freightlog" value="1" 
                          @checked(old('freightlog', $employee->freightlog) == 1)>Yes
                    </label>
                    <label>
                      <input class="p-3" type="radio" id="freightlog" name="freightlog" value="0" 
                          @checked(old('freightlog', $employee->freightlog) == 0)>No
                    </label>
                  </div>
                  <div class="form-group">
                    <label class="pr-4"><span class="pr-4">Is Outside Sales?</span>
                      <input class="p-3" type="radio" id="outside_sales" name="outside_sales" value="1" 
                          @checked(old('outside_sales', $employee->outside_sales) == 1)>Yes
                    </label>
                    <label>
                      <input class="p-3" type="radio" id="outside_sales" name="outside_sales" value="0" 
                          @checked(old('outside_sales', $employee->outside_sales) == 0)>No
                    </label>
                  </div>
                  <div class="form-group">
                    <label class="pr-4"><span class="pr-4">Is Hourly (clock)?</span>
                      <input class="p-3" type="radio" id="hourly" name="hourly" value="1" 
                          @checked(old('hourly', $employee->hourly) == 1)>Yes
                    </label>
                    <label>
                      <input class="p-3" type="radio" id="hourly" name="hourly" value="0" 
                          @checked(old('hourly', $employee->hourly) == 0)>No
                    </label>
                  </div>
                  <div class="form-group">
                    <label for="title">Clock In/Out Lunch?</label>
                    <select name="lunch_code" id="lunch_code" class="form-control">
                        <option value="" disabled selected hidden>Select lunch option</option>
                       @foreach($lunchselects as $lunchselect)
                          <option value="{{ $lunchselect->lunch_id }}" 
                              {{ old('lunch_code', $employee->lunch_code) == $lunchselect->lunch_id ? 'selected' : '' }}>
                              {{ $lunchselect->description }}
                          </option>
                      @endforeach
                    </select>
                  </div>

                  <br/>            
                  <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                    <button type="submit" class="btn btn-danger">Edit This User</button>
                </form>
              </div>
              <div class="card-footer">
                <form action="#" method="POST" onsubmit="return confirm('This action will permanantly inactivate this user.  Are you sure??');">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value ="{{ $employee->id }}">
                    <button type="submit" class="btn btn-link"><small style="color:red;">Inactivate This User</small></button>
                </form>
              @endforeach
            </div>             
          </div>       
        </div>
      </div>
    </div>
  </section>

@stop

