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
                    
                <form action="{{ route('admin.userupdate', ['id' => $employee->id]) }}" method="patch">
                @csrf

                  <div class="form-group">
                    <label for="title">Name</label>
                      <input type="text" class="form-control" id="name" name="name" value="{{ $employee->name }}" autocomplete="off">
                  </div>

                  <div class="form-group">
                    <label for="title">email</label>
                      <input type="text" class="form-control" id="email" name="email" value="{{ $employee->email }}" autocomplete="off">
                  </div>


                    <div class="form-group">
                      <label for="title">Freight Log?</label>
                        <input type="text" class="form-control" id="freightlog" name="freightlog" value="{{ $employee->freightlog }}" autocomplete="off">
                    </div>
                    <div class="form-group">
                      <label for="title">Outside Sales?</label>
                        <input type="text" class="form-control" id="outside_sales" name="outside_sales" value="{{ $employee->outside_sales }}" autocomplete="off">
                    </div>


                  <div class="form-group">
                    <label for="title">Hourly?</label>
                      <input type="text" class="form-control" id="hourly" name="hourly" value="{{ $employee->hourly }}" autocomplete="off">
                  </div>

                  <div class="form-group">
                    <label for="title">Lunch Code</label>
                      <input type="text" class="form-control" id="lunch_code" name="lunch_code" value="{{ $employee->lunch_code }}" autocomplete="off">
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
    </section>

@stop

@section('css')
@stop

@section('js')

@stop