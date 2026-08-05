@extends('adminlte::page')

@section('title', 'Edit AD Vendor Mapping')



@section('content')
@include('partials.flash-messages')

  <section class="content">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">         
        <div class="col-md-6">   
          <div class="card">
            <div class="card-body text-center">
              @foreach($advendor as $vendor)
                <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  <h4>{{ $vendor->ad_vendorname}}</h4>
                </div>    
                <form action="{{ route('admap.vendorupdate', ['id' => $vendor->vendor_id]) }}" method="POST">
                @csrf
                @method('PATCH')

                  <div class="form-group">
                    <label for="title">Vendor ID</label>
                      <input type="number" class="form-control" id="vendor_id" name="vendor_id" value="{{ $vendor->vendor_id }}" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="title">AD # (Supplier ID)</label>
                      <input type="number" class="form-control" id="supplier_id" name="supplier_id" value="{{ $vendor->supplier_id }}" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="title">AD Vendor Name (Not Prophet but AD Reference)</label>
                      <input type="test" class="form-control" id="ad_vendorname" name="ad_vendorname" value="{{ $vendor->ad_vendorname }}" autocomplete="off">
                  </div>
                 
                  <br/>            
                  <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                    <button type="submit" class="btn btn-danger">Edit This AD Mapped Vendor</button>
                </form>
              </div>
              <div class="card-footer">

              @endforeach
            </div>             
          </div>       
        </div>
      </div>
    </div>
  </section>

@stop

@push('css')
<style>
  /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
@endpush

