@extends('adminlte::page')

@section('title', 'AD Remit Report')

@section('content')
@include('partials.flash-messages')

  <section class="content">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">         
        <div class="col-md-6">   
          <div class="card">
            <div class="card-body text-center">
   
                <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  <h4>AD 820 Remittance Report</h4>
                </div>    
                <form action="{{ route('remit.report') }}" method="POST">
                @csrf
                @method('POST')

                  <div class="form-group">
                    <label for="title">Choose Report Date</label>
                      <input type="date" class="form-control" id="date" name="date" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                  </div>
                  
                  

                  <br/>            
                  <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                    <button type="submit" class="btn btn-danger">Get AD Remit Report</button>
                </form>
              </div>
              <div class="card-footer">

 
            </div>             
          </div>       
        </div>
      </div>
    </div>
  </section>

@stop

