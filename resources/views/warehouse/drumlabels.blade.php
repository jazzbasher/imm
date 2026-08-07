@extends('adminlte::page')

@section('title', 'Warehouse Downloads')

@section('content')
@include('partials.flash-messages')

  <section class="content">
    <div class="container-fluid">
      <div class="row g-6 justify-content-center">         
        <div class="col-md-6">   
          <div class="accordion" id="darkAccordion">

          @foreach($drumlabels as $label)

            <div class="card bg-dark text-white border-secondary">
              <div class="card-header" id="{{ $label->id }}">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left text-white collapsed d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapse-{{ $label->id }}" aria-expanded="true" aria-controls="collapse-{{ $label->id }}">
                    {{ $label->title }}
                    <i class="fas fa-chevron-down rotate-icon"></i>
                  </button>
                </h2>
              </div>
              <div id="collapse-{{ $label->id }}" class="collapse" aria-labelledby="{{ $label->id }}" data-parent="#darkAccordion">
                <div class="card-body text-center">
                  <a href="{{ Storage::disk('public')->url($label->file_path) }}" target="_blank" class="text-reset">Download</a>
                </div>
              </div>
            </div>
          @endforeach

          </div>
        </div>
      </div>
    </div>
  </section>

@stop

@push('css')
<style>
  .btn-link:not(.collapsed) .rotate-icon {
    transform: rotate(180deg);
  }
  .rotate-icon {
    transition: transform 0.2s ease-in-out;
  }
</style>
@endpush
