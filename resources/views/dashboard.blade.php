@extends('adminlte::page')

@section('title', 'IMM Home')

@section('content_header')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('home') }}
@endsection

@include('partials.flash-messages')
    
@stop

@section('content')

<section class="content">
      <div class="card">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12"> 
              <img src="{{ asset('/images/homehero.jpg') }}" class="img-fluid" >  
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{ route('contacts.view') }}">
            <div class="info-box">
              <span class="info-box-icon bg-warning elevation-1"><i class="far fa-address-book"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Contacts</span>
                </span>
              </div>
            </div>
          </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('calendar') }}">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-cyan elevation-1"><i class="fas fa-calendar-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Calendar 
                  @if($calendarevents)
                    <span class="badge badge-warning ml-2">{{ $calendarevents }}</span> 
                  @endif
                </span>
              </div>
            </div>
          </a>
          </div>
          <div class="clearfix hidden-md-up"></div>
          <div class="col-12 col-sm-6 col-md-3">
            <a href="https://industrialmill.epicordistribution.com/Prophet21/#/login" target="_blank">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-primary elevation-1"><i class="fab fa-product-hunt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Prophet</span>
              </div>
            </div>
          </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
              <span class="info-box-icon bg-dark elevation-1"><img class="rounded" src="{{ $icon }}" alt="{{ $condition }}" style="width: 65px; height: 65px;"></span>
              <div class="info-box-content"><span class="info-box-text">{{ $city }}</span>
                <span class="text-nowrap"><span class="info-box-number">{{ $temperature }}&deg;  <span class="small">{{ $condition }}</span></span></span>
              </div>
            </div>
          </div>
        </div> 
      </div>
</section>

@stop

@section('css')
  <style>
      .hero-bg {
          {{-- background-image: url('/images/landinghero.jpg');  --}}
          background-size: cover;
          background-position: center;
          background-repeat: no-repeat;
          color: #ffffff; /* Ensures text stands out over the image */
          width: 100%;
          min-height: 100px;
      }
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
         margin: 0;
      }

      input[type=number] {
        -moz-appearance: textfield;
      }
  </style>
@stop

@section('js')
@stop