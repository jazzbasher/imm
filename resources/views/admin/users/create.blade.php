@extends('adminlte::page')

@section('title', 'Create User')

@section('content')

@if (session()->has('error'))
    <div id="flash-message" class="alert alert-danger">
        {{ session('error') }} 
    </div>
@endif
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Register New User</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">First and Last Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   placeholder="John Doe"
                                   value="{{ old('name') }}" 
                                   required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">Confirm Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password-confirm" 
                                   name="password_confirmation" 
                                   required autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="branch" class="form-label">Branch</label>
                            <input type="branch" 
                                   class="form-control @error('branch') is-invalid @enderror" 
                                   id="branch" 
                                   name="branch" 
                                   value="{{ old('branch') }}" 
                                   autocomplete="branch">
                            @error('branch')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="extension" class="form-label">Extension</label>
                            <input type="extension" 
                                   class="form-control @error('extension') is-invalid @enderror" 
                                   id="extension" 
                                   name="extension" 
                                   value="{{ old('extension') }}" 
                                   autocomplete="extension">
                            @error('extension')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Outside Sales?</label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="outside_sales" id="not_sales" value="0"
                                @checked(old('sales', 0) === 0)>
                                <label class="form-check-label" for="not_sales">No</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="outside_sales" id="is_sales" value="1">
                                <label class="form-check-label" for="is_sales">Yes</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Freightlog Entry?</label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="freightlog" id="not_freightlog" value="0"
                                @checked(old('freightlog', 0) === 0)>
                                <label class="form-check-label" for="not_freightlog">No</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="freightlog" id="is_freightlog" value="1">
                                <label class="form-check-label" for="is_freightlog">Yes</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Accounting?</label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="accounting" id="not_accounting" value="0"
                                @checked(old('accounting', 0) === 0)>
                                <label class="form-check-label" for="not_sales">No</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="accounting" id="is_accounting" value="1">
                                <label class="form-check-label" for="is_accounting">Yes</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Hourly Employee?</label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hourly" id="not_hourly" value="0"
                                @checked(old('sales', 0) === 0)>
                                <label class="form-check-label" for="not_hourly">No</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hourly" id="is_hourly" value="1">
                                <label class="form-check-label" for="hourly">Yes</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-secondary me-md-2">Clear Form</button>
                            <button type="submit" class="btn btn-primary">Create User Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
  
@stop

@section('js')
<script src="{{ asset('js/flash-remove.js') }}"></script>

@stop