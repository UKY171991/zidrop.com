@extends('backEnd.layouts.master')
@section('title', 'Super Admin Dashboard')
@section('content')
    <style>
        .info-box-icon_img {
            width: 55px;
            height: 55px;
        }

        .info-box-number {
            font-size: 26px;
        }

        .info-box {
            vertical-align: bottom !important;
        }

        .info-box-content {
            padding-top: 0px !important;

        }

        .info-box-text {
            line-height: normal !important;
        }
        .canvasjs-chart-credit{
            display: none !important;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card dashboard-body">
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($parceltypes as $key => $value)
                                        @if ($value->id == 11)
                                            @continue
                                        @endif
                                        @php
                                            $parcelcount = App\Parcel::where('status', $value->id)->count();
                                        @endphp
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="info-box box-bg-{{ $value->sl }}">
                                                <a href="{{ url('editor/parcel', $value->slug) }}" style="color:#fff;">
                                                    <span class="info-box-icon">
                                                        <img class="info-box-icon_img"
                                                            src="{{ asset('status_icon/status-' . $value->id . '.png') }}"
                                                            alt="">
                                                    </span>

                                                    <div class="info-box-content text-right">
                                                        <span class="info-box-text">{{ $value->title }}</span>
                                                        <span class="info-box-number">{{ $parcelcount }}</span>
                                                </a>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                </div>
                                <!-- col end -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- main col end -->
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card dashboard-body">
                        <div class="col-sm-12">
                            <div class="manage-button">
                                <div class="body-title">
                                    <h5>Payment Overall Status</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="info-box box-bg-1">
                                        <span class="info-box-icon"><i class="fa-solid fa-naira-sign"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Amount</span>
                                            <span class="info-box-number">{{ number_format($totalamounts ?? 0, 2) }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>



                                <!-- col end -->
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="info-box box-bg-2">
                                        <span class="info-box-icon"><i class="fa-solid fa-naira-sign"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Merchant Due Amount </span>
                                            <span class="info-box-number">{{ number_format($merchantsdue ?? 0, 2) }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>


                                <!-- col end -->
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="info-box box-bg-3">
                                        <span class="info-box-icon"><i class="fa-solid fa-naira-sign"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Merchant Paid Amount </span>
                                            <span class="info-box-number">{{ number_format($merchantspaid ?? 0, 2) }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>


                                <!-- col end -->
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="info-box box-bg-7">
                                        <span class="info-box-icon"><i class="fa-solid fa-naira-sign"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Today Monthly Payment</span>
                                            <span
                                                class="info-box-number">{{ number_format($todaymerchantspaid ?? 0, 2) }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- col end -->
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="info-box" style="background-color: #17202A; color:#fff">
                                        <span class="info-box-icon"><i class="fa-solid fa-naira-sign"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Returned To Merchant Due</span>
                                            <span
                                                class="info-box-number">{{ number_format($totalReturnMercahntDue ?? 0, 2) }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>


                                <!-- col end -->
                            </div>
                        </div>
                    </div>
                </div>


                <!-- main col end -->
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card dashboard-body">
                        <div class="col-sm-12">
                            <div class="manage-button">
                                <div class="body-title">
                                    <h5>Overall Agent/Hub Amount Due</h5>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="row">

                                @foreach ($agentsDue as $key => $value)
                                    <?php
                                    if ($value->totalDue == 0) {
                                        continue;
                                    }
                                    
                                    $agentName = App\Agent::where('id', $value->agentId)->pluck('name')->first() ?? 'Null';
                                    ?>


                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="info-box " style="background-color: rgb(4, 98, 98); color:#fff">
                                            <span class="info-box-icon"><i class="fa-solid fa-naira-sign"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $agentName }}</span>
                                                <span
                                                    class="info-box-number">{{ number_format($value->totalDue ?? 0, 2) }}</span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        {{-- <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box " style="background-color: rgb(4, 98, 98); color:#fff">
                      <ul class="py-2" >
                        @foreach ($agentsDue as $key => $value)
                          <?php
                          $agentName = App\Agent::where('id', $value->agentId)->pluck('name')->first() ?? 'Null';
                          ?>
                          <li class="text-white py-1">
                            <a class="text-white  px-3" href="#">{{$agentName}} : {{$value->totalDue}}</a>
                          </li>
                        @endforeach
      
                      </ul>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div> --}}

                    </div>
                </div>
                <!-- main col end -->
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        {{-- <div class="card-header">
             <h3 class="float left">Parcel Statistics</h3>
             <form class="form-group" action="{{url('allparcel/search/')}}" method="post">
                @csrf
                <input type="text" placeholder="   Enter parcel" name="parcel"  style="height : 40px;" class="mt-2">
                <input type="submit" value="Search" style="height : 40px;">
            </form>
           </div> --}}
                        {{-- <div class="card-body">
                            <canvas id="myChart"></canvas>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row py-3 px-3">

            <div id="chartContainer" style="height: 500px; width: 100%;"></div>
        </div>
        </div>
    </section>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'pie',

            // The data for our dataset
            data: {
                labels: [
                    @foreach ($parceltypes as $parceltype)
                        '{{ $parceltype->title }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Parcel Statistics',
                    backgroundColor: ['#1D2941', '#5F45DA', '#670A91', '#096709', '#FFAC0E', '#AAB809',
                        '#2094A0', '#9A8309', '#C21010'
                    ],
                    borderColor: ['#1D2941', '#5F45DA', '#670A91', '#096709', '#FFAC0E', '#AAB809',
                        '#2094A0', '#9A8309', '#C21010'
                    ],
                    data: [
                        @foreach ($parceltypes as $parceltype)
                            @php
                                $parcelcount = App\Parcel::where('status', $parceltype->id)->count();
                            @endphp {{ $parcelcount }},
                        @endforeach
                    ]
                }]
            },

            // Configuration options go here
            options: {}
        });
    </script>


    <script>
        $(document).ready(function() {
            // Function to check status and refresh time from server
            function checkAutoRefresh() {
                $.ajax({
                    url: "{{ route('admin.auto-refresh') }}",
                    method: "GET",
                    dataType: "json",
                    success: function(data) {

                        if (data.status === 'active') {
                            // Display message in the UI
                            //$('#auto-refresh-status').html('<p>' + data.message + '</p>');

                            // Set the refresh interval in milliseconds (time is in minutes, so we convert)
                            let refreshTime = data.time * 1000;

                            //alert('Tested ' + data.status + " " + data.time +" "+ refreshTime);

                            // Trigger auto-refresh after the given time
                            setTimeout(function() {
                                location.reload(); // Refresh the page
                            }, refreshTime);

                        } else {
                            // Display message if auto-refresh is inactive or time is not set
                            $('#auto-refresh-status').html('<p>' + data.message + '</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error fetching auto-refresh status:', error);
                    }
                });
            }

            // Initial call to check the auto-refresh status
            checkAutoRefresh();
        });
    </script>

    <script>
        window.onload = function() {
            var deliveredData = @json($deliveredParcels);
            var pickupData = @json($pickupParcels);

            // Transform Laravel data to match CanvasJS format
            var deliveredDataPoints = deliveredData.map(item => ({
                label: item.month,
                y: item.count
            }));

            var pickupDataPoints = pickupData.map(item => ({
                label: item.month,
                y: item.count
            }));

            // Get maximum values for scaling both Y-axes equally
            var maxDelivered = Math.max(...deliveredDataPoints.map(d => d.y), 0);
            var maxPickup = Math.max(...pickupDataPoints.map(d => d.y), 0);
            var maxScale = Math.max(maxDelivered, maxPickup); // Ensure both Y-axes use the same max value

            var chart = new CanvasJS.Chart("chartContainer", {
                // height: 500, // Adjust this value as needed (default is ~400)
                exportEnabled: false,
                animationEnabled: true,
                title: {
                    text: "Monthly Pickup Vs Delivery"
                },
                subtitles: [{
                    text: ""
                }],
                axisX: {
                    title: ""
                },
                axisY: {
                    title: "Pickup Parcels",
                    titleFontColor: "#2A5D8A",
                    lineColor: "#2A5D8A",
                    labelFontColor: "#2A5D8A",
                    tickColor: "#2A5D8A",
                    includeZero: true,
                    maximum: maxScale // Set the same max scale
                },
                axisY2: {
                    title: "Delivered Parcels",
                    titleFontColor: "#145A32",
                    lineColor: "#145A32",
                    labelFontColor: "#145A32",
                    tickColor: "#145A32",
                    includeZero: true,
                    maximum: maxScale // Set the same max scale
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                data: [{
                        type: "column",
                        name: "Pickup",
                        axisYType: "secondary",
                        color: "#2A5D8A",
                        showInLegend: true,
                        yValueFormatString: "#,##0.# Parcels",
                        dataPoints: pickupDataPoints
                    },
                    {
                        type: "column",
                        name: "Delivered",
                        color: "#145A32",
                        showInLegend: true,
                        yValueFormatString: "#,##0.# Parcels",
                        dataPoints: deliveredDataPoints
                    }

                ]
            });

            chart.render();

            function toggleDataSeries(e) {
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                e.chart.render();
            }
        }
    </script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
@endsection
