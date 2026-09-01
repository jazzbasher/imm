@extends('adminlte::page')

@section('title', 'Vendor Pricelists')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('pricelist') }}
@endsection

@section('content')
@include('partials.flash-messages')

    <section class="content">
      <div class="container-fluid px-0">
        <div class="row justify-content-center">

            <div class="col-md-6 mb-4">
                <div class="card h-70">
                    <div class="card-header border-0">
                        <h3 class="card-title">Lenox Welded Band Saw</h3> 
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('images/thumbnails/lenoxpricelistthumb.png') }}" class="img-thumbnail w-25" alt="Small Thumbnail">
                        </div>
                        
                        <div class="card-footer text-muted text-small text-center" style="background-color: #40545C;">
                        @if(!empty($lenoxbandsaw->file_path) && Storage::disk('public')->exists($lenoxbandsaw->file_path))
                            <a href="{{ Storage::disk('public')->url($lenoxbandsaw->file_path) }}" target="_blank" class="small-box-footer text-white">View <i class="far fa-eye"></i></a>
                        @else
                            <a href="#bandsawnotfound" class="list-group-item list-group-item-action active">Lenox Welded Band Saw needs upload</a>
                        @endif    
                    </div>
                  </div>
                </div>
              </div>
              {{-- <div class="row justify-content-center">
                <div class="col-md-6 mb-4">
                   <div class="card h-70">
                        <div class="card-header border-0">
                            <h3 class="card-title">Placeholder for another vendor pricelist</h3> 
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('images/thumbnails/maintenancethumb.png') }}" class="img-thumbnail w-25" alt="Small Thumbnail">
                        </div>
                        <div class="card-footer text-muted text-small text-center" style="background-color: #40545C;">
                            <a href="#" class="small-box-footer text-white">View <i class="far fa-eye"></i></a>    
                        </div>
                    </div>             
                </div>
              </div> --}}

      </div>
    </section>

@stop

@section('css')
@stop

@section('js')

@stop