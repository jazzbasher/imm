@extends('adminlte::page')

@section('title', 'Add Freight Charge')

@section('content')
@include('partials.flash-messages')

@section('content')
    <section class="content" style="margin-top: 5px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="container h-100 mt-5">
                <div class="row h-100 justify-content-center align-items-center">
                  
                  <div class="col-10 col-md-8 col-lg-6">
                    <h3>Edit Freight Charge</h3>

                    <form action="{{ route('freightlog.zz', ['id' => $id]) }}" method="post">
                      @csrf
                      @foreach($logs as $log)

                      <div class="form-group">
                        <label for="title">Customer</label>
                        <input type="text" class="form-control" id="customer_id" name="customer_id" value="{{ $log->customer_id }}"  autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label for="title">Buyer</label>
                        <input type="text" class="form-control" id="buyer" name="buyer" value="{{ $log->buyer }}"   autocomplete="off">
                      </div>

                      <div class="form-group">
                        <label for="salesrep">Sales Rep</label>
                        <select class="form-control" id="salesrep" name="salesrep" required>
        
                            @foreach($salespeople as $k => $v)
                                <option value="{{ $v }}" @selected(old('salesrep', $log->salesrep) == $v)>
                                    {{ $k }}
                                </option>
                            @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="title">PO</label>
                        <input type="text" class="form-control" id="po" name="po" value="{{ $log->po }}"   autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label for="title">Freight Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ $log->amount }}" min="0" step="0.01"  autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label for="title">Order Number</label>
                        <input type="text" class="form-control" id="order_no" name="order_no" value="{{ $log->order_no }}"   autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label for="title">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Type notes here..." autocomplete="off">{{ $log->notes }}</textarea>
                          {{-- <input type="text" class="form-control" id="comments" name="comments" autocomplete="off"> --}}
                      </div>
                      @endforeach

                      <br>
                      <a href="{{ url()->previous() }}" class="btn btn-secondary mr-5">Cancel</a>
                      <button type="submit" class="btn btn-primary">Update This Freight Charge</button>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
@stop

@section('css')
@stop

@section('js')
@stop