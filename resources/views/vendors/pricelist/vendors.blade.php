@extends('adminlte::page')

@section('title', 'Price List Upload')

@section('content')
@include('partials.flash-messages')

<section class="content">
  <div class="container-fluid">
    <div class="row g-6 justify-content-left">         
      <div class="col-md-3">   
        <div class="card">
          <div class="card-body text-center">
              <div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                <h4>Vendors Price List</h4>
              </div>   

              <div class="list-group">
                @if(!empty($lenoxbandsaw->file_path) && Storage::disk('public')->exists($lenoxbandsaw->file_path))
                <a href="{{ Storage::disk('public')->url($lenoxbandsaw->file_path) }}" target="_blank" class="list-group-item list-group-item-action active">
                  Lenox Welded Band Saw
                </a>
                @else
                <a href="#bandsawnotfound" class="list-group-item list-group-item-action active">Lenox Welded Band Saw needs upload</a>
                @endif
                <a href="#" class="list-group-item list-group-item-action active">Dapibus ac facilisis in</a>
                <a href="#" class="list-group-item list-group-item-action active">Morbi leo risus</a>
                <a href="#" class="list-group-item list-group-item-action active">Porta ac consectetur ac</a>
                <a href="#" class="list-group-item list-group-item-action active">Vestibulum at eros</a>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


@stop

@push('css')
<style>
  a:hover {
      color: #f4fc03 !important; /* Forces the rule over default Bootstrap behavior */
  }
</style>
@endpush