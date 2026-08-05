@extends('adminlte::page')

@section('title', 'Order Line Item Detail')

@section('content')
@include('partials.flash-messages')

  <section class="content">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">         
        <div class="col-md-6">   
          <div class="card">
            <div class="card-body text-center">
   
                <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  <h4>Order Line Item Detail Report</h4>
                </div>    
                <form action="{{ route('epicorreport.post') }}" method="POST">
                @csrf
                @method('POST')
  
              <br/>

                  <div class="card">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="title">Report Start Date</label>
                          <input type="date" class="form-control" id="start" name="start" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                      </div>
                      <div class="form-group">
                        <label for="title">Report End Date</label>
                          <input type="date" class="form-control" id="end" name="end" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                      </div>
                             
                      <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                      <button type="submit" class="btn btn-warning">Get Report</button>
                    </div>
                  </div>

   </form>
</div>




                  
                
              </div>

           
          </div>       
        </div>
      </div>
    </div>
  </section>

@stop

