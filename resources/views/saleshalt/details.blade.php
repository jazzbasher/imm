@extends('adminlte::page')

@section('subtitle', 'Equipment Detail')



@section('content')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-warning"></i>Item ID/Name</span>
                      <p class="description-header">{{ $itemid }}</p>
                      <p class="description-header"></p>
                      <p class="description-header"></p>
                    </div>
                  </div>
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-warning"></i>Item Details</span>
                      <p class="description-header"></p>
                      <p class="description-header">$</p>
                      <p class="description-header"></p>
                      
                    </div>
                  </div>
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-warning"></i>Customer ID/Name</span>
                      <p class="description-header">{{ $custid }}</p>
                      <p class="description-header"></p>
                      <p class="description-header"></p>
                       
                    </div>
                  </div>
                  <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <span class="description-percentage text-warning">Purchase History</span>
                      <p class="description-header"></p>
                      <p class="description-header"></p>
                      <p class="description-header"></p>
                        
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Title 1</h3>
                <canvas id="myChart" style="width:100%;"></canvas>  
              </div>
              
            </div>                 
          </div>
          {{-- <div class="col-md-4">
     
            <div class="info-box mb-3 bg-info">
              <span class="info-box-icon"><i class="fas fa-wrench"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Work Orders</span>
                <span class="info-box-number">Order Count</span>
              </div>
            </div>
            <div class="info-box mb-3 bg-warning">
              <span class="info-box-icon"><i class="fas fa-cart-arrow-down"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Cost</span>
                <span class="info-box-number">Cost Count</span>
              </div>
            </div>
            <div class="info-box mb-3 bg-success">
              <span class="info-box-icon"><i class="fas fa-tools"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Last Maintenance</span>
                
              </div>
            </div>
            <div class="info-box mb-3 bg-danger">
              <span class="info-box-icon"><i class="far fa-sticky-note"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Next Maintenance</span>
                <span class="info-box-number">
                
                 </span>
              </div>
            </div>
            <div class="info-box mb-3 bg-secondary">
              <span class="info-box-icon"><i class="fas fa-location-arrow"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Last Mileage/Hour Ping Date</span>
                <span class="info-box-number">
                 
                </span>
              </div>
            </div>         
          </div> --}}
        </div>
           
           
      </div>
    </section>

@stop

@push('js')
     <script>
      var chartLabels = @json($yoylabel);
      var chartDataValues = @json($yoydata);
      new Chart(document.getElementById("myChart"), {
          type: 'line',
          data: {
            labels: chartLabels,
            datasets: [{
              label: "",
              borderColor: 'rgb(75, 192, 192)',
              backgroundColor: "#FFFFFF",
              fill: false,
              tension: 0.2,
              data: chartDataValues
            }]
          },
          options: {
              responsive: true,
              legend: { display: false },
              scales: {
                yAxes: [{
                  ticks: {
                    fontColor: 'white',
                    beginAtZero: true 
                  }
                }],
                xAxes: [{
                  ticks: {
                    fontColor: 'white'
                  }
                }]
              },
              title: {
                  display: true,
                  text: 'Purchases By Date Since 2024'
              }

          }
      });
    </script>
@endpush