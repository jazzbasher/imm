@extends('adminlte::page')


@section('plugins.Datatables', true)


@section('content')
@include('partials.flash-messages')

    
  <section class="content">
    <div class="card border border-dark p-2" style="background-color: #343C45; border-style: solid;">
      <div class="card-body">

        @php
          $heads = [
              'Name',
              'email',
              'Admin?',
              'FreightLog?',
              'OutsideSales?',
              'Hourly?',
              'Lunch',
              ['label' => 'Edit', 'no-export' => true, 'width' => 5],
          ];

          $config = [
              'order' => [[0, 'asc']],
              'searching' => true,
              'lengthChange' => false,
              'paging' => false,
              'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
          ];
        @endphp

        <x-adminlte-datatable id="tableusers" :heads="$heads" :config="$config" striped hoverable bordered compressed>
          @foreach($users as $user)
            <tr>
              <td class="text-nowrap">
                {{ $user->name }}
              </td>
              <td>
                {{ $user->email }}
              </td>
              <td class="text-center">
                @if($user->is_admin === 1)
                  <i class="fas fa-check text-success"></i>
                @endif  
              </td> 
              <td class="text-center">
                @if($user->freightlog === 1)
                  <i class="fas fa-check text-success"></i>
                @endif                        
              </td>                                                                                       
              <td class="text-center">                                                      
                @if($user->outside_sales === 1)
                  <i class="fas fa-check text-success"></i>
                @endif                          
              </td>
              <td class="text-center">                                                      
                @if($user->hourly === 1)
                  <i class="fas fa-check text-success"></i>
                @endif               
              </td>
              <td>
                @if($user->lunch_code > 0)                                                      
                  {{ $user->lunchcode->description }}
                @endif
              </td>
              <td class="project-actions">
                <a type="button" href="{{ route('admin.edituser', ['id' => $user->id]) }}" class="btn btn-tool" title="Edit This User">
                  <i class="fas fa-edit"></i>
                </a>
              </td>
            </tr>
          @endforeach
        </x-adminlte-datatable>
      </div>
    </div>
  </section>
@stop

