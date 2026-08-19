@extends('adminlte::page')

@section('title', 'Warehouse Downloads')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('warehouseforms') }}
@endsection

@section('content')
@include('partials.flash-messages')

    <section class="content">
      <div class="container-fluid px-0">
        <div class="row justify-content-center">

            <div class="col-md-6 mb-4">
                <div class="card h-70">
                    <div class="card-header border-0">
                        <h3 class="card-title">Shipping Checklist</h3> 
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('images/thumbnails/shippingthumb.png') }}" class="img-thumbnail w-25" alt="Small Thumbnail">
                        </div>
                        <div class="card-footer text-muted text-small text-center" style="background-color: #40545C;">
                        <a href="{{ Storage::disk('public')->url($shippingchecklist) }}" class="small-box-footer text-white">Download <i class="fas fa-arrow-circle-up"></i></a>    
                    </div>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-md-6 mb-4">
                   <div class="card h-70">
                        <div class="card-header border-0">
                            <h3 class="card-title">Vehicle Maintenance Form</h3> 
                        </div>
                        <div class="card-body text-center">
                        <img src="{{ asset('images/thumbnails/maintenancethumb.png') }}" class="img-thumbnail w-25" alt="Small Thumbnail">
                        
                    </div>
                    <div class="card-footer text-muted text-small text-center" style="background-color: #40545C;">
                        <a href="{{ Storage::disk('public')->url($vehiclemaintenance) }}" class="small-box-footer text-white">Download <i class="fas fa-arrow-circle-up"></i></a>    
                    </div>
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