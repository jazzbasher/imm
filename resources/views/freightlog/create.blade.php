@extends('adminlte::page')

@section('title', 'Add Freight Charge')

@section('content')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('newfreight') }}
@endsection

@include('partials.flash-messages')

@section('content')
    <section class="content" style="margin-top: 5px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="container h-100 mt-5">
                <div class="row h-100 justify-content-center align-items-center">
                  <div class="col-10 col-md-8 col-lg-6">
                    <h3>Add Freight Charge</h3>
                    <form action="{{ route('freightlog.store') }}" method="post" onkeydown="return event.key != 'Enter';">
                      @csrf
                      <div class="form-group">
                        <label for="title">Customer ID</label>
                        <input type="text" class="form-control" id="customer_id" name="customer_id" autocomplete="off" value="{{ old('customer_id') }}" class="@error('customer_id') is-invalid @enderror" required>
                      </div>
                      <div class="form-group">
                        <label for="title">Buyer Name</label>
                        <input type="text" class="form-control" id="buyer" name="buyer" autocomplete="off"  value="{{ old('buyer') }}" class="@error('buyer') is-invalid @enderror" required>
                      </div>
                      <div class="form-group">
						            <label for="salesrep">Sales Rep</label>
						            <select class="form-control" id="salesrep" name="salesrep" required>
						              <option value="" selected disabled>Select...</option>
						                @foreach($salespeople as $k => $v)
                              <option value="{{ $v }}">
                                {{ $k }}
                              </option>
                            @endforeach
					             </select>
						          </div>
                      <div class="form-group">
                        <label for="title">PO #</label>
                        <input type="text" class="form-control" id="po" name="po" autocomplete="off"  value="{{ old('po') }}" class="@error('po') is-invalid @enderror" required>
                      </div>
                      <div class="form-group">
                        <label for="title">Freight Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="0.00" min="0" step="0.01" autocomplete="off"  value="{{ old('amount') }}" class="@error('amount') is-invalid @enderror" required>
                      </div>
                      <div class="form-group">
                        <label for="title">Order #</label>
                        <input type="text" class="form-control" id="order_no" name="order_no" autocomplete="off"  value="{{ old('order_no') }}" class="@error('order_no') is-invalid @enderror">
                      </div>
                      <div class="form-group">
                        <label for="title">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Type optional note here..." autocomplete="off">{{ old('notes') }}</textarea>
                      </div>
                      <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                      <br>
                      <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                      <button type="submit" class="btn btn-primary">Create Freight Charge</button>
                    </form>
                  </div>
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
