@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
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
            <div class="info-box">
              <span class="info-box-icon bg-warning elevation-1"><i class="far fa-address-book"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Contacts</span>
                </span>
              </div>
            </div>
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
                <span class="info-box-number">{{ $temperature }}&deg;  <span class="small">{{ $condition }}</span></span>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body p-0">
          <div> 




          </div>
        </div>
      </div>
    </section>



{{-- <div class="container" style="max-width: 400px;">
  
        @if($temperature)
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">      
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <img src="{{ $icon }}" alt="{{ $condition }}" style="width: 70px; height: 70px;">
                        <h3 class="ml-3">
                            {{ $temperature }}&deg; F
                        </h3>
                    </div>
                 
                    <hr>
                    <p class="card-text small text-muted mb-0">
                        Humidity: {{ $humidity }} 
                    </p>
                </div>
            </div>
        @endif
    </div> --}}



{{--   <header class="jumbotron jumbotron-fluid hero-bg text-white text-center m-0">
  <div class="container py-0">
    <h1 class="display-3 font-weight-bold">   </h1>
    <p class="lead my-4">   </p>
    <div class="mt-5">

    </div>
  </div>
</header> --}}
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