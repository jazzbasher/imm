@extends('adminlte::page')

@section('title', 'IMM Contacts') 

@section('content_header')

@section('content_top_nav_right')
            {{ Breadcrumbs::render('contacts') }}
@endsection

@include('partials.flash-messages')
    
@stop

@section('content')


<section class="content" style="margin-top: 5px;">
    <div class="container-fluid">
       
@section('plugins.Datatables', true)

    <x-adminlte-card title="IMM Internal Contact List" header-class="text-center" theme="secondary"> 
      <x-adminlte-datatable id="remit" class="with-buttons" :heads="$heads" :config="$config" striped compact with-buttons hoverable bordered compressed/>
    </x-adminlte-card>
@stop
  </div>
</section>


