<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Zidrop | @yield('title', 'Move Everywhere')</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, minimum-scale=1.0">
    <meta charset="UTF-8" />
    <meta name="keywords"
        content="" />
    <script>
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- //Meta tag Keywords -->
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon.png') }}">
    {{-- <script src="{{ asset('frontEnd/') }}/js/jquery_3.4.1_jquery.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom-Files -->
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/bootstrap4.min.css">

    <!-- Bootstrap-Core-CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- flaticon -->
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/merchant.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/swiper-menu.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/dist/css/toastr.min.css">
    <!-- datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
    <!-- Style-CSS -->
    <link href="{{ asset('frontEnd') }}/css/fontawesome-all.min.css" rel="stylesheet">
    <!-- Font-Awesome-Icons-CSS -->
    <!-- //Custom-Files -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <style>
        .pageStatusTitle {
            font-weight: bolder !important;
        }
    </style>
</head>

<body>
    @php
        $deliverymanInfo = App\Deliveryman::find(Session::get('deliverymanId'));
    @endphp
    <section class="mobile-menu ">
        <div class="swipe-menu default-theme">
            <div class="postyourad">
                <a href="{{ url('merchant/dashboard') }}">
                    <img src="{{ asset($deliverymanInfo->image) }}" alt="Your logo" />

                    <a><b>{{ $deliverymanInfo->name }}</b></a>

                </a>

                <!--                 <a  href="{{ url('deliveryman/dashboard') }}" class="mobile-username">{{ $deliverymanInfo->names }}</a>-->

            </div>
            <!--Navigation Icon-->
            <div class="nav-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav class="codehim-nav">
                <ul class="menu-item">


                    <li><a href="{{ url('deliveryman/dashboard') }}">Dashboard</a>
                    </li>
                    <li><a href="{{ url('deliveryman/parcels') }}" class="mcreate_parcel">
                            My All Parcels
                        </a>
                    </li>

                    @foreach ($parceltypes as $parceltype)
                        @if ($parceltype->id == 11)
                            @continue
                        @endif
                        @php
                            $parcelcount = App\Parcel::where([
                                'status' => $parceltype->id,
                                'deliverymanId' => Session::get('deliverymanId'),
                            ])->count();
                        @endphp
                        <li class="nav-item">
                            <a href="{{ url('deliveryman/parcel', $parceltype->slug) }}">
                                <i class="fa fa-circle-notch"></i>
                                {{ $parceltype->title }} ({{ $parcelcount }})
                            </a>
                        </li>
                    @endforeach



                    <li>
                        <a href="{{ url('deliveryman/pickup') }}">
                            <i class="fa fa-circle-notch"></i>
                            Pickup Manage
                        </a>
                    </li>


                    <li><a href="{{ url('deliveryman/logout') }}" class="agent-logout">Logout</a>
                    </li>
                    <br>
                    <li>
                    </li>
                    <br>
                    <li>
                    </li>
                    <br>
                    <li>
                    </li>

                    <br>
                    <li>
                    </li>
                    <br>
                    <li>
                    </li>

                </ul>
                <!--//Tab-->
            </nav>
        </div>
    </section>
    <!-- mobile menu end -->
    <section class="main-area">
        <div class="dash-sidebar">
            <div class="sidebar-inner">
                <div class="profile-inner">
                    <div class="profile-pic">
                        <a href="#"><img src="{{ asset($deliverymanInfo->image) }}" alt=""></a>
                    </div>
                    <div class="profile-id">
                        <p>{{ $deliverymanInfo->name }}: {{ $deliverymanInfo->id }}</p>
                    </div>
                    <div class="dashboard-button">
                        <a href="{{ url('deliveryman/dashboard') }}">Dashboard</a>
                    </div>
                </div>
                <div class="side-list">
                    <ul>
                        <li>
                            <a href="{{ url('/deliveryman/dashboard') }}">
                                <i class="fa fa-home"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('deliveryman/parcels') }}">
                                <i class="fa fa-car"></i>
                                My All Parcels
                            </a>
                        </li>

                        @foreach ($parceltypes as $parceltype)
                            @if ($parceltype->id == 11)
                                @continue
                            @endif
                            @php
                                $parcelcount = App\Parcel::where(function ($query) use ($parceltype) {
                                    $query->where('deliverymanId', Session::get('deliverymanId'));
                                    $query->orWhere('pickupmanId', Session::get('deliverymanId'));
                                })
                                    ->where('status', $parceltype->id)
                                    ->count();
                            @endphp
                            <li class="nav-item">
                                <a href="{{ url('deliveryman/parcel', $parceltype->slug) }}">
                                    <i class="fa fa-circle-notch"></i>
                                    {{ $parceltype->title }} ({{ $parcelcount }})
                                </a>
                            </li>
                        @endforeach

                        <li>
                            <a href="{{ url('deliveryman/pickup') }}">
                                <i class="fa fa-truck"></i>
                                Pickup Manage
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('del.com.historyy', Session::get('deliverymanId')) }}">
                              <i>
                                <img src="{{ asset('naira.png') }}" alt="" width="16px"
                                    class="img-fluid">
                            </i>
                                Commission History
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('deliveryman/logout') }}">
                                <i class="fa fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </li>
                        <br>
                        <li>

                        </li>
                        <br>
                        <li>

                        </li>



                    </ul>
                </div>
            </div>
        </div>
        <!-- Sidebar End -->
        <div class="dashboard-body">
            <div class="heading-bar">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="pik-inner">
                            <ul>
                                <li>
                                    <div class="dash-logo">
                                        @foreach ($whitelogo as $key => $value)
                                            <a href="{{ url('merchant/dashboard') }}"><img
                                                    src="{{ asset($value->image) }}" alt=""></a>
                                        @endforeach
                                    </div>

                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <div class="heading-right">
                            <ul>
                                <li>
                                    <div class="track-area">
                                        <form action="{{ url('/deliveryman/parcel/track') }}" method="POST">
                                            @csrf
                                            <input class="form-control" type="text" name="trackid"
                                                placeholder="Search your track number..." search>
                                            <button>Submit</button>
                                        </form>
                                    </div>

                                </li>



                                <li class="profile-area">
                                    <div class="profile">
                                        <a class=""><img src="{{ asset($deliverymanInfo->image) }}"
                                                alt="">

                                        </a>
                                        <ul>
                                            <li><a href="{{ url('deliveryman/profile/edit') }}">Setting</a></li>
                                            <li><a href="{{ url('deliveryman/logout') }}">Logout</a></li>
                                        </ul>
                                    </div>

                                </li>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
            <div class="main-body">
                <div class="col-sm-12">
                    @yield('content')
                </div>
            </div>
            <!-- Column End-->
        </div>
    </section>

    <!--Next Day Pick Modal end -->
    <script type="text/javascript" src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{-- <script src="{{ asset('frontEnd/') }}/js/bootstrap4.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('frontEnd/') }}/js/swiper-menu.js"></script>
    <script src="{{ asset('backEnd/') }}/dist/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    <!-- Datatable -->
    <script src="{{ asset('backEnd/') }}/plugins/datatables/jquery.dataTables.js"></script>
    <script src="{{ asset('backEnd/') }}/plugins/datatables/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js "></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js "></script>
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    @yield('custom_js_scripts')

    <!-- Web-Fonts -->
    <script>
        $(function() {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

            //--------------
            //- AREA CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

            var areaChartData = {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                        label: 'Digital Goods',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [28, 48, 40, 19, 86, 27, 90]
                    },
                    {
                        label: 'Electronics',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: [65, 59, 80, 81, 56, 55, 40]
                    },
                ]
            }

            var areaChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }]
                }
            }

            // This will get the first returned node in the jQuery collection.
            var areaChart = new Chart(areaChartCanvas, {
                type: 'line',
                data: areaChartData,
                options: areaChartOptions
            })

            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
            var lineChartOptions = $.extend(true, {}, areaChartOptions)
            var lineChartData = $.extend(true, {}, areaChartData)
            lineChartData.datasets[0].fill = false;
            lineChartData.datasets[1].fill = false;
            lineChartOptions.datasetFill = false

            var lineChart = new Chart(lineChartCanvas, {
                type: 'line',
                data: lineChartData,
                options: lineChartOptions
            })

            //-------------
            //- DONUT CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData = {
                labels: [
                    'Chrome',
                    'IE',
                    'FireFox',
                    'Safari',
                    'Opera',
                    'Navigator',
                ],
                datasets: [{
                    data: [700, 500, 400, 600, 300, 100],
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                }]
            }
            var donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            var donutChart = new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            })

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData = donutData;
            var pieOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            var pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
            })

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })

            //---------------------
            //- STACKED BAR CHART -
            //---------------------
            var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
            var stackedBarChartData = $.extend(true, {}, barChartData)

            var stackedBarChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }

            var stackedBarChart = new Chart(stackedBarChartCanvas, {
                type: 'bar',
                data: stackedBarChartData,
                options: stackedBarChartOptions
            })
        })
    </script>
    <script>
        function calculate_result() {
            $.ajax({
                type: "GET",
                url: "{{ url('cost/calculate/result') }}",
                dataType: "html",
                success: function(deliverycharge) {
                    $('.calculate_result').html(deliverycharge)
                }
            });
        }
        $('.calculate').on('keyup paste click', function() {
            var cod = $('.cod').val();
            var weight = $('.weight').val();
            if (cod, weight) {
                $.ajax({
                    cache: false,
                    type: "GET",
                    url: "{{ url('cost/calculate') }}/" + cod + '/' + weight,
                    dataType: "json",
                    success: function(deliverycharge) {
                        return calculate_result();
                    }
                });
            }
        });
    </script>
    <script>
        flatpickr(".flatDate", {});
    </script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                dom: 'Bfrtip',
                sort: false,
                buttons: [{
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'Csv',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },

                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print all',
                        exportOptions: {
                            modifier: {
                                selected: null
                            }
                        }
                    },
                    {
                        extend: 'colvis',
                    },

                ],
                select: true
            });

            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script>
        function percelDelivery(that) {
            if (that.value == "6") {
                $('.partialpayment').show();
            } else {
                $('.partialpayment').hide();
            }
        }
    </script>
</body>

</html>
