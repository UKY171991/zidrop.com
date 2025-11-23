<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Zidrop Logistics || @yield('title', 'Dashbaord')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Font Awesome -->
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon.png') }}">
    <!-- fabeicon css -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/font-awesome.css">
    <!-- Ionicons -->
    <link rel="stylesheet"
        href="{{ asset('backEnd/') }}/plugins/code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('backEnd/') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/dist/css/adminlte.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/summernote/summernote-bs4.css">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/select2/css/select2.min.css">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/owlcarousel/owl.carousel.css">
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/plugins/owlcarousel/owl.theme.default.min.css">
    <!-- owl.theme.default.min -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- flatpickr -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/dist/css/toastr.min.css">
    <!-- datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/dist/css/custom.css?v=5.0">


    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('extracss')
    <style>
        .row.total-bar {
            border-top: 2px solid #222;
            margin-top: 15px;
            padding-top: 15px;
            margin-bottom: 15px;
        }

        #example333 thead tr th {
            background-color: #4634FF !important;
            color: #fff !important;
        }

        #deliverymanexample_manage thead tr th {
            background-color: #4634FF !important;
            color: #fff !important;
        }

        #merchantexample thead tr th {
            background-color: #4634FF !important;
            color: #fff !important;
        }

        thead tr th {
            background-color: #4634FF !important;
            color: #fff !important;
        }

        .swal-footer {
            text-align: center !important;
        }

        .swal-title {
            font-weight: 700 !important;
            font-size: 38px !important;
        }

        .swal-text {
            font-size: 22px !important;
            text-align: center !important;
        }

        .input-group {
            width: 100%;
        }

        .pageStatusTitle {
            font-weight: bolder !important;
        }
        #spinnerr {
    display: none; /* Hide spinner initially */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999; /* Ensure it appears above everything */
}
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed" onload="startTime()">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">

            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>




                <li class="nav-item d-none d-sm-inline-block">

                </li>


                <!-- nav item end -->
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('superadmin/dashboard') }}" class="nav-link" title="Dashboard">
                        <i class="fa fa-home" style="font-size:27px"></i>
                    </a>
                </li>




                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/editor/new/parcel-create') }}" class="nav-link">


                        <img src="{{ asset('compose.png') }}" alt="" width="18px" class="img-fluid pb-1">
                        Parcel Create
                    </a>
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link" type="button" id="AddExpensePublicButton">
                        <i class="fa fa-plus"></i>
                        Create Expense </a>

                </li>

                <!-- nav item end -->
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('author/topup/history') }}" class="nav-link">

                        <img src="{{ asset('wallet.png') }}" alt="" width="18px" class="img-fluid pb-1">

                        Wallet
                    </a>
                </li>

                <!-- nav item end -->



                <li class="nav-item d-none d-sm-inline-block active">
                    <a href="{{ url('editor/parcel/all') }}" class="nav-link" title="Dashboard">

                        <img src="{{ asset('task-actions.png') }}" alt="" width="18px"
                            class="img-fluid pb-1">
                        All Parcel

                    </a>
                </li>

                <!-- nav item end -->



                <!--
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('editor/parcel/pending') }}" class="nav-link"><i class="fa fa-globe"></i>Pending
        ({{ $newpickup->count() }})
        </a>
      </li>
      
        -->


            </ul>


            <ul class="navbar-nav ml-auto">


                <!-- nav item end -->
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/cc') }}" target="_blank" class="nav-link"><i class="fas fa-eraser"></i>
                        Clear-Cache</a>
                </li>

                <!-- nav item end -->

                <!-- nav item end -->
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="https://cloudhost-1743976.uk-south-2.nxcli.net:2443/roundcube/" title=" Open Web Mail"
                        target="_blank" class="nav-link">
                        <i class="fa fa-envelope"></i>
                    </a>

                </li>

                @php
                    $requestpayment = App\Agentpayment::where('status', 0)->count();
                @endphp
                <li class="nav-item has-treeview">
                    <a href="{{ url('editor/agent/payment-request') }}" class="nav-link"
                        title="Hub Payment Confirmation">
                        <i>
                            <img src="{{ asset('naira.png') }}" alt="" width="18px"
                                class="img-fluid pb-1">
                        </i>
                        <span class="text-danger">
                            <b>
                                {{ $requestpayment }}
                            </b>
                        </span>
                    </a>
                </li>

                <!-- nav item end -->
                <li class="nav-item has-treeview">
                    <a href="{{ url('editor/new/pickup') }}" class="nav-link" title=" Pickup Notification">
                        <i class="fas fa-bell"></i>
                        {{ $newpickup->count() }}
                    </a>
                </li>

                <!-- nav item end -->
                <li class="nav-item has-treeview">
                    <a id="goFS" class="nav-link anchor" title="Full Screen" data-widget="fullscreen">
                        <i class="fas fa-expand"></i>
                    </a>
                </li>


                <!-- nav item end ----------------------------------------------------------
        <li class="nav-item has-treeview">
           <a  id="myBtn" class="nav-link anchor" title="Lock Screen" data-widget="lockscreen">
           <i class="fas fa-lock"></i>
          </a>
       </li>
       -->



                <!-- nav item end -->
                <li class="nav-item has-treeview">
                    <a href="{{ url('password/change') }}" class="nav-link" title="Change Password">
                        <i class="fas fa-key"></i>
                    </a>
                </li>



                <!-- nav item end ----------------------------------------------------------------------------------------
        
        <li class="nav-item has-treeview">
            <a href="{{ route('logout') }}" title="Logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
             <i class="fas fa-sign-out-alt"></i>
             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
             </form>
            </a>
       </li>
       
       -->





                <li class="nav-item has-treeview">
                    {{-- <a href="{{ route('logout') }}" title="Logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
             <i class="fa fa-sign-out" style="color:red"></i>
             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
             </form>
            </a> --}}
                    <a href="{{ route('admin.logout') }}" title="Logout" class="nav-link"> <i
                            class="fa fa-sign-out" style="color:red"></i> </a>
                </li>







                <!-- nav item end -->
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('/superadmin/dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-light">Zidrop Logistics</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="user-image">
                        <img src="{{ asset(auth::user()->image) }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="user-info">
                        <a href="#" class="d-block">{{ auth::user()->name }}</a>
                        <i class="fas fa-circle"></i>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <!-- nav item end -->
                        @if (Auth::user()->role_id == 1)
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-cog"></i>
                                    <p>
                                        App Setting
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/contact_info/create') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Contact Info</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ url('/editor/logo/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Logo</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/banner/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Banner</p>
                                        </a>
                                    </li>
                                    {{-- <li class="nav-item">
                                        <a href="{{ url('/editor/feature/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Feature</p>
                                        </a>
                                    </li> --}}
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/price/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <!--<p>Pricing</p> -->
                                            <p>Why ZiDrop</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/about/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>About</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/clientfeedback/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Client Feedback</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/service/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Service</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/notice/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Notice</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/gallery/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Gallery</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/partner/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Partner</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/social-media/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Social Icon</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('superadmin.smtp.configuration.show') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>SMTP Configuration</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('2fa') }}" class="nav-link">&nbsp;&nbsp;&nbsp;
                                            {{-- <i class="fa fa-lock"></i>&nbsp; --}}
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>2-Step Verification</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('status-description.index') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            {{-- <i class="fa fa-lock"></i>&nbsp; --}}
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Status Description Page</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.refresh') }}" class="nav-link"> &nbsp;&nbsp;&nbsp;
                                            <!-- <i class="fa fa-clock-o"></i> -->
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Auto Refresh</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/api_info/create') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Payment API Info</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('statistics-details.index') }}"
                                            class="nav-link">&nbsp;&nbsp;&nbsp;
                                            <i class="fas fa-angle-right"></i>&nbsp;
                                            <p>Statistics Details</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- nav item end -->
                            <!-- nav item end -->





                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-user-tie"></i>
                                    <p>
                                        Panel User
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('/superadmin/user/add') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p> Add</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/superadmin/user/manage') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-gears"></i>&nbsp;
                                            <p>Manage</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- nav item end -->
                        @endif

                        @if (Auth::user()->role_id <= 4)
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-briefcase"></i>
                                    <p>
                                        Merchant
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                        @endif





                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('merchant/register') }}" class="nav-link">&nbsp;&nbsp;
                                    <i class="fa fa-plus"></i>&nbsp;
                                    <p>Add</p>
                                </a>



                            <li class="nav-item">
                                <a href="{{ url('/author/merchant/manage') }}" class="nav-link">&nbsp;&nbsp;
                                    <i class="fas fa-cog"></i>&nbsp;
                                    <p>Manage</p>
                                </a>

                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ url('/admin/deliverycharge/manage') }}" class="nav-link">&nbsp;&nbsp;
                                    <i class="fas fa-cog"></i>&nbsp;
                                    <p>Merchant charge manage</p>
                                </a>
                            </li> --}}

                            <li class="nav-item">
                                <a href="{{ url('/author/merchant/notice') }}" class="nav-link">&nbsp;&nbsp;
                                    <i class="fas fa-cog"></i>&nbsp;
                                    <p>Merchant Notice</p>
                                </a>

                            </li>


                        </ul>
                        </li>
                        <!-- nav item end -->


                        @if (Auth::user()->role_id <= 4)
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-users"></i>
                                    <p>
                                        Deliveryman
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/deliveryman/add') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p>Add</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/deliveryman/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;
                                            <i class="fas fa-cog"></i>&nbsp;
                                            <p>Manage</p>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </li>
                            <!-- nav item end -->
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-users"></i>
                                    <p>
                                        Agents/Hubs
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('/author/agent/add') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p>Add</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/author/agent/manage') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fas fa-cog"></i>&nbsp;
                                            <p>Manage</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('editor/agent/payment-request') }}"
                                            class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-gears"></i>&nbsp;
                                            <p>Manage Payments</p>
                                        </a>
                                    </li>
                                  
                                    <li class="nav-item">
                                        <a href="{{ url('admin/branch') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fas fa-home"></i> &nbsp;

                                            <p>Branches</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ url('/author/agent/notice') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fas fa-home"></i> &nbsp;

                                            <p>Agent/Hub Notice</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <!-- nav item end -->
                        @endif

                        @if (Auth::user()->role_id <= 2)
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/admin/deliverycharge/add') }}" class="nav-link">&nbsp;&nbsp;
                                        <i class="fa fa-plus"></i>&nbsp;
                                        <p>Service Type Add</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/admin/deliverycharge/manage') }}" class="nav-link">&nbsp;&nbsp;
                                        <i class="fas fa-cog"></i>&nbsp;
                                        <p>Service TypeManage</p>
                                    </a>
                                </li>
                            </ul>

                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-map-marker"></i>
                                    <p>
                                        Cities/Towns
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.charge-tarif') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p>City Tariff</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.town-tarif') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p>Town Tariff</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin-city.index') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p>City</p>
                                        </a>
                                    </li>


                                </ul>
                            </li>
                            {{-- <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-map-marker"></i>
                                    <p>
                                        State/Delivery Area
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/deliverycharge/add') }}"
                                            class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p>State Add</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ url('/admin/deliverycharge/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;
                                            <i class="fas fa-cog"></i>&nbsp;
                                            <p>State Manage</p>
                                        </a>
                                    </li>


                                    <li class="nav-item">
                                        <a href="{{ url('/admin/nearestzone/add') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p>Area Add</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/nearestzone/manage') }}"
                                            class="nav-link">&nbsp;&nbsp;
                                            <i class="fas fa-cog"></i>&nbsp;
                                            <p>Area Manage</p>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                        @endif





                        <!-- nav item end -->
                        @if (Auth::user()->role_id != 3)
                            <!-- <li class="nav-item has-treeview">

            

            <a href="#" class="nav-link">
            <i class="fas fa-chart-pie"></i>
              <p>
               HR
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
          

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/author/department/manage') }}" class="nav-link">&nbsp;&nbsp;
                <i class="fas fa-users-cog"></i>&nbsp;
                    <p>Department</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/author/employee/manage') }}" class="nav-link">&nbsp;&nbsp;
                <i class="fas fa-users"></i>&nbsp;
                    <p>Employee</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/author/agent/manage') }}" class="nav-link">&nbsp;&nbsp;
                <i class="fas fa-user-friends"></i>&nbsp;
                    <p>Agent</p>
                </a>
              </li>
            </ul>
          </li> -->
                        @endif
                        <!-- nav item end -->

                        {{-- <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-tie"></i>
                                <p>
                                    Merchant Payment
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                
                            </ul>
                        </li> --}}
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <p>
                                    Payments
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('editor/merchant/payment') }}" class="nav-link">&nbsp;&nbsp;
                                        <i class="fa-solid fa-naira-sign"></i>&nbsp;
                                        <p>Merchant Payment</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('editor/merchant/returned_merchant') }}"
                                        class="nav-link">&nbsp;&nbsp;
                                        <i class="fa-solid fa-naira-sign"></i>&nbsp;
                                        <p>Returned to Merchant</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('editor.agent.agent_payment_list') }}" class="nav-link">&nbsp;&nbsp;
                                        <i class="fa-solid fa-naira-sign"></i>&nbsp;
                                        <p>Agent Payment</p>
                                    </a>
                                </li>     
                                
                                <li class="nav-item">
                                    <a href="{{ route('editor.deliveryman.deliveryman_payment_list') }}" class="nav-link">&nbsp;&nbsp;
                                        <i class="fa-solid fa-naira-sign"></i>&nbsp;
                                        <p>Deliveryman Payment</p>
                                    </a>
                                </li>     
                                       
                            </ul>
                        </li>
                        

                        <!-- <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-user-tie"></i>
              <p>
                Agent Payment
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('editor/agent/payment-request') }}" class="nav-link">&nbsp;&nbsp;
                 <i class="fa fa-gears"></i>&nbsp;
                    <p>Manage Payments</p>
                </a>
              </li>
            </ul>
          </li> -->

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-tie"></i>
                                <p>
                                    Wallet System
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('author/topup/history') }}" class="nav-link">&nbsp;&nbsp;
                                        <i class="fa fa-gears"></i>&nbsp;
                                        <p>Topup Management</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if (Auth::user()->role_id <= 4)
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-gift"></i>
                                    <p>
                                        Parcel Manage
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    <li class="nav-item">
                                        <a href="{{ url('/editor/new/parcel-create') }}" class="nav-link">
                                            <i class="fas fa-cicle-o"></i>
                                            <p>Parcel Create</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/parcel/all') }}" class="nav-link">
                                            <i class="fas fa-cicle-o"></i>
                                            <p>All Parcel</p>
                                        </a>
                                    </li>

                                    @foreach ($parceltypes as $parceltype)
                                        @if ($parceltype->id == 11)
                                            @continue
                                        @endif
                                        @php
                                            $parcelcount = App\Parcel::where('status', $parceltype->id)->count();
                                        @endphp
                                        <li class="nav-item">
                                            <a href="{{ url('editor/parcel', $parceltype->slug) }}" class="nav-link">
                                                <i class="fas fa-cicle-o"></i>
                                                <p>{{ $parceltype->title }} ({{ $parcelcount }})</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <!-- nav item end -->


                            <!-- nav item end -->

                        @endif

                        @if (Auth::user()->role_id <= 3)
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-truck"></i>
                                    <p>
                                        Pickup Request
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/new/pickup') }}" class="nav-link">
                                            <i class="fas fa-cicle-o"></i>
                                            <p>New Pickup ({{ $newpickup->count() }})</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/pending/pickup') }}" class="nav-link">
                                            <i class="fas fa-cicle-o"></i>
                                            <p>Pending Pickup ({{ $pendingpickup->count() }})</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/accepted/pickup') }}" class="nav-link">
                                            <i class="fas fa-cicle-o"></i>
                                            <p>Accepted Pickup ({{ $acceptedpickup->count() }})</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/cancelled/pickup') }}" class="nav-link">
                                            <i class="fas fa-cicle-o"></i>
                                            <p>Cancelled Pickup ({{ $cancelledpickup->count() }})</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-chart-pie"></i>
                                <p>
                                    Reports
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.salse') }}" class="nav-link">
                                        <i class="fas fa-cicle-o"></i>
                                        <p>Income</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('expense-report') }}" class="nav-link">
                                        <i class="fas fa-cicle-o"></i>
                                        <p>Expense</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-chart-pie"></i>
                                <p>
                                    Expense
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('expense.index') }}" class="nav-link">
                                        <i class="fas fa-cicle-o"></i>
                                        <p>Expense</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('expense-type.index') }}" class="nav-link">
                                        <i class="fas fa-cicle-o"></i>
                                        <p>Expense Type</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if (Auth::user()->role_id <= 2)
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-award"></i>
                                    <p>
                                        Note
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/note/create') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fa fa-plus"></i>&nbsp;
                                            <p>Add</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/editor/note/manage') }}" class="nav-link">&nbsp;&nbsp;
                                            <i class="fas fa-cog"></i>&nbsp;
                                            <p>Manage</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- nav item end -->
                        @endif
                        @if (Auth::user()->role_id <= 2)
                            {{-- <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-cog"></i>
                                <p>
                                    Settings
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('superadmin.settings') }}" class="nav-link">&nbsp;&nbsp;
                                        <i class="fas fa-cog"></i>&nbsp;
                                        <p>Settings</p>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}



                            <!-- nav item end -->
                        @endif
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('content')
            
        </div>
        <style>
         
        </style>
        <!-- Spinner HTML -->
        <div id="spinnerr" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="search-product-inner" id="live_data_show"></div>

        <footer class="main-footer">
            <strong>Copyright &copy;<a href="{{ url('/') }}">ZiDrop Logistics</a></strong>
        </footer>
        <!--Add/Edit Expense Modal -->
        <div class="modal fade" id="ExpensepublicModal" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('expense.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="ExpenseModalLabel">Add Expense</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row  mb-2">
                                <div class="col-md-12 mb-2">
                                    <label for="title" class="form-label">Expense Title <strong
                                            class="text-denger">*</strong></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        placeholder="Enter Expense Title" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="expense_number" class="form-label">Expense Number<strong
                                            class="text-denger">*</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">#EXP-</span>
                                        </div>
                                        <input type="text" name="expense_number" id="expense_number"
                                            class="form-control" style=""
                                            placeholder="Auto-generated with prefix #EXP-"
                                            value="{{ $PubliclastExpNUmber }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Expense Type<strong
                                            class="text-denger">*</strong></label>
                                    <select name="expense_type_id" id="expense_type_id" class="form-control"
                                        required>
                                        <option value="">Select Expense Type</option>
                                        @foreach ($PublicExpenseTypes as $expense_type)
                                            <option value="{{ $expense_type->id }}">{{ $expense_type->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Vehicle<strong
                                            class="text-denger">*</strong></label>
                                    <input type="text" name="vehicle" id="vehicle" class="form-control"
                                        required>
                                </div>
                                {{-- <div class="col-md-6 mb-2">
                    <label for="title" class="form-label">Unit<strong
                            class="text-denger">*</strong></label>
                    <input type="text" name="unit" id="unit" class="form-control" >
                </div> --}}
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Date<strong
                                            class="text-denger">*</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Amount<strong
                                            class="text-denger">*</strong></label>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                        required step="any">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Receipt(less than 1000KB)</label>
                                    <input type="file" name="receipt_file" id="receipt_file"
                                        class="form-control">
                                    {{-- show image for edit --}}
                                    <div class="mt-2" id="showImage"></div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="title" class="form-label">Note<strong
                                            class="text-denger">*</strong></label>
                                    <textarea name="note" id="note" class="form-control" required></textarea>
                                </div>

                            </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-secondary CloseModal"
             data-dismiss="modal">Close</button> --}}
                                <button type="submit" class="btn btn-primary py-1">Save</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="{{ asset('backEnd/') }}/plugins/jquery/jquery.min.js"></script>
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('backEnd/') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <!-- Bootstrap 4 -->
        <script src="{{ asset('backEnd/') }}/plugins/bootstrap/js/popper.min.js"></script>

        <script src="{{ asset('backEnd/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('backEnd/') }}/dist/js/adminlte.js"></script>
        <!-- Summernote -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/ckeditor.js"></script>
        <script>
            // Replace the <textarea id="editor1"> with a CKEditor 4
            // instance, using default configuration.
            CKEDITOR.replace('editor1');
        </script>
        <!-- overlayScrollbars -->
        <script src="{{ asset('backEnd/') }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- FastClick -->
        <script src="{{ asset('backEnd/') }}/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('backEnd/') }}/plugins/owlcarousel/owl.carousel.min.js"></script>
        <!-- Datatable -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="{{ asset('backEnd/') }}/plugins/bootstrap/js/bootstrap.min.js"></script>
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
        <!-- flatpicker -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        {{-- QR --}}
        <script>
            $(function() {
                flatpickr("#flatpicker", {
                    minDate: "today",
                });
            })
        </script>
        <script>
            flatpickr(".flatDate", {});
        </script>
        <script src="{{ asset('backEnd/') }}/plugins/select2/js/select2.full.min.js"></script>
        <!-- Select2 -->
        <script src="{{ asset('backEnd/') }}/plugins/chart.js/Chart.min.js"></script>
        <script src="{{ asset('backEnd/') }}/plugins/sparklines/sparkline.js"></script>
        <script src="{{ asset('backEnd/') }}/dist/js/toastr.min.js"></script>
        {!! Toastr::message() !!}
        <script src="{{ asset('backEnd/') }}/dist/js/demo.js"></script>
        <script src="{{ asset('js/common.js') }}"></script>


        <!-- ChartJS -->
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
        <script>
            function percelDelivery(that) {
                if (that.value == "6") {
                    $('.partialpayment').show();
                } else {
                    $('.partialpayment').hide();
                }
            }
        </script>
        {{-- <script>
  function percelDelivery(that) {
    if (that.value == "4") {
            document.getElementsByClassName("customerpaid").style.display = "block";
        } else {
            document.getElementsByClassName("customerpaid").style.display = "none";
        }
    }
</script> --}}
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
        </script>
        @yield('custom_js_scripts')
        <script>
            $(document).ready(function() {
                $('#example').DataTable({
                    dom: 'Bfrtip',
                    "lengthMenu": [
                        [200, 500, -1],
                        [200, 500, "All"]
                    ],
                    buttons: [{
                            extend: 'copy',
                            text: 'Copy',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'D_Man',
                            exportOptions: {
                                columns: [1, 3, 4, 5, 7, 8, 10, 14]
                            }
                        },

                        {
                            extend: 'print',
                            text: 'Print',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
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
        <!-- page script -->
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
                var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
                var lineChartData = jQuery.extend(true, {}, areaChartData)
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
                var barChartData = jQuery.extend(true, {}, areaChartData)
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
                var stackedBarChartData = jQuery.extend(true, {}, barChartData)

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
            var goFS = document.getElementById("goFS");
            goFS.addEventListener("click", function() {
                document.body.requestFullscreen();
            }, false);
            $('#example333 thead tr th').css('background-color', '#4634FF');
            $('#deliverymanexample_manage thead tr th').css('background-color', '#4634FF');
            $('#merchantexample thead tr th').css('background-color', '#4634FF');
            $('thead tr th').css('background-color', '#4634FF');
        </script>

        {{-- Sweet Alert --}}
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script type="text/javascript">
            $('.show_confirm').click(function(event) {
                var form = $(this).closest("form");
                var name = $(this).data("name");
                event.preventDefault();
                swal({
                    title: "Are you sure you want to delete this record?",
                    text: "This record cannot be restored after deletion. Do you want to confirm?",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            value: null,
                            visible: true,
                            className: "btn-secondary",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Delete",
                            value: true,
                            visible: true,
                            className: "btn-danger",
                            closeModal: false,
                        },
                    },
                    dangerMode: true,
                    customClass: {
                        title: 'sweetAlTilteClass',
                        content: 'sweetAlContentClass',
                        actions: 'your-custom-actions-class',
                    },
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });
        </script>
        {{-- expense modal open --}}
        <script>
            $(document).ready(function() {
                $('#AddExpensePublicButton').on('click', function() {
                    $('#ExpensepublicModal').modal('show');
                });
            })
            $(document.body).on('click', '#ExpensepublicModal .close', function(e) {
                e.preventDefault();
                // Listen for the 'hidden.bs.modal' event
                $('#ExpensepublicModal').on('hidden.bs.modal', function() {
                    // Remove the 'hidden.bs.modal' event listener to avoid multiple bindings
                    $(this).off('hidden.bs.modal');
                });

                // Hide the modal
                $('#ExpensepublicModal').modal('hide');
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

</body>

</html>
