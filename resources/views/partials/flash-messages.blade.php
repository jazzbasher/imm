{{-- Success Alert --}}
@if (session()->has('success'))
    <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-check"></i> Success!</h5>
        {{ session('success') }}
        <button type="text" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Error Alert --}}
@if (session()->has('error'))
    <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        {{ session('error') }}
        <button type="text" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session()->has('deny'))
    <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-ban"></i> Success!</h5>
        {{ session('deny') }}
        <button type="text" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Validation Errors Alert --}}
@if ($errors->any())
    <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-ban"></i> Validation Errors</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="text" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif