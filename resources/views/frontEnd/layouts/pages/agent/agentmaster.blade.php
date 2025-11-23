<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Zidrop | @yield('title', 'Move Everywhere')</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, minimum-scale=1.0">
    <meta charset="UTF-8" />
    <meta name="keywords" content="" />
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
    <!-- Custom-Files -->
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/dist/css/custom.css?v=5.0">
    <!-- Bootstrap-Core-CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- flaticon -->
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/merchant.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/swiper-menu.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/dist/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/font-awesome.css">

    <!-- datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
    <!-- Style-CSS -->
    <link href="{{ asset('frontEnd') }}/css/fontawesome-all.min.css" rel="stylesheet">
    <!-- Font-Awesome-Icons-CSS -->
    <!-- //Custom-Files -->
    <script src="{{ asset('frontEnd/') }}/js/jquery_3.4.1_jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <!-- select2 -->
    @yield('extracss')

    <style>
        .pageStatusTitle {
            font-weight: bolder !important;
        }

        /* Spinner CSS */

        .p2p-button {
            color: #602D90 !important;
            font-weight: 600 !important;
            padding: 12px 25px !important;
        }

        .spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .spinner-circle {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .hidden {
            display: none;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    @php
        $agentInfo = App\Agent::find(Session::get('agentId'));
    @endphp
    <section class="mobile-menu ">
        <div class="swipe-menu default-theme">
            <div class="postyourad">
                <a href="{{ url('agent/dashboard') }}">
                    @foreach ($whitelogo as $key => $value)
                        <img src="{{ asset($value->image) }}" alt="Your logo" />
                    @endforeach
                </a>

                <a href="{{ url('agent/dashboard') }}" class="mobile-username">{{ $agentInfo->names }}</a>

            </div>

            <!--Navigation Icon-->
            <div class="nav-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav class="codehim-nav">
                <ul class="menu-item">
                    <li><a href="{{ url('agent/dashboard') }}">Dashboard</a>
                    </li>



                    @foreach ($parceltypes as $parceltype)
                    @php
                        // Skip if ID is 11 or slug is 'paid'
                        if ($parceltype->id == 11 || in_array($parceltype->slug, ['paid'])) {
                            continue;
                        }

                        // Apply agentId filter only if parceltype ID is not 1
                        $parcelcount = \App\Parcel::where('status', $parceltype->id)
                            ->when($parceltype->id != 1, function ($query) {
                                $query->where('agentId', Session::get('agentId'));
                            })
                            ->count();
                    @endphp

                    <li class="nav-item">
                        <a href="{{ url('agent/parcel', $parceltype->slug) }}">
                            {{ $parceltype->title }} ({{ $parcelcount }})
                        </a>
                    </li>
                @endforeach

                    {{-- @foreach ($parceltypes as $parceltype)
                        @if ($parceltype->id == 11)
                            @continue
                        @endif
                        @php
                            $parcelcount = App\Parcel::where([
                                'status' => $parceltype->id,
                                'agentId' => Session::get('agentId'),
                            ])->count();
                        @endphp
                        <li class="nav-item">
                            @php
                                if (in_array($parceltype->slug, ['paid'])) {
                                    continue;
                                }
                            @endphp
                            <a href="{{ url('agent/parcel', $parceltype->slug) }}">

                                {{ @$parceltype->title }} ({{ $parcelcount }})
                            </a>
                        </li>
                    @endforeach --}}



                    <li>
                        <br>
                        <br>
                        <br>
                    </li>

                    <li>
                        <br>
                        <br>
                        <br>
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
                    @php
                        if ($agentInfo->image) {
                            $img = $agentInfo->image;
                        } else {
                            $img = 'frontEnd/images/avator.png';
                        }
                    @endphp
                    <div class="profile-pic">
                        <a href="#"><img src="{{ asset($img) }}" alt=""></a>
                    </div>
                    <div class="profile-id">



                        <p>{{ $agentInfo->name }}: {{ $agentInfo->id }}</p>

                    </div>
                    <div class="dashboard-button">
                        <a href="{{ url('agent/dashboard') }}">Dashboard</a>
                    </div>
                </div>
                <div class="side-list">
                    <ul>

                        <?php
                        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;
                        ?>


                        @if ($agentsettings && $agentsettings->agent_create_parcel == 1 && $agentsettings->agent_create_parcel != null)
                            <li>
                                <a href="{{ route('agent.parcel-create') }}">
                                    <i class="fas fa-pen-square"></i>
                                    Book Shipment
                                </a>
                            </li>
                        @endif
                        {{-- @if ($agentsettings && $agentsettings->p2p_permission == 1 && $agentsettings->p2p_permission != null)
                            <li>
                                <a href="{{ route('agent.p2p-create') }}">
                                    <i class="fas fa-pen-square"></i>
                                    Create P2P
                                </a>
                            </li>
                        @endif --}}
                        <li>
                            <a href="{{ url('agent/parcels') }}">
                                <i class="fa fa-car"></i>
                                All Shipments
                            </a>
                        </li>


                        @foreach ($parceltypes as $parceltype)
    @if ($parceltype->id == 11)
        @continue
    @endif

    @php
        $parcelcount = \App\Parcel::where('status', $parceltype->id)
            ->when($parceltype->id != 1, function ($query) {
                $query->where('agentId', Session::get('agentId'));
            })
            ->count();
    @endphp

    <li class="nav-item">
        <a href="{{ url('agent/parcel', $parceltype->slug) }}">
            <i class="fa fa-circle-notch"></i>
            {{ $parceltype->title }} ({{ $parcelcount }})
        </a>
    </li>
@endforeach


                        {{-- @foreach ($parceltypes as $parceltype)
                            @if ($parceltype->id == 11)
                                @continue
                            @endif
                            @php
                                $parcelcount = App\Parcel::where([
                                    'status' => $parceltype->id,
                                    'agentId' => Session::get('agentId'),
                                ])->count();
                            @endphp
                            <li class="nav-item">
                                <a href="{{ url('agent/parcel', $parceltype->slug) }}">
                                    <i class="fa fa-circle-notch"></i>
                                    {{ @$parceltype->title }} ({{ $parcelcount }})
                                </a>
                            </li>
                        @endforeach --}}

                        <li>
                            <a href="{{ url('agent/profile/request-paid') }}">
                                <i>
                                    <img src="{{ asset('paystack-icon.png') }}" alt="" width="16px"
                                        class="img-fluid">
                                </i>
                                Verify Payments
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('agent.payment.commission_history', Session::get('agentId')) }}">
                                <i>
                                    <img src="{{ asset('naira.png') }}" alt="" width="16px"
                                        class="img-fluid">
                                </i>
                                Commission History
                            </a>

                        </li>

                        <li>
                            <a href="{{ route('transhubrptview') }}">
                                <i class="fas fa-chart-pie"></i>
                                TTH Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('returnhubrptview') }}">
                                <i class="fas fa-chart-pie"></i>
                                RTOH Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transmerchantrptview') }}">
                                <i class="fas fa-chart-pie"></i>
                                RTM Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('assigntodeltview') }}">
                                <i class="fas fa-chart-pie"></i>
                                ATD Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('agentexpense.index') }}">
                                <i class="fa fa-credit-card"></i>
                                Expense
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('agent/profile/settings') }}">
                                <i class="fa fa-cogs"></i>
                                Settings
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('agent/logout') }}">
                                <i class="fa fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </li>


                        <li>
                            <br>
                            <br>
                        </li>
                        <li>
                            <br>
                            <br>
                        </li>
                        <li>
                            <br>
                            <br>
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
                                @if ($agentsettings && $agentsettings->p2p_permission == 1 && $agentsettings->p2p_permission != null)
                                    <li class="">
                                        <div class="track-area">
                                            <a href="{{ route('agent.p2p-create') }}"
                                                class="p2p-button form-control"><i class="fa fa-car"></i> Book P2P</a>
                                        </div>

                                    </li>
                                @endif

                                <li>
                                    <div class="track-area">
                                        <form action="{{ url('/agent/parcel/track') }}" method="POST">
                                            @csrf
                                            <input class="form-control" type="text" name="trackid"
                                                placeholder="Search your track number..." search>
                                            <button>Submit</button>
                                        </form>
                                    </div>

                                </li>
                                <li class="profile-area">
                                    <div class="profile">
                                        @php
                                            if ($agentInfo->image) {
                                                $img = $agentInfo->image;
                                            } else {
                                                $img = 'frontEnd/images/avator.png';
                                            }
                                        @endphp
                                        <a class=""><img src="{{ asset($img) }}" alt="">

                                        </a>
                                        <ul>
                                            <li><a href="{{ url('agent/profile/edit') }}">Setting</a></li>
                                            <li><a href="{{ url('agent/logout') }}">Logout</a></li>
                                        </ul>
                                    </div>

                                </li>
                            </ul>
                        </div>
                    </div>






                </div>



            </div>

            <div class="main-body" style="padding: 0px;">
                <div class="col-sm-12">
                    <div class="container-fluide mobile-men">
                        <div class="row">
                            <div class="col-sm-12" style="background-color:#dc3545; padding-top:7px;"
                                onmouseover="document.getElementById('noticeMarquee').stop();"
                                onmouseout="document.getElementById('noticeMarquee').start();">
                                <marquee id="noticeMarquee" style="font-weight: bold; color: white;"
                                    class="marqueeTagDIv">
                                    {{ @$agentNotice->title }}
                                </marquee>
                            </div>
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

    <!-- Spinner HTML -->

    <div id="spinner" class="spinner hidden">
        <div class="spinner-circle"></div>
    </div>

    <!--Next Day Pick Modal end -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="{{ asset('frontEnd/') }}/js/bootstrap4.min.js"></script>
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
    <script>
        function percelDelivery(that) {
            if (that.value == "6") {
                $('.partialpayment').show();
            } else {
                $('.partialpayment').hide();
            }
        }
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



    <!-- ChartJS -->
    <script src="{{ asset('backEnd/') }}/plugins/select2/js/select2.full.min.js"></script>
    <!-- Select2 -->
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2();
            $('#example1').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true,

            });

        })
    </script>
    <script type="text/javascript">
        $("#search_data").on('keyup', function() {
            var keyword = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ url('/') }}/search_data/" + keyword,
                data: {
                    keyword: keyword
                },
                success: function(data) {
                    console.log(data);
                    $("#live_data_show").html('');
                    $("#live_data_show").html(data);
                }
            });
        });
    </script>
    <script src="{{ asset('js/common.js') }}"></script>
    <script>
        function myPrintFunction() {
            window.print();
        }
    </script>
    <script>
        jQuery("#My-Button").click(function() {
            jQuery(':checkbox').each(function() {
                if (this.checked == true) {
                    this.checked = false;
                } else {
                    this.checked = true;
                }
            });
        });

        // Comma Seperate Numeric Value 
        // Select all elements with the class 'CommaSeperateValueSet'
        const productValueInputs = document.getElementsByClassName('CommaSeperateValueSet');

        // Loop through each element and add an event listener
        Array.from(productValueInputs).forEach((productValueInput) => {
            productValueInput.addEventListener('input', function(e) {
                let value = e.target.value;

                // Remove any non-numeric characters, including commas
                value = value.replace(/,/g, '');

                // Format the number with commas
                const formattedValue = Number(value).toLocaleString('en-US');

                // Update the input field
                e.target.value = formattedValue;
            });
        });

        function convertCommaSeparatedToNumber(value) {
            if (!value) return 0; // Return 0 for empty or undefined values
            // Convert the value to a string, remove commas, and parse as a number
            return Number(String(value).replace(/,/g, '')) || 0;
        }
    </script>
    @yield('custom_js_scripts')

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                dom: 'Bfrtip',
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
</body>

</html>
