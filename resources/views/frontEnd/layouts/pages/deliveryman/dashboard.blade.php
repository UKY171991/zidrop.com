@extends('frontEnd.layouts.pages.deliveryman.master')
@section('title', 'Dashboard')
@section('content')
<style>
    h5 {
        text-transform: uppercase;
    }

    .stats-reportList .stats-per-item {
        width: 100%;
        float: center;
        text-align: center;
    }

    .info-box {
        padding: 10px !important;
        border-radius: 10px !important;
    }

    .col-md-8 {
        padding: 0px !important;
    }

    .col-md-3,
    .col-sm-6,
    .col-12 {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    .info-box-text {
        font-size: 14px !important;
    }

    .info-box-content {
        padding-top: 0px !important;

    }

    .info-box-text {
        line-height: normal !important;
        text-transform: uppercase !important;
    }

    .info-box-icon_img {
        width: 40px;
        height: 40px;
    }

    .info-box-number {
        font-size: 26px !important;
        font-weight: 700 !important;
    }

    .section-padding {
        margin: 10px 0;
    }

    .dashboard-content {
        padding: 10px 0;
    }

    h3 {
        font-size: 26px !important;
        font-weight: 700 !important;
    }
</style>
    <section class="section-padding dashboard-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="stats-reportList-inner delivery-dashboar-inner">
                        {{-- <div class="row">
                            <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                                <div class="stats-reportList bg-dark">
                                    <div class="stats-per-item">
                                        <h5>Total Parcel</h5>
                                        <h3>N{{ number_format($totalparcel ?? 0, 2) }}</h3>
                                    </div>
                                </div>
                            </div>



                            <!-- col end -->
                            <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                                <div class="stats-reportList bg-success">
                                    <div class="stats-per-item">
                                        <h5>Total Deliverd</h5>
                                        <h3>N{{ number_format($totaldelivery ?? 0, 2) }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- col end -->
                            <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                                <div class="stats-reportList bg-success">
                                    <div class="stats-per-item">
                                        <h5>Total Hold</h5>
                                        <h3>N{{ number_format($totalhold ?? 0, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                                <div class="stats-reportList bg-warning">
                                    <div class="stats-per-item">
                                        <h5>Total Cancelled</h5>
                                        <h3>N{{ number_format($totalcancel ?? 0, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                                <div class="stats-reportList bg-info">
                                    <div class="stats-per-item">
                                        <h5>Returned Pending</h5>
                                        <h3>N{{ number_format($returnpendin ?? 0, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                                <div class="stats-reportList bg-danger">
                                    <div class="stats-per-item">
                                        <h5>Return To Merchant</h5>
                                        <h3>N{{ number_format($returnmerchant ?? 0, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- col end -->
                        </div> --}}
                        <div class="row mb-5">
                           
                            <div class="col-md-3 col-sm-6 col-12 ">
                                <div class="stats-reportList" style="background-color:#D68910">
                                    <a href="{{ url('/deliveryman/parcel/out-for-delivery') }}" style="color:#fff; justify-content-between">
                                        <div class="row info-box">
                                            <div class="col-md-4"> <img class="info-box-icon_img"
                                                    src="{{ asset('status_icon/status-3.png') }}" alt=""></div>
                                            <div class="col-md-8 text-right">
                                                <span class="info-box-text">Out For Delivery</span>
                                                <h3 class="text-right info-box-number">{{ $outfordevilery }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="stats-reportList" style="background-color:#145A32">
                                    <a href="{{ url('/deliveryman/parcel/deliverd') }}" style="color:#fff; justify-content-between">
                                        <div class="row info-box">
                                            <div class="col-md-4"> <img class="info-box-icon_img"
                                                    src="{{ asset('status_icon/status-4.png') }}" alt=""></div>
                                            <div class="col-md-8 text-right">
                                                <span class="info-box-text">Delivered</span>
                                                <h3 class="text-right info-box-number">{{ $delivered }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="stats-reportList" style="background-color:#00A86B">
                                    <a href="{{ url('/deliveryman/parcel/partial-delivery') }}" style="color:#fff; justify-content-between">
                                        <div class="row info-box">
                                            <div class="col-md-4"> <img class="info-box-icon_img"
                                                    src="{{ asset('status_icon/status-6.png') }}" alt=""></div>
                                            <div class="col-md-8 text-right">
                                                <span class="info-box-text">Partial Delivery</span>
                                                <h3 class="text-right info-box-number">{{ $partialdelivery }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="stats-reportList" style="background-color:#504A4B">
                                    <a href="{{ url('/deliveryman/parcel/disputed-packages') }}" style="color:#fff; justify-content-between">
                                        <div class="row info-box">
                                            <div class="col-md-4"> <img class="info-box-icon_img"
                                                    src="{{ asset('status_icon/status-5.png') }}" alt=""></div>
                                            <div class="col-md-8 text-right">
                                                <span class="info-box-text">Disputed</span>
                                                <h3 class="text-right info-box-number">{{ $disputedpackages }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="stats-reportList" style="background-color:#5F6A6A">
                                    <a href="{{ url('/deliveryman/parcel/return-to-hub') }}" style="color:#fff; justify-content-between">
                                        <div class="row info-box">
                                            <div class="col-md-4"> <img class="info-box-icon_img"
                                                    src="{{ asset('status_icon/status-7.png') }}" alt=""></div>
                                            <div class="col-md-8 text-right">
                                                <span class="info-box-text">Return To Hub</span>
                                                <h3 class="text-right info-box-number">{{ $returtohub }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
            
                        </div>
                    </div>
                    <!-- dashboard payment -->
                </div>
            </div>

            <div class="dashboard-payment-info delivery-dashboar-inner">
                <div class="row">
                    <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                        <div class="stats-reportList bg-dark">
                            <div class="stats-per-item">
                                <h5>Total Amount</h5>
                                <h3>N{{ number_format($totalamount ?? 0, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                        <div class="stats-reportList bg-success">
                            <div class="stats-per-item">
                                <h5>Paid Amount</h5>
                                <h3>N{{ number_format($deliveryman->total_paid_commission ?? 0, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
                        <div class="stats-reportList bg-danger">
                            <div class="stats-per-item">
                                <h5>Unpaid Amount</h5>
                                <h3>N{{ number_format($deliveryman->total_commission ?? 0, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>Parcel Statistics</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
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
                                $parcelcount = App\Parcel::where(['status' => $parceltype->id, 'deliverymanId' => Session::get('deliverymanId')])->count();
                            @endphp {{ $parcelcount }},
                        @endforeach
                    ]
                }]
            },

            // Configuration options go here
            options: {}
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>

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
@endsection
