@extends('adminlte::page')

@section('title', 'Home')

{{-- @section('content_top_nav_right')
            {{ Breadcrumbs::render('adminpw', $user->name) }}
@endsection --}}

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
                    <h5 class="mb-0">Change Password for {{ $user->name }}</h5>

                </div>

                <div class="card-body">
                    {{-- Success Alert Notification --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif


<form action="{{ route('admin.users.update-password', $user->id) }}" method="POST">
    @csrf
    @method('PUT')


    <!-- New Password Field -->
    <div class="form-group">
        <label for="password">New Password</label>
        <input type="text" name="password" id="password" autocomplete="new-password" onfocus="this.type='password'" required class="@error('password') is-invalid @enderror">
        @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Confirm New Password Field -->
    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="text" name="password_confirmation" id="password_confirmation" autocomplete="new-password" onfocus="this.type='password'"  required>
    </div>

    <button type="submit">Update Password</button>
</form>
</div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add extra stylesheets here --}}
@stop

@section('js')
<script src="{{ asset('js/flash-remove.js') }}"></script>

@stop