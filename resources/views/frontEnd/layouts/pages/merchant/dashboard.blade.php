@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title', 'Dashboard')
@section('content')


    <style>
        /*body {
                    font-family: Arial, sans-serif;
                    margin: 50px;
                }*/

        .tooltip-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
            color: #909BA3;
        }

        .tooltip-container .tooltip-text {
            visibility: hidden;
            opacity: 0;
            /*            background-color: #333;*/
            background-color: #5a6770;
            color: #fff;
            text-align: left;
            padding: 12px 15px;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.5;
            position: absolute;
            top: 100%;
            /* Position below the element */
            left: 50%;
            transform: translateX(-50%);
            white-space: normal;
            /* Allow multiline content */
            width: 300px;
            /* Set a fixed width to handle long content */
            z-index: 1000;
            transition: opacity 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: sans-serif;
            font-weight: 400;
            font-size: .875rem;
        }

        .tooltip-container .tooltip-text::after {
            content: '';
            position: absolute;
            top: -11px;
            /* Position the arrow above the tooltip */
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent #5a6770 transparent;
        }

        .tooltip-container:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
            transform: translateX(-50%) translateY(10px);
            /* Slight downward motion */
        }

        @media (max-width: 768px) {
            .tooltip-container {
                position: relative;
                display: inline-block;
                cursor: pointer;
                float: right;
            }

            .tooltip-container .tooltip-text {
                left: -121px;
            }

            .tooltip-container .tooltip-text::after {
                right: 5%;
                left: 93%;
            }
        }
    </style>

    <style>
        @media screen and (min-width: 520px) and (max-width: 767px) {
            .mobile-men {
                margin-top: 52px;
            }

            .col-md-25 {
                width: 33%;
            }
        }

        @media screen and (max-width: 519px) {

            .col-md-25 {
                width: 50%;
            }
        }

        @media screen and (min-width: 768px) {

            /* 5 box in a row */
            .col-md-25 {
                width: 20%;
            }
        }

        table {
            font-size: 12px;
            width: 100%;
        }

        .stats-reportList b {
            font-size: 26px !important;
            font-weight: 700 !important;
            color: #fff !important;
        }

        .info-box.box-bg-mda {
            background: #E2E2E2;
        }

        .info-box.box-bg-rmd {
            background: #E2E2E2;
        }

        .info-box {
            vertical-align: bottom !important;
            height: 70px !important;
        }

        .info-box {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            border-radius: .25rem;
            background: #fff;
            min-height: 70px;
            position: relative;
        }

        .info-box-icon_img {
            width: 55px;
            height: 55px;
            color: wheat !important;
        }

        .info-box .info-box-icon {
            border-radius: .25rem;
            display: block;
            font-size: 1.875rem;
            text-align: center;
            width: 70px;
        }

        .info-box .info-box-content {
            -ms-flex: 1;
            flex: 1;
            padding: 5px 10px;
        }

        .info-box-content {
            padding-top: 0px !important;
            display: inline;
        }

        .info-box-content b {
            font-size: 26px !important;
            font-weight: 700 !important;
        }

        .paymentRow {
            margin-top: 1px !important;
            padding-top: 0px !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .paymentRow .col-md-4 {
            padding: 7px !important;
        }

        .info-box {
            padding: 0.5rem !important;
        }

        .info-box-icon_img {
            width: 40px;
            height: 40px;
            color: wheat !important;
        }

        .info-box-content b {
            font-size: 19px !important;
            font-weight: 700 !important;
        }

        .info-box .info-box-icon {
            width: 30px !important;
        }
        .canvasjs-chart-credit{
            display: none !important;
        }
        .main-body {
            background-color: white;
        }

    </style>

    <section class="section-padding" style="margin-bottom:0px">
        <b style="text-decoration: none;text-transform:uppercase; font-weight:1000; color:#191714a8">Transaction count for
            {{ date('F') }},
            {{ date('Y') }}</b>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel_month/pending?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color: #D21F3C">
                            <p class="text-center text-light">Pending</p>
                            <p class="text-center text-light"><b>{{ $m_pending == 0 ? 0 : $m_pending }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel_month/in-transit?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color:#495DDF">
                            <p class="text-center text-light">In Transit</p>
                            <p class="text-center text-light"><b>{{ $m_pick == 0 ? 0 : $m_pick }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel_month/arrived-at-hub?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color:#004953">
                            <p class="text-center text-light">Arrived At Hub</p>
                            <p class="text-center text-light"><b>{{ $m_da == 0 ? 0 : $m_da }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel_month/out-for-delivery?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color: #D68910">
                            <p class="text-center text-light">Out for Delivery</p>
                            <p class="text-center text-light"><b>{{ $m_await == 0 ? 0 : $m_await }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel_month/deliverd?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color: #145A32">
                            <p class="text-center text-light">Delivered</p>
                            <p class="text-center text-light"><b>{{ $m_deliver == 0 ? 0 : $m_deliver }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel_month/partial-delivery?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color:#00A86B;">
                            <p class="text-center text-light">Partial Delivery</p>
                            <p class="text-center text-light">
                                <b>{{ $m_partial_deliver == 0 ? 0 : $m_partial_deliver }}</b>
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel_month/return-to-hub?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color: #5F6A6A">
                            <p class="text-center text-light"> Return To Hub </p>
                            <p class="text-center text-light"><b>{{ $m_returntohub == 0 ? 0 : $m_returntohub }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel_month/disputed-packages?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color: #504A4B">
                            <p class="text-center text-light">Disputed</p>
                            <p class="text-center text-light"><b>{{ $m_hold == 0 ? 0 : $m_hold }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel_month/return-to-merchant?month=' . true) }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color: #17202A">
                            <p class="text-center text-light">Returned</p>
                            <p class="text-center text-light"><b>{{ $m_return == 0 ? 0 : $m_return }}</b></p>
                        </div>
                    </a>
                </div>

                <div class="col-md-25 mb-4">
                    <a>
                        <div class="p-2 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color:#f012be">
                            <p class="text-center text-light">Wallet Usage</p>
                            <p class="text-center text-light"><b>N{{ number_format($m_wallet ?? 0, 2) }}</b></p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding" style="margin-top:0px">
        <b style="text-decoration: none;text-transform:uppercase; font-weight:1000; color:#191714a8">TRANSACTION COUNT FROM
            INCEPTION</b>
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel/pending') }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="text-transform:uppercase;height: 80px;background-color: #D21F3C;">
                            <p class="text-center text-light">Pending</p>
                            <p class="text-center text-light"><b>{{ $t_pending == 0 ? 0 : $t_pending }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel/in-transit') }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="text-transform:uppercase;height: 80px;background-color:#495DDF;">
                            <p class="text-center text-light">In Transit</p>
                            <p class="text-center text-light"><b>{{ $t_pick == 0 ? 0 : $t_pick }}</b></p>
                        </div>
                    </a>
                </div>


                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel/arrived-at-hub') }}">
                        <div class="p-2 m-1 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color:#004953">
                            <p class="text-center text-light">Arrived At Hub</p>
                            <p class="text-center text-light"><b>{{ $t_da == 0 ? 0 : $t_da }}</b></p>
                        </div>
                    </a>
                </div>

                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel/out-for-delivery') }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="text-transform:uppercase;height: 80px;background-color: #D68910;">
                            <p class="text-center text-light">Out for Delivery</p>
                            <p class="text-center text-light"><b>{{ $t_await == 0 ? 0 : $t_await }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel/deliverd') }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="text-transform:uppercase;height: 80px;background-color: #145A32;">
                            <p class="text-center text-light">Delivered</p>
                            <p class="text-center text-light"><b>{{ $t_deliver == 0 ? 0 : $t_deliver }}</b></p>
                        </div>
                    </a>
                </div>

                <div class="col-md-25">
                    <a href="{{ url('/merchant/parcel/partial-delivery') }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="text-transform:uppercase;height: 80px;background-color:#00A86B;">
                            <p class="text-center text-light">Partial Delivery</p>
                            <p class="text-center text-light">
                                <b>{{ $t_partial_deliver == 0 ? 0 : $t_partial_deliver }}</b>
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel/return-to-hub') }}">
                        <div class="p-2 m-1 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color: #5F6A6A">
                            <p class="text-center text-light">Return To Hub</p>
                            <p class="text-center text-light"><b>{{ $t_returntohub == 0 ? 0 : $t_returntohub }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel/disputed-packages') }}">
                        <div class="p-2 m-1 m-1 stats-reportList"
                            style="height:80px;text-transform:uppercase;background-color: #504A4B">
                            <p class="text-center text-light">Disputed</p>
                            <p class="text-center text-light"><b>{{ $t_hold == 0 ? 0 : $t_hold }}</b></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-25 ">
                    <a href="{{ url('/merchant/parcel/return-to-merchant') }}">
                        <div class="p-2 m-1 stats-reportList"
                            style="text-transform:uppercase;height: 80px;background-color: #17202A;">
                            <p class="text-center text-light">Returned</p>
                            <p class="text-center text-light"><b>{{ $t_return == 0 ? 0 : $t_return }}</b></p>
                        </div>
                    </a>
                </div>

                <div class="col-md-25">
                    <a>
                        <div class="p-2 m-1 stats-reportList"
                            style="text-transform:uppercase;height: 80px;background-color:#f012be;">
                            <p class="text-center text-light">Available Wallet</p>
                            <p class="text-center text-light"><b>N{{ number_format($merchant->balance ?? 0, 2) }}</b>
                            </p>
                        </div>
                    </a>
                </div>

            </div>

        </div>
        <div>
            <div class="row justify-content-between mt-2 paymentRow">
                <div class="col-md-4">
                    <div class="info-box d-flex align-items-center box-bg-mda">
                        <span class="info-box-icon mr-3">
                            <img class="info-box-icon_img" src="{{ asset('frontEnd/merchant/merchant-due.png') }}"
                                alt="">
                        </span>
                        <div class="info-box-content" style="color:#168118">
                            <span class="info-box-text" style="color:#191714a8">Next Payout Amount <i
                                    class="fa fa-info-circle tooltip-container" aria-hidden="true"><span
                                        class="tooltip-text">The sum price of all the parcels that have been delivered or
                                        partially delivered and are yet to be settled by accounts.</span></i></span><br>
                            <span class="info-box-number"><b>N {{ number_format($merchantDueamount ?? 0, 2) }}</b></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-4">
                    <div class="info-box d-flex align-items-center box-bg-rmd">
                        <span class="info-box-icon  mr-3">
                            <img class="info-box-icon_img" src="{{ asset('frontEnd/merchant/merchant-return-due.png') }}"
                                alt="">
                        </span>
                        <div class="info-box-content" style="color: #ff0000">
                            <span class="info-box-text" style="color:#191714a8">Returned-To-Merchant Due <i
                                    class="fa fa-info-circle tooltip-container" aria-hidden="true"><span
                                        class="tooltip-text">The total cost of undelivered parcels returned to the
                                        merchant, to be deducted from the next payout if unpaid after
                                        invoicing.</span></i></span><br>
                            <span class="info-box-number"><b>N {{ number_format($retmercharge ?? 0, 2) }}</b></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-4">
                    <div class="info-box d-flex align-items-center box-bg-rmd">
                        <span class="info-box-icon  mr-3">
                            <img class="info-box-icon_img" src="{{ asset('frontEnd/merchant/merchant-paid.png') }}"
                                alt="">
                        </span>
                        <div class="info-box-content" style="color: #36454F">
                            <span class="info-box-text" style="color:#191714a8">Overall Paid Amount <i
                                    class="fa fa-info-circle tooltip-container" aria-hidden="true"><span
                                        class="tooltip-text">This is the cumulative sum of all earnings received over time.
                                        Lifetime earnings. </span></i></span><br>
                            <span class="info-box-number"><b>N {{ number_format($merchantspaid ?? 0, 2) }}</b></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>

        </div>
        <div class="row py-3 px-3">

            <div id="chartContainer" style="height: 500px; width: 100%;"></div>
        </div>
    </section>

    <section class="section-padding ">
        <b style="text-decoration: none;text-transform:uppercase; font-weight:1000; color:#191714a8">RECENT SHIPMENT STATUS
            UPDATES</b>

        <div class="container-fluid">

            <div class="row">
                <!-- column end -->
                <style>
                    table tr th,
                    td {
                        font-size: 12px;
                    }
                </style>

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="stats-reportList-inner">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" width="100">
                                    <thead>
                                        <tr>
                                            <th scope="col">S/N</th>
                                            <th scope="col">View</th>
                                            <th scope="col">Recipient Name</th>
                                            <th scope="col">Delivery City/Town</th>
                                            <th scope="col">Tracking ID</th>
                                            <th scope="col">Order Number</th>
                                            <th scope="col">Parcel Weight</th>
                                            <th scope="col">Shipment Status</th>
                                            <th>COD</th>
                                            <th scope="col">Status Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parcels as $key => $parcel)
                                            <tr>
                                                <th>{{ ++$key }}</th>
                                                <td scope="row">
                                                    <button class="edit_icon" href="#" data-toggle="modal"
                                                        data-target="#merchantParcel{{ $parcel->id }}"
                                                        title="View"><i class="fa fa-eye"></i></button>
                                                    <div id="merchantParcel{{ $parcel->id }}" class="modal fade"
                                                        role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Parcel Details</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="table table-bordered table-striped">
                                                                        {{-- <tr>
                                                                            <td>Merchant Name</td>
                                                                            <td>{{ $parcel->merchant->firstName }}
                                                                                {{ $parcel->merchant->lastName }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Merchant Phone</td>
                                                                            <td>{{ $parcel->merchant->phoneNumber }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Merchant Email</td>
                                                                            <td>{{ $parcel->merchant->emailAddress }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Company</td>
                                                                            <td>{{ $parcel->merchant->companyName }}</td>
                                                                        </tr> --}}
                                                                        <tr>
                                                                            <td>Recipient Name</td>
                                                                            <td>{{ $parcel->recipientName }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Recipient Phone</td>
                                                                            <td>{{ $parcel->recipientPhone }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Recipient Address</td>
                                                                            <td>{{ $parcel->recipientAddress }}</td>
                                                                        </tr>
                                                                        
                                                                        <tr>
                                                                            <td>Delivery City/Town</td>
                                                                            <td>{{ $parcel->deliverycity->title }}
                                                                                /
                                                                                {{ $parcel->deliverytown->title }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Order Number</td>
                                                                            <td>{{ $parcel->order_number }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>COD</td>
                                                                            <td>{{ number_format($parcel->cod, 2) }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>C. Charge</td>
                                                                            <td>{{ number_format($parcel->codCharge, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Tax</td>
                                                                            <td>{{ number_format($parcel->tax, 2) }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Insurance</td>
                                                                            <td>{{ number_format($parcel->insurance, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>D. Charge</td>
                                                                            <td>N{{ number_format($parcel->deliveryCharge, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Sub Total</td>
                                                                            <td>{{ number_format($parcel->merchantAmount, 2) }}
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Paid</td>
                                                                            <td>{{ number_format($parcel->merchantPaid, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Due</td>
                                                                            <td>{{ number_format($parcel->merchantDue, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Create Date</td>
                                                                            <td>{{ $parcel->created_at }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Last Update</td>
                                                                            <td>{{ date('F d, Y', strtotime($parcel->updated_at)) }}
                                                                                <br>
                                                                                {{ date('g:i a', strtotime($parcel->updated_at)) }}

                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $parcel->recipientName }}</td>
                                                <td>{{ $parcel->deliverycity->title }}
                                                    /
                                                    {{ $parcel->deliverytown->title }}
                                                </td>
                                                <td>{{ $parcel->trackingCode }}</td>
                                                <td>{{ $parcel->order_number }}</td>
                                                <td>{{ $parcel->productWeight }}</td>
                                                <td>{{ $parcel->parcelnote->note ?? 'Empty Note' }}</td>
                                                <td> {{ number_format($parcel->cod ?? 0, 2) }}</td>
                                                <td>{{ $parcel->updated_at->format('d/m/Y') }}<br>{{ $parcel->updated_at->format('g:i A') }}

                                                </td>
                                            </tr>
                                            <!-- Modal end -->
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


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
        document.addEventListener('DOMContentLoaded', () => {
            const tooltipElements = document.querySelectorAll('[title]');
            const tooltip = document.createElement('div');
            tooltip.className = 'custom-tooltip';
            document.body.appendChild(tooltip);

            tooltipElements.forEach(el => {
                el.addEventListener('mouseenter', (e) => {
                    const title = el.getAttribute('title');
                    if (title) {
                        tooltip.textContent = title;
                        el.setAttribute('data-title', title);
                        el.removeAttribute('title');
                        tooltip.style.opacity = '1';
                    }
                });

                el.addEventListener('mousemove', (e) => {
                    tooltip.style.left = e.pageX + 10 + 'px';
                    tooltip.style.top = e.pageY + 10 + 'px';
                });

                el.addEventListener('mouseleave', (e) => {
                    tooltip.style.opacity = '0';
                    const title = el.getAttribute('data-title');
                    if (title) {
                        el.setAttribute('title', title);
                    }
                });
            });
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
                    text: "Merchant Monthly Pickup Vs Delivery"
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
                data: [
                    {
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
