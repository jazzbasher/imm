@extends('adminlte::page')

@section('title', '3M POS Report')

@section('content')
@include('partials.flash-messages')

  <section class="content">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">         
        <div class="col-md-6">   
          <div class="card">
            <div class="card-body text-center">
   
                <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  <h4>3M POS Report</h4>
                </div>    
                <form action="{{ route('mmm.report') }}" method="POST">
                @csrf
                @method('POST')
                <div>
                <button type="submit" name="dateparam" value="lastmonth" class="btn  btn-danger">All 3M POS For Last Month</button>
              </div>
              <br/>

                <div class="accordion" id="laravelAccordion">
        <div class="card">
            <!-- Accordion Header -->
            <div class="card-header" id="heading1">
                <h2 class="mb-0">
                    <button class="btn btn-warning btn-block text-left collapsed" 
                            type="button" 
                            data-toggle="collapse" 
                            data-target="#collapse1" 
                            aria-expanded="false" 
                            aria-controls="collapse1">
                        Report By Date Range Instead
                    </button>
                </h2>
            </div>

            <!-- Hidden Collapsible Div Container -->
            <div id="collapse1" 
                 class="collapse" 
                 aria-labelledby="heading1" 
                 data-parent="#laravelAccordion">
                <div class="card-body">
                    <div class="form-group">
                    <label for="title">Report Start Date</label>
                      <input type="date" class="form-control" id="start" name="start" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                  </div>
                  <div class="form-group">
                    <label for="title">Report End Date</label>
                      <input type="date" class="form-control" id="end" name="end" autocomplete="off" onclick="this.showPicker()" onfocus="this.showPicker()">
                  </div>
                  
                  

                  <br/>            
                  <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                    <button type="submit" name="dateparam" value="daterange" class="btn btn-warning">Get Report In Date Range</button>
                </div>
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

