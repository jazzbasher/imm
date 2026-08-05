@extends('adminlte::page')

@section('title', 'Add Vendor AD Mapping')

@section('content')
@include('partials.flash-messages')

@section('content')
    <section class="content" style="margin-top: 5px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="container h-100 mt-5">
                <div class="row h-100 justify-content-center align-items-center">
                  
                  <div class="col-10 col-md-8 col-lg-6">
                    <h3>Add Vendor/AD # Mapping</h3>
                    <form action="{{ route('remit.create') }}" method="post" onkeydown="return event.key != 'Enter';">
                      @csrf
                      
                      <div class="form-group">
                        <label for="title">Vendor ID (from Prophet)</label>
                        <input type="number" class="form-control" id="vendor_id" name="vendor_id" autocomplete="off" value="{{ old('vendor_id') }}" class="@error('vendor_id') is-invalid @enderror" required>
                      </div>
                      <div class="form-group">
                        <label for="title">AD Supplier ID (provided by AD)</label>
                        <input type="number" class="form-control" id="supplier_id" name="supplier_id" autocomplete="off"  value="{{ old('supplier_id') }}" class="@error('supplier_id') is-invalid @enderror" required>
                      </div>
                      
                      <div class="form-group">
                        <label for="title">AD Supplier Name (what AD calls them, not in Prophet)</label>
                        <input type="text" class="form-control" id="ad_vendorname" name="ad_vendorname" autocomplete="off"  value="{{ old('ad_vendorname') }}" class="@error('ad_vendorname') is-invalid @enderror" required>
                      </div>
                     
                      <br>
                      <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                      <button type="submit" class="btn btn-primary">Add This Vendor Mapping</button>
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
@stop
