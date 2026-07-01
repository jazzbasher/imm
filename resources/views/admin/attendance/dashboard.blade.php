@extends('adminlte::page')

@section('title', 'Attendance Dash')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('attendancedash') }}
@endsection

@section('content')
@include('partials.flash-messages')

    <section class="content">
      <div class="container-fluid px-0">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-70">
                    <div class="card-header border-0">
                        <h5 class="card-title" style="font-size: 16px;">Current payperiod {{ \Carbon\Carbon::parse($thispayperiod['start_date'])->format('m/d/y') }} - {{ \Carbon\Carbon::parse($thispayperiod['end_date'])->format('m/d/y') }}</h5>
                        <div class="card-tools">
                            <a href="#" class="btn btn-sm btn-tool">
                                <i class="bi bi-download"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-tool">
                                <i class="bi bi-list"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                            <p class="text-info fs-2">
                                <i class="far fa-calendar-alt fa-lg"></i>
                            </p>
                            <p class="d-flex flex-column text-end">
                                <span class="fw-bold">
                                     {{ $calendarevents }}
                                </span>
                                <span class="text-secondary">APPROVED LEAVES</span>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-warning fs-2">
                                    <i class="far fa-clock fa-lg"></i>
                                </p>
                                <p class="d-flex flex-column text-end">
                                <span class="fw-bold">
                                     {{ round($totalclockhours,2) }}
                                </span>
                                <span class="text-secondary">CLOCKED HOURS</span>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer text-muted text-small text-center" style="background-color: #40545C;">
                        <a href="{{ route('attendance.periodreport', 'current') }}" class="small-box-footer text-white">Drill Down <i class="fas fa-arrow-circle-right"></i></a>    
                    </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                   <div class="card h-70">
                        <div class="card-header border-0">
                            <h5 class="card-title" style="font-size: 16px;">Last payperiod {{ \Carbon\Carbon::parse($lastpayperiod['start_date'])->format('m/d/y') }} - {{ \Carbon\Carbon::parse($lastpayperiod['end_date'])->format('m/d/y') }}</h5>
                            <div class="card-tools">
                              <a href="#" class="btn btn-sm btn-tool">
                                <i class="bi bi-download"></i>
                              </a>
                              <a href="#" class="btn btn-sm btn-tool">
                                <i class="bi bi-list"></i>
                              </a>
                            </div>
                        </div>
                        <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                          <p class="text-info fs-2">
                            <i class="far fa-calendar-alt fa-lg"></i>
                          </p>
                          <p class="d-flex flex-column text-end">
                            <span class="fw-bold">
                               {{ $lastcalendarevents }}
                            </span>
                            <span class="text-secondary">APPROVED LEAVES</span>
                          </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                          <p class="text-warning fs-2">
                            <i class="far fa-clock fa-lg"></i>
                          </p>
                          <p class="d-flex flex-column text-end">
                            <span class="fw-bold">
                               {{ round($lasttotalclockhours,2) }}
                            </span>
                            <span class="text-secondary">CLOCKED HOURS</span>
                          </p>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-small text-center" style="background-color: #40545C;">
                        <a href="{{ route('attendance.periodreport', 'last') }}" class="small-box-footer text-white">Drill Down <i class="fas fa-arrow-circle-right"></i></a>    
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