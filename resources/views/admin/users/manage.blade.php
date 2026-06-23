@extends('adminlte::page')

@section('subtitle', 'Admin Manage Users')

{{-- @section('content_top_nav_right')
            {{ Breadcrumbs::render('adminusers') }}
@endsection
 --}}
@section('content')
@if (session()->has('success'))
    <div id="flash-message" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session()->has('error'))
    <div id="flash-message" class="alert alert-danger">
        {{ session('error') }} 
    </div>
@endif
    <!-- Main content -->
    <section class="content">
      <div class="card">
         <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('admin.users.create') }}">
              <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-plus"></i></span>
              <div class="info-box-content">                <span class="info-box-text">Create New User</span>
                <span class="info-box-number"> </span>
              </div>
            </div>
          </div></a>
        </div>
        <div class="card-body p-0">
          <div> 
          <table class="table-card-mobile table table-sm table-striped projects">
              <thead class="small">
                  <tr>
                      <th>
                          Name
                      </th>
                      <th>
                          email
                      </th>
                      <th>
                          Created
                      </th>
                      <th>
                          Admin?
                      </th>
                      <th>
                          Sales?
                      </th>
                      <th>
                        Edit
                      </th>
                      <th>
                        Password
                      </th>
                  </tr>
              </thead>
              <tbody>
              @foreach($users as $user)
                  <tr>
                      <td>
                          {{ $user->name }}
                      </td>
                      <td>
                          {{ $user->email }} 
                      </td>
                      <td>
                          {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format("m/d/y") : ''  }}
                      </td>
                      <td>
                          {{ $user->is_admin}}
                      </td>
                      <td>
                          {{ $user->outside_sales }}
                      </td>                                    
                      <td class="project-actions">
                          <a type="button" href="#" class="btn btn-tool" title="Edit This User">
                            <i class="fas fa-edit"></i>
                          </a>
                      </td>
                      <td class="project-actions">
                          <a type="button" href="{{ route('admin.users.edit-password', ['user' => $user->id]) }}" class="btn btn-tool" title="Change Password">
                            <i class="fas fa-key"></i>
                          </a>
                      </td>
                  </tr>                
                  @endforeach              
              </tbody>
          </table>
        </div>
        </div>
      </div>
    </section>
    @stop

    @push('css')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    @endpush

    @push('js')
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="{{ asset('js/flash-remove.js') }}"></script>
    @endpush