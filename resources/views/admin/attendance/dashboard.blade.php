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



        <div class="row">
            <div class="col-md-6">
                <div class="card h-70">
                    <div class="card-header">
                        <h3 class="card-title">Current Statuses</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover"> 
                            <thead>
                                <tr>
                                    <th style="width: 30%"></th>
                                    <th  style="width: 60%"></th>
                                    <th  style="width: 5%"></th>
                                    <th  style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Clocked-In Users</td>
                                    <td class="align-middle">
                                        <div class="progress progress-xs">
                                            <div class="progress-bar bg-success d-flex align-items-center" role="progressbar" aria-valuenow="{{ $percentclocked }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $percentclocked }}%"></div>
                                        </div>
                                    </td>
                                    <td><small>{{ $countclocked }}/{{ $hourlyusers }}</small></td>
                                    <td><span class="badge bg-danger">{{ $percentclocked }}%</span></td>
                                </tr>
                                <tr>
                                    <td>On-Leave Today</td>
                                    <td class="align-middle">
                                        @forelse($leaveusers as $user)
                                            <small>{{ $user }} </small>
                                        @empty
                                            <span class="badge bg-warning align-middle ml-1 mr-1">0 </span>
                                        @endforelse
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>OT Last 7 Days</td>
                                    <td class="align-middle">
                                        @forelse($otcheck as $ot)
                                            <small>{{ \Illuminate\Support\Str::words($ot->user->name, 1, '') }}<span class="badge bg-warning align-middle ml-1 mr-1">{{ $ot->cnt }} </span></small>
                                        @empty
                                            <span class="badge bg-warning align-middle ml-1 mr-1">0 </span>
                                        @endforelse
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

                <div class="col-md-6">
                   <div class="card h-70">
                    <div class="card-header">
                        <h3 class="card-title">Who Is Clocked-In?</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                    @forelse($clockedusers as $clocked)
                                    <span class="badge badge-pill badge-warning"><small><b>{{ \Illuminate\Support\Str::words($clocked->name, 1, '') }} {{ \Carbon\Carbon::parse($clocked->latestClock->clock_in)->format('g:i') }}</b></small></span>
                                    @empty
                                        <small>No One Clocked In</small>
                                    @endforelse
                                    </td>

                                </tr>
                                <tr>
                                    <td><small>Tiffany's Metric Two</small></td>
                                </tr>
                                <tr>
                                    <td><small>Tiffany's Metric Three</small></td>
                                </tr>
                    
                          
                            </tbody>
                        </table>
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