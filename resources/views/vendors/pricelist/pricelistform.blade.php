@extends('adminlte::page')

@section('title', 'Price List Upload')

@section('content')
@include('partials.flash-messages')

 <section class="content">
    <div class="container-fluid">
      	<div class="row g-6 justify-content-center">         
        	<div class="col-md-6">   
          		<div class="card">
            		<div class="card-body text-center">
   
                		<div class="rounded-circle bg-primary-subtle  d-inline-flex align-items-center justify-content-center mb-3">
                  			<h4>File Upload</h4>
                		</div>    

                		<form action="{{ route('pricelist.upload') }}" method="POST" enctype="multipart/form-data">
    					@csrf
    						<input type="file" name="document">
    						<button type="submit">Upload File</button>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@stop