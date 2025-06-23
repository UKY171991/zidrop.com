@extends('frontEnd.layouts.pages.agent.agentmaster')
@if ($parceltype)
    @section('title', $parceltype->title)
@else
    @section('title', 'MY PARCEL')
@endif

@section('content')
    <style>
        @media screen and (max-width: 767px) {
            li.paginate_button.previous {
                display: inline !important;
            }

            li.paginate_button.next {
                display: inline !important;
            }

            li.paginate_button {
                display: none !important;
            }

            .custom-card {
                margin-top: 25px !important;
            }

            .main-body,
            .container-fluid {
                padding: 0px !important;
                margin: 0px !important;
            }

            .box-content {
                padding: 0px !important;
                margin: 0px !important;
                margin-top: 40px !important;
            }

            .col-sm-12 {
                padding: 10px !important;
                margin: 8px !important;
            }

        }

        @media screen {
            #printSection {
                display: none;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printSection,
            #printSection * {
                visibility: visible !important;
            }

            #printSection {
                position: absolute !important;
                left: 0;
                top: 0;
            }

        }

        .col-sm-2 {
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        .select2-container .select2-search--inline .select2-search__field {
            margin-top: -7px !important;
            padding: 5px 5px !important;
            padding-top: 10px !important;

        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ddd !important
        }

        .form-control2 {
            display: block;
            width: 97%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .selectedItemshow {
            display: inline;
            background-color: #008B8B !important;
            padding: 9px !important;
            font-size: 14px;
            color: #fff;
            border-radius: 50px !important;
            display: none;
        }

        #example333_length label {
            display: inline-flex;
            margin-top: -30px !important;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            padding: 4px;
            width: 90px;
            margin-left: 10px;
            font-size: smaller;
            margin-top: -4px;
        }

        .dataTables_wrapper .dataTables_length {
            margin-top: -40px !important;
            float: right;
        }

        .cust-action-btn {
            width: 130px;
        }

        .cust-action-btn li {
            display: inline-block;
        }

        .action_buttons ul {
            padding: 18px 15px;
        }

        .action_buttons li {
            list-style: none;
            display: inline-block;
        }

        .action_buttons button,
        .action_buttons a {
            border: 0;
            text-align: left;
            border-radius: 5px;
            padding: 6px 10px;
            color: #fff;
            float: left;
            ;
        }

        .action_buttons button {
            cursor: pointer;
        }

        .action_buttons span {
            margin-left: 10px;
            margin-top: 8px;
            display: inline-block;
        }

        .action_buttons .btn-primary {
            padding: 6px 10px;
        }

        .action_buttons .btn .btn-primary {
            padding: 6px 10px;
        }
    </style>
    <div class="container-fluid">
        <div class="box-content">
            <div class="row">
                {{-- Show all errors with cross --}}
                @if ($errors->any())
                    <div class="col-sm-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Errors</strong>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <button type="button" class="close btn btn-danger" data-dismiss="alert" aria-label="Close">
                                <i class="fas fa-cross"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card">
                        <div class="row">
                            <div class="col-md-6 mt-2 mr-auto text-left">
                                <div class="body-title pl-2">
                                    @if ($parceltype)
                                        <h5 class="pageStatusTitle">{{ $parceltype?->title }} PARCEL</h5>
                                    @else
                                        <h5 class="pageStatusTitle">MY PARCEL</h5>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mt-2 mr-auto text-right float-right">
                                <button class="btn btn-primary mr-3" id="scanStart" title="Scan Bar Code"><i
                                        class="fa fa-qrcode"></i> Scan Barcode</button>

                                <div id="scaninput" style="display: none; text-align:right;">
                                    <input type="text" class="form-control2 mt-3 mb-3 " id="scannerCodesubmit"
                                        placeholder="Click Here and Then Scan">
                                    <button class="btn btn-primary mr-3" id="scanStartStop" title="Scan Bar Code"><i
                                            class="fa fa-qrcode"></i> Stop Scan</button>
                                </div>
                            </div>

                        </div>

                        <audio id="beepSound" preload="auto">
                            <source src="{{ asset('beep-08b.mp3') }}" type="audio/mpeg">
                        </audio>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form action="" class="filte-form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="1" name="filter_id">
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" placeholder="Track Id" name="trackId">
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" placeholder="Phone Number"
                                            name="phoneNumber">
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" placeholder="Customer name"
                                            name="cname">
                                    </div>
                                    <!-- col end -->
                                    <!-- col end -->
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" placeholder="Merchant Id"
                                            name="merchantId">
                                    </div>
                                    {{-- @if ($slug == 'all')
                                        <div class="col-sm-2">
                                            <select name="Upstatuss[]" class="form-control select2" id="Upstatuss" multiple>
                                                <option value=""></option>
                                                @foreach ($parceltypes as $key => $ptvalue)
                                                    @php
                                                        if (in_array($ptvalue->id, [8, 9])) {
                                                            continue;
                                                        }
                                                    @endphp
                                                    <option value="{{ $ptvalue->id }}">{{ $ptvalue->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif --}}
                                    <!-- col end -->
                                    <div class="col-sm-2">
                                        <input type="date" class="flatDate form-control" placeholder="Date Form"
                                            name="startDate">
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-2">
                                        <input type="date" class="flatDate form-control" placeholder="Date To"
                                            name="endDate">
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-success mt-2"
                                            id="SearchDtataButton">Submit</button>
                                    </div>
                                    <!-- col end -->
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <form action="{{ url('agent/dliveryman-asign/bulk-option') }}" method="POST" id="myform"
                                class="bulk-status-form">
                                @csrf
                                {{-- <select name="deliverymanId" class="bulkselect" form="myform" required>
                                    <option value="">Select all & Asign</option>
                                    @foreach ($Agentdeliverymen as $key => $dman)
                                        <option value="{{ $dman->id }}">{{ $dman->name }}</option>
                                    @endforeach

                                </select>
                                <select class="bulkselect" name="asigntype">
                                    <option value="1">Pickup</option>
                                    <option value="2">Delivery</option>
                                </select>
                                
                                <button type="submit" class="bulkbutton bulk-status-btn">Apply</button> --}}
                                @if (request()->segment(3) === 'pending')
                                    <button id="PickedUpUpdate" type="button" class="btn bulkbutton mb-2"
                                        data-slug="picked_up" style="background:#00796B">Pick
                                        Up</button>
                                    <button id="PrintSelectedItemsInvoice" type="button" class="btn btn-secondary mb-2"
                                        style=""> <i class="fa fa-print"></i> Print Waybill</button>
                                @endif

                                @if (request()->segment(3) === 'in-transit')
                                    <button id="receiveParcel" type="button" class="bulkbutton btn-success">Receive
                                        Parcel</button>
                                @endif
                                @php
                                    $statuses = ['in-transit', 'arrived-at-hub', 'paid', 'picked-up'];
                                @endphp

                                @if (in_array(request()->segment(3), $statuses))
                                    <button id="AssignParcelRider" type="button" class="bulkbutton bulk-status-btn mb-2">
                                        Asign Parcel To Rider
                                    </button>
                                @endif
                                @if (request()->segment(3) === 'picked-up')
                                    <button id="TransferToHub" type="button" class="bulkbutton bulk-status-btn mb-2"
                                        style="background:#495DDF">Transfer To Hub</button>
                                @endif
                                @if (request()->segment(3) === 'out-for-delivery')
                                @endif
                                @if (request()->segment(3) === 'return-to-hub')
                                    <button id="ReturnTohubAction" type="button" class="bulkbutton btn-danger  mb-2"
                                        data-slug="out-for-delivery" style="background:#5F6A6A">Return To Origin Hub
                                    </button>
                                    <button id="ReturnToMerchatAction" type="button" class="bulkbutton btn-danger  mb-2"
                                        data-slug="out-for-delivery" style="background:#17202A">Return To Merchant
                                    </button>
                                @endif
                                @if (request()->segment(3) !== 'pending' && request()->segment(3) !== 'return-to-hub')
                                    <button id="MassUpdateParcelll" type="button"
                                        class="bulkbutton btn-danger bg-danger">Mass Update</button>
                                @endif
                                @if (request()->segment(3) === 'picked-up')
                                    <button id="PrintSelectedItemsInvoice" type="button" class="btn btn-secondary "
                                        style=""> <i class="fa fa-print"></i> Print Waybill</button>
                                @endif
                                <p class="selectedItemshow" style="">0</p>
                                <table id="example333"
                                    class="table table-bordered table-striped custom-table table-responsive">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="My-Buttonn"></th>
                                            <th>Tracking ID</th>
                                            <th>More</th>
                                            <th>Date</th>
                                            <th>Shop Name</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Delivery Man</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Charge</th>
                                            <th>Tax</th>
                                            <th>Insurance</th>
                                            <th>Sub Total</th>
                                            <th>L. Update</th>
                                            <th>Payment Status</th>
                                            <th>Merchant Id</th>
                                            <th>Note</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
        {{-- Assign Modal --}}
        <!-- Modal -->
        <div id="asignModalUpdate" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delivery Man Asign</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('agent/deliveryman/asign') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hidden_id" id="assign_hidden_id" value="">
                            <input type="hidden" name="merchant_phone" id="assign_merchant_phone" value="">
                            <div class="form-group">
                                <select name="deliverymanId" class="form-control" id="">
                                    <option value="">Select..</option>
                                    @foreach ($Agentdeliverymen as $key => $deliveryman)
                                        <option value="{{ $deliveryman->id }}">{{ $deliveryman->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- form group end -->
                            <div class="form-group mrt-15">
                                <textarea name="note" class="form-control" cols="30" placeholder="Note"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success">Update</button>
                            </div>
                            <!-- form group end -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
       
        <!-- Modal end -->
        {{-- View Modal --}}
        <div id="merchantParcelUpdate" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Parcel Details</h5>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td width="40%" id="merchant_name">Merchant Name</td>
                                <td id="firstNamelastName">
                                </td>
                            </tr>
                            <tr>
                                <td id="merchant_phone">Merchant Phone</td>
                                <td id="phoneNumber"></td>
                            </tr>
                            <tr>
                                <td id="merchant_email">Merchant Email</td>
                                <td id="emailAddress"></td>
                            </tr>
                            <tr>
                                <td id="merchant_company">Company</td>
                                <td id="companyName"></td>
                            </tr>
                            <td>Recipient Name</td>
                            <td id="recipientName"></td>
                            </tr>
                            <tr>
                                <td>Recipient Address</td>
                                <td id="recipientAddress"></td>
                            </tr>
                            <tr>
                                <td>Pickup City/Town</td>
                                <td id="pickuptitle"></td>
                            </tr>
                            <tr>
                                <td>Delivery City/Town</td>
                                <td id="deliverytitle"></td>
                            </tr>
                            <tr>
                                <td>Order Number</td>
                                <td id="order_number"></td>
                            </tr>
                            <tr>
                                <td>COD</td>
                                <td id="cod"></td>
                            </tr>
                            <tr>
                                <td>Declared Value</td>
                                <td id="package_value"></td>
                            </tr>
                            <tr>
                                <td>C. Charge</td>
                                <td id="codCharge"></td>
                            </tr>
                            <tr>
                                <td>Tax</td>
                                <td id="atax"></td>
                            </tr>
                            <tr>
                                <td>Insurance</td>
                                <td id="ainsurance"></td>
                            </tr>
                            <tr>
                                <td>D. Charge</td>
                                <td id="deliveryCharge"></td>
                            </tr>
                            <tr>
                                <td>Sub Total</td>
                                <td id="merchantAmount"></td>
                            </tr>
                            <tr>
                                <td>Paid</td>
                                <td id="merchantPaid"></td>
                            </tr>
                            <tr>
                                <td>Due</td>
                                <td id="merchantDue"></td>
                            </tr>
                            <tr>
                                <td>Create Date</td>
                                <td id="created_at"></td>
                            </tr>
                            <tr>
                                <td>Last Update</td>
                                <td id="Last_updated_at">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
          {{-- Parcel History --}}
          <div id="merchantParcelhUpdate" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Parcel History</h5>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <tr style="background-color: #fff; color: #000" >
                                <th style="background-color: #fff; color: #000; font-weight:bold"><b class="">Customer Name</b></th>
                                <th style="background-color: #fff; color: #000"><b class="">Date</b></th>
                                <th style="background-color: #fff; color: #000"><b class="">Done By</b></th>
                                <th style="background-color: #fff; color: #000"><b class="">Note</b></th>
                                <th style="background-color: #fff; color: #000"><b class="">Status</b></th>
                            </tr>
                            
                            <tbody id="ParcelHistoryTable">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
        {{-- S Update Modal --}}
        <!-- Modal -->
        <div id="sUpdateModalUpdate" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Parcel Status Update</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('agent/parcel/status-update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hidden_id" value="" id="par_up_hidden_id">
                            <input type="hidden" name="customer_phone" value="" id="par_up_customer_phone">
                            <div class="form-group">
                                <select name="status" onchange="percelDelivery(this)" class="form-control"
                                    id="parcel_status_update">

                                    @foreach ($parceltypes as $key => $ptvalue)
                                        @php
                                            if (in_array($ptvalue->id, [8, 9, 11])) {
                                                continue;
                                            }
                                        @endphp
                                        <option value="{{ $ptvalue->id }}" data-sl="{{ $ptvalue->sl }}"
                                            @if ($ptvalue->id == 6) @disabled(true) @endif>
                                            {{ $ptvalue->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <style>
                                .partialpayment {
                                    display: none;
                                }
                            </style>
                            <!-- form group end -->
                            <div class="form-group mrt-15">
                                <select name="note" class="form-control" id="" required>
                                    <option value="">Select</option>
                                    @foreach ($allnotelist as $key => $notelist)
                                        <option value="{{ $notelist->title }}">{{ $notelist->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- form group end -->
                            <div class="form-group">
                                <div id="customerpaid" class="partialpayment">
                                    <input type="text" class=" form-control CommaSeperateValueSet"
                                        value="{{ old('customerpay') }}" id="customerpay" name="partial_payment"
                                        placeholder="customer pay" /><br />
                                </div>
                            </div>
                            <!-- form group end -->
                            <div class="form-group">
                                <button class="btn btn-success">Update</button>
                            </div>
                            <!-- form group end -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
        {{-- Mass update Modal --}}
        <!-- Modal -->
        <div class="modal fade" id="MassUpdateParcelModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Parcel Status Update</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('agent/parcel/status-mass-update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="par_up_mass_hidden_ids" value=""
                                id="par_up_mass_hidden_ids">

                            <div class="form-group">
                                <select name="status" class="form-control" id="parcel_status_mass_update">
                                    <option value=""></option>
                                    @foreach ($parceltypes as $key => $ptvalue)
                                        @php
                                            if (in_array($ptvalue->id, [8, 9, 11])) {
                                                continue;
                                            }
                                        @endphp
                                        <option value="{{ $ptvalue->id }}"
                                            @if ($ptvalue->id == 6) @disabled(true) @endif
                                            data-sl="{{ $ptvalue->sl }}">{{ $ptvalue->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <style>
                                .partialpayment {
                                    display: none;
                                }
                            </style>
                            <!-- form group end -->
                            <div class="form-group mrt-15">
                                <select name="note" class="form-control" id="" required>
                                    <option value="">Select</option>
                                    @foreach ($allnotelist as $key => $notelist)
                                        <option value="{{ $notelist->title }}">{{ $notelist->title }}</option>
                                    @endforeach
                                </select>
                                @error('note')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- form group end -->
                            <div class="form-group">
                                <div id="customerpaid" class="partialpayment">
                                    <input type="text" class=" form-control" value="{{ old('customerpay') }}"
                                        id="customerpay" name="partial_payment" placeholder="customer pay" /><br />
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                            <!-- form group end -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Assign To Rider modal --}}
        <div class="modal fade" id="AssignParcelRiderModal" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Parcel To Rider</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('agent/dliveryman-asign/bulk-option') }}" method="POST">
                            @csrf
                            <input type="hidden" name="parcel_id" value="" id="parcel_id">
                            <input type="hidden" name="btn" value="rider" id="btn">

                            <div class="form-group">
                                {{-- @dd($Agentdeliverymen) --}}
                                <select name="deliverymanId" class=" form-control" required>
                                    <option value="">Select all & Asign</option>
                                    @foreach ($Agentdeliverymen as $key => $dman)
                                        <option value="{{ $dman->id }}">{{ $dman->name }}</option>
                                    @endforeach

                                </select>
                                @error('deliverymanId')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- form group end -->
                            <div class="form-group mrt-15">
                                <select class=" form-control" name="asigntype">
                                    <option value="2">Delivery</option>
                                    <option value="1">Pickup</option>
                                </select>
                                @error('transferhub')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                            <!-- form group end -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Transfer to Hub --}}
        {{-- Transfer To Hub modal --}}
        <div class="modal fade" id="TransferToHubModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transfer To Hub</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('agent/parcel/transfertohub/update') }}" method="POST">
                            @csrf
                            @php
                                $agentId = Session::get('agentId');
                            @endphp
                            <input type="hidden" name="parcel_id" value="" id="parcels_id">
                            <div class="form-group">
                                <select name="agentId" class="bulkselect form-control" required="required">
                                    <option value="">Select Hub</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}"
                                            @if ($agent->id == $agentId) @disabled(true) @endif>
                                            {{ $agent->name }}</option>
                                    @endforeach

                                </select>

                                @error('agentId')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- form group end -->
                            <div class="form-group mrt-15">

                                <select class="bulkselect form-control" name="transferhub">
                                    <option value="1">Transfer To Hub</option>
                                </select>
                                @error('transferhub')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                            <!-- form group end -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Return To Origin Hub modal --}}
        <div class="modal fade" id="ReturnToHubModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Return To Origin Hub</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('agent/parcel/returntohub/update') }}" method="POST">
                            @csrf
                            @php
                                $agentId = Session::get('agentId');
                            @endphp
                            <input type="hidden" name="ret_parcel_id" value="" id="ret_parcel_id">
                            <div class="form-group">
                                <select name="agentId" class="bulkselect form-control" required="required">
                                    <option value="">Select Hub</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}"
                                            @if ($agent->id == $agentId) @disabled(true) @endif>
                                            {{ $agent->name }}</option>
                                    @endforeach

                                </select>

                                @error('agentId')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- form group end -->
                            <div class="form-group mrt-15">

                                <select class="bulkselect form-control" name="transferhub">
                                    <option value="1">Return To Origin Hub</option>
                                </select>
                                @error('transferhub')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                            <!-- form group end -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @section('custom_js_scripts')

        <script>
            // ReturnToMerchatAction
            $(document).ready(function(event) {
                $('#ReturnToMerchatAction').click(function(event) {
                    event.preventDefault();
                    var parcels = [];
                    var slug = "return-to-merchant";
                    $(':checkbox:checked').each(function(i) {
                        var value = $(this).val();
                        if (value == 'on') { // Use '===' for comparison
                            return;
                        } else {
                            parcels[i] = value;
                        }
                    });
                    console.log(parcels);
                    if (parcels.length === 0) {
                        alert('Alert:: Please select minimum 1 parcel');
                    } else {
                        // Show spinner
                        $('#spinner').show();

                        // AJAX call
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('agentparcel.returntomerchant') }}",
                            dataType: 'JSON',
                            data: {
                                parcels: parcels,
                                slug: slug,
                                _token: '{{ csrf_token() }}' // Include CSRF token
                            },
                            success: function(results) {
                                if (results.success === true) {
                                    // Show toastr success message for 2 seconds
                                    toastr.success('Parcels has been return to Merchant', '', {
                                        timeOut: 2000
                                    });
                                    // Reload the page after 2 seconds
                                    setTimeout(function() {
                                        location.reload();
                                    }, 700);
                                } else {
                                    console.log(results.message); // Use results instead of response
                                }
                            },
                            error: function() {
                                // Handle error
                                alert('An error occurred. Please try again.');
                            },
                            complete: function() {
                                // Hide spinner
                                $('#spinner').hide();
                            }
                        });
                    }
                });

            });

            $(document).ready(function(event) {
                $('#PickedUpUpdate').click(function(event) {
                    event.preventDefault();
                    var parcels = [];
                    var slug = "picked_up";
                    $(':checkbox:checked').each(function(i) {
                        var value = $(this).val();
                        if (value == 'on') { // Use '===' for comparison
                            return;
                        } else {
                            parcels[i] = value;
                        }
                    });
                    console.log(parcels);
                    if (parcels.length === 0) {
                        alert('Alert:: Please select minimum 1 parcel');
                    } else {
                        // Show spinner
                        $('#spinner').show();

                        // AJAX call
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('agentparcel.singlestatusupdate') }}",
                            dataType: 'JSON',
                            data: {
                                parcels: parcels,
                                slug: slug,
                                _token: '{{ csrf_token() }}' // Include CSRF token
                            },
                            success: function(results) {
                                if (results.success === true) {
                                    // Show toastr success message for 2 seconds
                                    toastr.success('Parcel has been picked up', '', {
                                        timeOut: 2000
                                    });
                                    // Reload the page after 2 seconds
                                    setTimeout(function() {
                                        location.reload();
                                    }, 700);
                                } else {
                                    console.log(results.message); // Use results instead of response
                                }
                            },
                            error: function() {
                                // Handle error
                                alert('An error occurred. Please try again.');
                            },
                            complete: function() {
                                // Hide spinner
                                $('#spinner').hide();
                            }
                        });
                    }
                });

                $('#PrintSelectedItemsInvoice').click(function(event) {
                    event.preventDefault();

                    var parcels = [];

                    $(':checkbox:checked').each(function() {
                        var value = $(this).val();
                        if (value !== 'on') {
                            parcels.push(value);
                        }
                    });

                    if (parcels.length === 0) {
                        alert('Alert: Please select at least 1 parcel');
                        return;
                    }

                    var url = "{{ route('agent.parcel.PrintSelectedItems') }}?parcels=" + parcels.join(',');

                    window.open(url, '_blank'); // Open the PDF in a new tab
                });
                // $('#PrintSelectedItemsInvoice').click(function(event) {
                //     event.preventDefault(); // Prevent default form submission behavior

                //     var parcels = [];

                //     // Iterate over checked checkboxes
                //     $(':checkbox:checked').each(function() {
                //         var value = $(this).val();
                //         if (value !== 'on') { // Ignore the default 'on' value of checkboxes
                //             parcels.push(value);
                //         }
                //     });

                //     console.log(parcels); // Debugging: Log selected parcels

                //     // Check if at least one parcel is selected
                //     if (parcels.length === 0) {
                //         alert('Alert: Please select at least 1 parcel');
                //         $('#spinner').hide(); // Hide spinner if no parcels are selected
                //     } else {
                //         var spinner = $('#spinner');
                //         spinner.show(); // Show spinner before making the request

                //         // Make AJAX call to update parcel status
                //         $.ajax({
                //             type: 'POST',
                //             url: "{{ route('agent.parcel.PrintSelectedItems') }}",
                //             dataType: 'json',
                //             data: {
                //                 parcels: parcels
                //             },
                //             headers: {
                //                 'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token in headers
                //             },
                //             success: function(results) {
                //                 spinner.hide(); // Hide spinner on success
                //                 console.log('Success:', results);
                //             },
                //             error: function(xhr, status, error) {
                //                 spinner.hide(); // Ensure spinner is hidden on error
                //                 // alert('An error occurred. Please try again.');
                //             }
                //         });
                //     }
                // });

            });
            //Modal Scripts
            $(document.body).on('click', '#asignModal', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                var merchant_phone = $(this).data('merchant_phone');
                $('#assign_hidden_id').val(id);
                $('#assign_merchant_phone').val(merchant_phone);
                jQuery('#asignModalUpdate').modal('show');
            });
            // view
            $(document.body).on('click', '#merchantParcel', function() {
                event.preventDefault();
                var type = $(this).data('type');
                var firstname = $(this).data('firstname');
                var lastname = $(this).data('lastname');
                var phonenumber = $(this).data('phonenumber');
                var emailaddress = $(this).data('emailaddress');
                var companyname = $(this).data('companyname');
                var recipientname = $(this).data('recipientname');
                var recipientaddress = $(this).data('recipientaddress');
                var zonename = $(this).data('zonename');
                var delivery = $(this).data('delivery');
                var pickup = $(this).data('pickup');
                var title = $(this).data('title');
                var order_number = $(this).data('order_number');
                var cod = $(this).data('cod');
                var package_value = $(this).data('package_value');
                var tax = $(this).data('tax');
                var insurance = $(this).data('insurance');
                var codcharge = $(this).data('codcharge');
                var deliverycharge = $(this).data('deliverycharge');
                var merchantamount = $(this).data('merchantamount');
                var merchantpaid = $(this).data('merchantpaid');
                var merchantdue = $(this).data('merchantdue');
                var created_at = $(this).data('created_at');
                var updated_at = $(this).data('updated_at');
                if (type == 'p2p') {

                    $('#merchant_name').html('Sender Name');
                    $('#merchant_phone').html('Sender Phone');
                    $('#merchant_email').html('Sender Email');
                    $('#firstNamelastName').html(firstname);
                } else {
                    $('#merchant_name').html('Merchant Name');
                    $('#merchant_phone').html('Merchant Phone');
                    $('#merchant_email').html('Merchant Email');
                    $('#firstNamelastName').html(firstname + ' ' + lastname);
                }

                $('#phoneNumber').html(phonenumber);
                $('#emailAddress').html(emailaddress);
                $('#companyName').html(companyname);
                $('#recipientName').html(recipientname);
                $('#recipientAddress').html(recipientaddress);
                $('#zonenametitle').html(zonename + ' / ' + title);
                $('#deliverytitle').html(delivery);
                $('#pickuptitle').html(pickup);
                $('#order_number').html(order_number);
                $('#cod').html(cod);
                $('#package_value').html(package_value);
                $('#atax').html(tax);
                $('#ainsurance').html(insurance);
                $('#codCharge').html(codcharge);
                $('#deliveryCharge').html(deliverycharge);
                $('#merchantAmount').html(merchantamount);
                $('#merchantPaid').html(merchantpaid);
                $('#merchantDue').html(merchantdue);
                $('#created_at').html(created_at);
                var createdDate = new Date(updated_at);
                var monthNames = [
                    "January", "February", "March",
                    "April", "May", "June", "July",
                    "August", "September", "October",
                    "November", "December"
                ];
                var formattedCreatedDate = monthNames[createdDate.getMonth()] + ' ' + createdDate.getDate() + ' ' +
                    createdDate.getFullYear() + ' ' + createdDate.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                $('#Last_updated_at').html(createdDate);
                $('#merchantParcelUpdate').modal('show');
            });
            $(document.body).on('click', '#sUpdateModal', function() {
                event.preventDefault();
                var id = $(this).data('id');
                var recipientPhone = $(this).data('recipientPhone');
                var parcel_status_update = $(this).data('parcel_status_update');
                var parcel_status_update_sl = $(this).data('parcel_status_update_sl');
                $('#par_up_hidden_id').val(id);
                $('#par_up_customer_phone').val(recipientPhone);
                $('#parcel_status_update').val(parcel_status_update);
                $('#sUpdateModalUpdate').modal('show');

                $('#parcel_status_update option').each(function() {
                    var optionDataSl = $(this).data('sl');
                    if (parcel_status_update_sl > optionDataSl) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });
            $(document.body).on('click', '#merchantParcelh', function() {
            event.preventDefault();
            var id = $(this).data('id');
            var url = "{{ url('/agent/get_parcel_history') }}/" + id;
            // Ajax call
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var html = '';
                    $.each(response, function(key, value) {
                        var createdDate = new Date(value.date);
                        var monthNames = [
                            "January", "February", "March",
                            "April", "May", "June", "July",
                            "August", "September", "October",
                            "November", "December"
                        ];
                        var formattedCreatedDate = monthNames[createdDate.getMonth()] + ' ' +
                            createdDate.getDate() + ' ' + createdDate.getFullYear() + ' ' +
                            createdDate.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        html += '<tr>';
                        html += '<td>' + value.name + '</td>';
                        html += '<td>' + formattedCreatedDate + '</td>';
                        html += '<td>' + value.done_by + '</td>';
                        if (value.note == null) {
                            html += '<td>No note added</td>';
                        } else {
                            html += '<td>' + value.note + '</td>';
                        }
                        if (value.status == null) {
                            html += '<td>N/A</td>';
                        } else {
                            html += '<td>' + value.status + '</td>';
                        }
                        html += '</tr>';
                        $('#ParcelHistoryTable').html(html);
                    });
                    $('#merchantParcelhUpdate').modal('show');
                }
            });
            $('#merchantParcelhUpdate').modal('show');
        });
        </script>
        <script>
            // {{-- Datatable --}}
            $(document).ready(function(event) {
                var slug = '{{ $slug }}' ?? '';

                var table33 = $('#example333').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ordering: false, // Disable sorting globally
                    responsive: true,
                    language: {
                        lengthMenu: 'Show _MENU_ ',
                    },
                    lengthMenu: [
                        [50, -1],
                        ['50 rows', 'Show all']
                    ],
                    ajax: {
                        url: "{{ url('agent/agent_get_parcel_data') }}" + '/' + slug,
                        data: {

                        }
                    },

                    columnDefs: [{
                            orderable: false,
                            targets: '_all'
                        } // Disable sorting on all columns
                    ],
                    dom: 'ltrip',
                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('data_all_trs');
                    },


                });

                // Searching
                $('#SearchDtataButton').click(function() {

                    var filter_id = $('input[name="filter_id"]').val();
                    var trackId = $('input[name="trackId"]').val();
                    var phoneNumber = $('input[name="phoneNumber"]').val();
                    var startDate = $('input[name="startDate"]').val();
                    var endDate = $('input[name="endDate"]').val();
                    var merchantId = $('input[name="merchantId"]').val();
                    var cname = $('input[name="cname"]').val();
                    var UpStatusArray = $('#Upstatuss').val() ?? null;
                    console.log(UpStatusArray);
                    // destroly datatable
                    var table33 = $('#example333').DataTable();
                    table33.destroy();
                    $('#example333').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        sorting: false,
                        responsive: true,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            ['10 rows', '25 rows', '50 rows', 'Show all']
                        ],
                        ajax: {
                            url: "{{ url('agent/agent_get_parcel_data') }}" + '/' + slug,
                            data: {
                                filter_id: filter_id,
                                trackId: trackId,
                                phoneNumber: phoneNumber,
                                cname: cname,
                                merchantId: merchantId,
                                startDate: startDate,
                                endDate: endDate,
                                UpStatusArray: UpStatusArray,
                            }
                        },

                        // dom: 'trip',
                        dom: 'ltpi',

                        createdRow: function(row, data, dataIndex) {
                            // Add your desired class to each <tr> element
                            $(row).addClass('data_all_trs');
                        }

                    });
                });
            });



            // received Parcel
            $(document).ready(function() {
                $('#receiveParcel').click(function() {
                    var $btn = $(this);
                    $btn.prop('disabled', true).text('Processing...');

                    console.log('clicked');
                    var parcels = [];

                    // Iterate over checked checkboxes
                    $(':checkbox:checked').each(function() {
                        var value = $(this).val();
                        if (value !== 'on') {
                            parcels.push(value);
                        }
                    });

                    console.log(parcels.length);

                    if (parcels.length === 0) {
                        alert('Alert:: Please select minimum 1 parcel');
                        $btn.prop('disabled', false).text('Receive Parcel'); // re-enable if nothing selected
                    } else {
                        $.ajax({
                            type: "POST",
                            url: '{{ route('agent.parcel.receive') }}',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "parcels": parcels
                            },
                            success: function(response) {
                                if (response.success === 'success') {
                                    window.location.reload();
                                } else {
                                    console.log(response);
                                    $btn.prop('disabled', false).text(
                                    'Receive Parcel'); // re-enable on error
                                }
                            },
                            error: function() {
                                alert('Something went wrong. Please try again.');
                                $btn.prop('disabled', false).text(
                                'Receive Parcel'); // re-enable on fail
                            }
                        });
                    }
                });
            });

            // Select checkbox
            $(document).ready(function() {

                var selectedItemshow = parseInt($(".selectedItemshow").text(), 10) || 0;

                $(document.body).on('change', '.selectItemCheckbox', function(event) {
                    event.preventDefault();
                    var ischecked = $(this).is(':checked');

                    console.log(ischecked);
                    if (!ischecked) {
                        $(this).parent().parent().removeClass('selected');
                        $("#My-Buttonn").prop('checked', false);
                        selectedItemshow -= 1; // Decrement count
                        if (selectedItemshow <= 0) {
                            selectedItemshow = 0;
                            $(".selectedItemshow").css('display', 'none');
                        }
                    } else {
                        $(this).parent().parent().addClass('selected');
                        selectedItemshow += 1; // Increment count
                        $(".selectedItemshow").css('display', 'inline');
                    }
                    $(".selectedItemshow").text(selectedItemshow);
                });

                jQuery("#check_all_check").click(function() {

                    jQuery(':checkbox').each(function() {
                        if (this.checked == true) {
                            this.checked = false;
                        } else {
                            this.checked = true;
                        }
                    });
                });
                $(document.body).on('change', '#My-Buttonn', function() {
                    event.preventDefault();
                    var ischecked = $(this).is(':checked');
                    selectedItemshow = 0;
                    console.log(ischecked);
                    if (!ischecked) {
                        $(".selectItemCheckbox").removeAttr('checked');
                        $("#example333 tbody tr").removeClass('selected');
                        $(".selectItemCheckbox").each(function() {
                            this.checked = false;
                            selectedItemshow -= 1; // Decrement count
                            if (selectedItemshow <= 0) {
                                selectedItemshow = 0;
                                $(".selectedItemshow").css('display', 'none');
                            }
                        });

                    } else {
                        $(".selectItemCheckbox").attr('checked');
                        $("#example333 tbody tr").addClass('selected');
                        // checked all checkbox
                        $(".selectItemCheckbox").each(function() {
                            this.checked = true;
                            selectedItemshow += 1; // Increment count
                            $(".selectedItemshow").css('display', 'inline');
                        });
                    }
                    $(".selectedItemshow").text(selectedItemshow);
                });
            });
            //  Scaning 
            var OLDTrackingCode = '';
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var parcelSlug = '{{ $slug }}' ?? '';

                $('#scannerCodesubmit').on('input', function() {
                    let scannedCode = $(this).val();
                    var CodeLength = scannedCode.length;

                    // Check if the input has a value and is different from the previous scanned code
                    if (scannedCode.length > 0 && CodeLength === 12) {
                        // Trigger the search function
                        console.log('coll function')
                        searchCode(scannedCode);
                        OLDTrackingCode = scannedCode;
                    }
                });

                function searchCode(code) {
                    console.log('ajax function')
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('agent/get_parcel_by_qr') }}/" + code,
                        dataType: 'JSON',
                        data: {
                            slug: parcelSlug,
                        },
                        success: function(results) {
                            console.log('success function')
                            if (results.success) {
                                console.log(results.message);

                                // Clear the table before adding new results
                                $('#example333 tbody').empty();

                                // Clear the input field after a successful scan
                                $('#scannerCodesubmit').val('');

                                // Append the search results to the table
                                $('#example333 tbody').append(results.html);

                                // Mark the row as selected and check the checkbox
                                $(".selectItemCheckbox").prop('checked', true);
                                $("#example333 tbody tr").addClass('selected');
                                console.log(results.beepSound);
                                // Play a beep sound if specified and the scanned code is different from the last one
                                //check box
                                if (results.total <= 0) {
                                    $(".selectedItemshow").text(0);
                                    $(".selectedItemshow").css('display', 'none');
                                } else {
                                    $(".selectedItemshow").text(results.total);
                                    $(".selectedItemshow").css('display', 'inline');
                                    if (results.beepSound) {
                                        const beepSound = document.getElementById("beepSound");
                                        beepSound.play();
                                    }
                                }

                                // Update the OLDTrackingCode to prevent multiple beeps for the same code
                                OLDTrackingCode = code;
                            } else {
                                console.log(results.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX request failed:', status, error);
                            $('#scannerCodesubmit').val('');
                            // Handle the error based on status code or error message
                            if (xhr.status === 404) {
                                console.error('Parcel not found. Please check the tracking code.');
                                alert('Parcel not found. Please check the tracking code.');
                            } else if (xhr.status === 500) {
                                console.error('Server error. Please try again later.');
                                alert('Server error. Please try again later.');
                            } else {
                                console.error('An unexpected error occurred. Please try again.');
                                alert('An unexpected error occurred. Please try again.');
                            }
                        }
                    });
                }

                // Handle the start scanning button
                $(document.body).on('click', '#scanStart', function() {
                    $('#scannerCodesubmit').val('');
                    // Clear table data
                    // var table33 = $('#example333').DataTable();
                    // table33.clear();
                    $('#example333 tbody').empty();
                    $('.dataTables_paginate').hide();
                    $('.dataTables_info').hide();
                    $(".selectedItemshow").text(0);
                    OLDTrackingCode = '';
                    $('#scaninput').show();
                    $('#scannerCodesubmit').focus();
                });

                // Handle the stop scanning button
                $(document.body).on('click', '#scanStartStop', function() {
                    $('.dataTables_paginate').show();
                    $('.dataTables_info').show();
                    var table33 = $('#example333').DataTable();
                    table33.ajax.reload();
                    OLDTrackingCode = '';
                    $(".selectedItemshow").text(0);
                    $(".selectedItemshow").css('display', 'none');
                    // Clear Laravel Session
                    var sessionName = 'OLDTrackingCodesQRscans_agents';
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('session/destroy') }}" + '/' + sessionName,
                        dataType: 'JSON',
                        success: function(results) {
                            if (results.success) {
                                console.log(results.message);
                            } else {
                                console.log(results.message);
                            }
                        }
                    });
                    $('#scannerCodesubmit').val('');
                    $('#scaninput').hide();

                });
            });
        </script>


        {{-- Responsive Paginate --}}
        <script>
            $(document).ready(function(event) {
                // mass Update
                $('#MassUpdateParcelll').click(function(event) {
                    event.preventDefault();
                    // alert('clicked');
                    var slug = '{{ $slug }}' ?? '';
                    var parcels = [];
                    var status = [];
                    var sl = [];
                    $(':checkbox:checked').each(function(i) {
                        parcels[i] = $(this).val();
                        status[i] = $(this).data('status');
                        sl[i] = $(this).data('parcel_status_update_sl');
                    });
                    console.log(status);
                    if (parcels.length === 0) {
                        alert('Alert:: Please select minimum 1 parcel');
                    } else {
                        $('#par_up_mass_hidden_ids').val(parcels);



                        if (slug != 'all') {
                            if (parcels.length === 1) {
                                $('#parcel_status_mass_update').val(status[0]);

                                $('#parcel_status_mass_update option').each(function() {
                                    var optionDataSl = $(this).data('sl');
                                    if (sl[0] > optionDataSl || optionDataSl == 6) {
                                        $(this).prop('disabled', true);
                                    } else {
                                        $(this).prop('disabled', false);
                                    }
                                });
                            } else {
                                $('#parcel_status_mass_update').val(status[1]);

                                // $('#parcel_status_mass_update option').each(function() {
                                //     var optionDataSl = $(this).data('sl');
                                //     if (status[1] > optionDataSl) {
                                //         $(this).prop('disabled', true);
                                //     } else {
                                //         $(this).prop('disabled', false);
                                //     }
                                //     });
                            }


                        }



                        jQuery('#MassUpdateParcelModal').modal('show');
                        $('#parcel_status_mass_update').change(function() {
                            var status = $(this).val();
                            if (status == 6) {
                                $('#customerpaid').show();
                            } else {
                                $('#customerpaid').hide();
                            }
                        });
                    }
                });
            });

            $(document.body).on('click', '.page-item', function(event) {

                $('#My-Buttonn').prop('checked', false);
            });
        </script>
        {{-- new 21/09/2023 --}}
        <script>
            // change select2 placeholder text 
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Select Status",
                    allowClear: true,
                    multiple: true
                });
            });
        </script>
        {{-- Transfer To Hub --}}
        <script>
            // Transfer To Hub 
            $(document).ready(function(event) {
                $('#AssignParcelRider').click(function(event) {
                    event.preventDefault();
                    var parcels = [];
                    $(':checkbox:checked').each(function(i) {
                        var value = $(this).val();
                        if (value == 'on') { // Use '===' for comparison
                            return;
                        } else {
                            parcels[i] = value;
                        }
                    });
                    console.log(parcels);
                    if (parcels.length === 0) {
                        alert('Alert:: Please select a minimum of 1 parcel');
                    } else {
                        $('#parcel_id').val(parcels);
                        jQuery('#AssignParcelRiderModal').modal('show');
                    }
                });

                $('#TransferToHub').click(function(event) {
                    event.preventDefault();
                    var parcels = [];
                    $(':checkbox:checked').each(function(i) {
                        var value = $(this).val();
                        if (value == 'on') { // Use '===' for comparison
                            return;
                        } else {
                            parcels[i] = value;
                        }
                    });
                    console.log(parcels);
                    if (parcels.length === 0) {
                        alert('Alert:: Please select minimum 1 parcel');
                    } else {
                        $('#parcels_id').val(parcels);
                        jQuery('#TransferToHubModal').modal('show');
                    }
                });

            });
            // Transfer To Hub 
            $(document).ready(function(event) {

                $('#ReturnTohubAction').click(function(event) {
                    event.preventDefault();
                    var parcels = [];
                    $(':checkbox:checked').each(function(i) {
                        var value = $(this).val();
                        if (value == 'on') { // Use '===' for comparison
                            return;
                        } else {
                            parcels[i] = value;
                        }
                    });
                    console.log(parcels);
                    if (parcels.length === 0) {
                        alert('Alert:: Please select minimum 1 parcel');
                    } else {
                        $('#ret_parcel_id').val(parcels);
                        jQuery('#ReturnToHubModal').modal('show');
                    }
                });

            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                @if (session()->has('open_url') && session()->get('open_url') != '')
                    window.open('{{ session()->get('open_url') }}', '_blank');
                @endif
            });
        </script>

    @endsection
