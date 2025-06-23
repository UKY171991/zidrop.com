@extends('backEnd.layouts.master')
@if ($parceltype)
    @section('title', $parceltype->title)
@else
    @section('title', 'ALL PARCEL')
@endif
@section('content')
    <style>
        .display_hide_for_camera {
            display: none !important;
        }

        @media screen {
            #printSection {
                display: none !important;
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

            #example333_filter {
                display: none !important;
            }
        }

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
        }

        /* .btn-secondary{
                            background-color: #4634FF !important;
                            border-color: #6c757d !important;
                        } */
        #example333 thead tr th {
            background-color: #4634FF !important;
            color: #fff !important;
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
    </style>
    <!-- Main content -->
    <section class="content">
        <?php
        // $show_data = session()->get('show_data');
        ?>
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
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="body-title">

                            @if ($parceltype)
                                <h5 class="pageStatusTitle">{{ $parceltype?->title }} PARCEL</h5>
                            @else
                                <h5 class="pageStatusTitle">ALL PARCEL</h5>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mt-2 mr-auto text-right">
                        <button class="btn btn-primary mr-3" id="scanStart" title="Scan Bar Code"><i
                                class="fa fa-qrcode"></i> Scan Barcode</button>

                        <div id="scaninput" style="display: none; text-align:right;">
                            <input type="text" class="form-control2 mt-3 mb-3 " id="scannerCodesubmit"
                                placeholder="Click Here and Then Scan">
                            <button class="btn btn-primary mr-3" id="scanStartStop" title="Scan Bar Code"><i
                                    class="fa fa-qrcode"></i> Stop Scan</button>
                        </div>
                    </div>
                    <audio id="beepSound" preload="auto">
                        <source src="{{ asset('beep-08b.mp3') }}" type="audio/mpeg">
                    </audio>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card">

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <form action="" class="">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" value="1" name="filter_id">
                                        <div class="col-sm-2 mt-2">
                                            <input type="text" class="form-control" placeholder="Track Id"
                                                name="trackId">
                                        </div>
                                        <!-- col end -->
                                        <div class="col-sm-2 mt-2">
                                            <input type="text" class="form-control" placeholder="Customer name"
                                                name="cname">
                                        </div>

                                        <!--<div class="col-sm-2 mt-2">
                                                                <input type="text" class="form-control" placeholder="Address" name="address">
                                                              </div>-->

                                        <div class="col-sm-2 mt-2">
                                            <input type="number" class="form-control" placeholder="Phone Number"
                                                name="phoneNumber">
                                        </div>
                                        <!-- col end -->
                                        <div class="col-sm-2 mt-2">
                                            <input type="number" class="form-control" placeholder="Merchant Id"
                                                name="merchantId">
                                        </div>
                                        <!-- col end -->
                                        <div class="col-sm-2 mt-2">
                                            <input type="date" class="flatDate form-control"
                                                placeholder="Create Date Form" name="startDate">
                                        </div>
                                        <!-- col end -->
                                        <div class="col-sm-2 mt-2">
                                            <input type="date" class="flatDate form-control" placeholder="Create Date To"
                                                name="endDate">
                                        </div>
                                        <!-- col end -->
                                        <div class="col-sm-2 mt-2">
                                            <input type="date" class="flatDate form-control"
                                                placeholder="Update Date Form" name="upstartDate">
                                        </div>
                                        <!-- col end -->
                                        <div class="col-sm-2 mt-2">
                                            <input type="date" class="flatDate form-control" placeholder="Update Date To"
                                                name="upendDate">
                                        </div>
                                        <!-- col end -->
                                        <div class="col-sm-2 mt-2">
                                            <button type="button" class="btn btn-success"
                                                id="SearchDtataButton">Submit</button>
                                        </div>
                                        <!-- col end -->
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <form action="{{ url('editor/dliveryman-asign/bulk-option') }}" method="POST"
                                    id="myform" class="bulk-status-form">
                                    @csrf
                                    <!--                       <select name="deliverymanId" class="bulkselect" form="myform">
                                                          <option value="">Select all & Asign</option>
                                                             @foreach ($deliverymen as $key => $dman)
    <option value="{{ $dman->id }}">{{ $dman->name }}</option>
    @endforeach
                                                                                              
                                                          </select>
                                                          <select class="bulkselect" name="asigntype">
                                                              <option value="1">Pickup</option>
                                                              <option value="2">Delivery</option>
                                                          </select>
                                                         <button type="submit" name="btn" value="rider" class="bulkbutton bulk-status-btn">Apply</button> -->

                                    {{-- <select name="agentId" class="bulkselect" form="myform" required="required">
                                        <option value="">Select all & Asign</option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach

                                    </select>

                                    <select class="bulkselect" name="transferhub">
                                        <option value="1">Transfer To Hub</option>
                                    </select>
                                    <button type="submit" name="btn" value="agent"
                                        class="bulkbutton bulk-status-btn">Apply</button> --}}

                                    {{-- check if status id is 12 --}}

                                    {{-- @if ($slug == 'out-for-delivery')
                                        <button id="ReturnTohubAction" type="button" class="bulkbutton btn-danger  mb-2"
                                            data-slug="out-for-delivery" style="background:#00796B">Return To Origin Hub</button>
                                    @endif --}}
                                    @if ($slug == 'pending')
                                        <button id="PickedUpUpdatee" type="button" class="bulkbutton btn  mb-2"
                                            data-slug="picked_up" style="background:#00796B">Pick
                                            Up</button>
                                            <button id="PrintSelectedItemsInvoice" type="button"
                                            class="btn btn-secondary mb-2" style=""> <i class="fa fa-print"></i> Print Waybill</button>
                                    @else
                                        <button id="TransferToHubParcelll" type="button"
                                            class="bulkbutton bulk-status-btn mb-2">Transfer To Hub</button>
                                        <button id="MassUpdateParcelll" type="button"
                                            class="bulkbutton btn-danger bg-danger mb-2">Mass Update</button>
                                    @endif
                                    <p class="selectedItemshow" style="">0</p>

                                    <table id="ScanDatatable" class="table table-bordered table-striped custom-table">

                                    </table>
                                    <table id="example333" class="table table-bordered table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th> <input type="checkbox" id="My-Buttonn"></th>
                                                <th>Tracking</th>
                                                <th>Action</th>
                                                <th>Merchant Id</th>
                                                <th>Create_Date</th>
                                                <th>Company_Name</th>
                                                <th>Customer</th>
                                                <th width="10%">City/Town</th>
                                                <th>Full Address</th>
                                                <th>Phone</th>
                                                <th>Pickman</th>
                                                <th>Rider</th>
                                                <th>Agent</th>
                                                <th>Last Update</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Declared Value</th>
                                                <th>Charge</th>
                                                <th>Cod Charge</th>
                                                <th>Tax</th>
                                                <th>Insurance</th>
                                                <th>Sub Total</th>
                                                <th>Pay Return ?</th>
                                                <th>Pay ?</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        {{-- <div class="py-3">{{$show_data->links()}}</div> --}}

                    </div>
                </div>
            </div>
        </div>
        {{-- All Modals --}}

        {{-- Pickman modal --}}
        <!-- Modal -->
        <div id="pickupmanModalUpdate" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pickupman Asign</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('editor/pickupman/asign') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hidden_id" id="pickman_hidden_id" value="">
                            <input type="hidden" name="merchant_phone" id="pickman_merchant_phone" value="">
                            <div class="form-group">
                                <select name="pickupmanId" class="form-control" id="">
                                    <option value="">Select</option>
                                    @foreach ($deliverymen as $key => $deliveryman)
                                        <option value="{{ $deliveryman->id }}">{{ $deliveryman->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- form group end -->
                            <div class="form-group">
                                <textarea name="note" class="form-control"></textarea>
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
        {{-- Deliveryman modal --}}
        <!-- Modal -->
        <div id="asignModalUpdate" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deliveryman Assign</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('editor/deliveryman/asign') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hidden_id" id="deliveryman_hidden_id" value="">
                            <input type="hidden" name="merchant_phone" id="deliveryman_merchant_phone" value="">
                            <div class="form-group">
                                <select name="deliverymanId" class="form-control" id="deliverymanidselectvalue">
                                    <option value="">Select</option>
                                    @foreach ($deliverymen as $key => $deliveryman)
                                        <option value="{{ $deliveryman->id }}">{{ $deliveryman->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- form group end -->
                            <div class="form-group">
                                <textarea name="note" class="form-control"></textarea>
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
        {{-- Agent Modal --}}
        <!-- Modal -->
        <div id="agentModalUpdate" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agent Asign</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('editor/agent/asign') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hidden_id" id="agent_hidden_id" value="">
                            <input type="hidden" name="merchant_phone" id="agent_merchant_phone" value="">
                            <div class="form-group">
                                <select name="agentId" class="form-control" id="agentis_selecetvalue">
                                    <option value="">Select</option>
                                    @foreach ($agents as $key => $agent)
                                        <option value="{{ $agent->id }}">
                                            {{ $agent->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <textarea name="note" class="form-control"></textarea>
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
        {{-- parcel Update Modal --}}
        <!-- Modal -->
        <div id="sUpdateModalUpdate" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content--->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Parcel Status Update</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('editor/parcel/status-update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hidden_id" value="" id="par_up_hidden_id">
                            <input type="hidden" name="customer_phone" value="" id="par_up_customer_phone">
                            <div class="form-group">
                                <select name="status" onchange="percelDelivery(this)" class="form-control"
                                    id="parcel_status_update">
                                    @foreach ($parceltypes as $key => $ptvalue)
                                        @if ($ptvalue->id == 11)
                                            @continue
                                        @endif
                                        <option value="{{ $ptvalue->id }}">
                                            {{ $ptvalue->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mrt-15">
                                <select name="note" class="form-control" id="" required>
                                    <option value="">Select</option>
                                    @foreach ($allnotelist as $key => $notelist)
                                        <option value="{{ $notelist->title }}">
                                            {{ $notelist->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- form group end -->
                            <div class="form-group">
                                <div class="customerpaid" style="display: none;">
                                    <input type="text" class="form-control" value="{{ old('customerpay') }}"
                                        id="customerpay" name="customerpay" placeholder="customer pay" /><br />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="partialpayment" style="display: none;">
                                    <input type="text" class="form-control CommaSeperateValueSet"
                                        value="{{ old('partial_payment') }}" id="partial_payments"
                                        name="partial_payment" placeholder="Partial pay" /><br />
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
                                <td>Company</td>
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
        {{-- mass update modal --}}
        <div class="modal fade" id="MassUpdateParcelModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Parcel Status Update</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('editor/parcel/status-mass-update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="par_up_mass_hidden_ids" value=""
                                id="par_up_mass_hidden_ids">

                            <div class="form-group">
                                <select name="status" class="form-control" id="parcel_status_mass_update">
                                    @foreach ($parceltypes as $key => $ptvalue)
                                        @if ($ptvalue->id == 11)
                                            @continue
                                        @endif
                                        {{-- @php
                                            if (in_array($ptvalue->id, [8, 9])) {
                                                continue;
                                            }
                                        @endphp --}}
                                        <option value="{{ $ptvalue->id }}"
                                            @if ($ptvalue->id == 6) @disabled(true) @endif>
                                            {{ $ptvalue->title }}</option>
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
        {{-- Transfer To Hub modal --}}
        <div class="modal fade" id="TransferToHubModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transfer To Hub</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('editor/dliveryman-asign/bulk-option') }}" method="POST">
                            @csrf
                            <input type="hidden" name="parcel_id" value="" id="parcel_id">
                            <input type="hidden" name="btn" value="agent" id="btn">

                            <div class="form-group">
                                <select name="agentId" class="bulkselect form-control" required="required">
                                    <option value="">Select Hub</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
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
                        <form action="{{ url('editor/returntohub/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="return_parcel_id" value="" id="return_parcel_id">
                            <input type="hidden" name="btn" value="agent" id="btn">

                            <div class="form-group">
                                <select name="agentId" class="bulkselect form-control" required="required">
                                    <option value="">Select Hub</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
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

    </section>

    <!-- Modal Section  -->
@endsection

@section('custom_js_scripts')
    {{-- Pick update --}}
    <script>
        // PickedUp-Update
        $(document).ready(function(event) {
            $('#PickedUpUpdatee').click(function(event) {
                event.preventDefault(); // Prevent default form submission behavior         


                var parcels = [];
                var slug = "picked_up";

                // Iterate over checked checkboxes
                $(':checkbox:checked').each(function() {
                    var value = $(this).val();
                    if (value !== 'on') { // Use strict comparison to avoid unwanted type coercion
                        parcels.push(value); // Use push to add value to the parcels array
                    }
                });

                console.log(parcels); // Log the selected parcels for debugging purposes

                // Check if at least one parcel is selected
                if (parcels.length === 0) {
                    alert('Alert: Please select at least 1 parcel');
                    $('#spinnerr').hide(); // Hide spinnerr if no parcels are selected
                } else {
                    // Make AJAX call to update parcel status

                    var spiner = $('#spinnerr');
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('editor.parcel.singlestatusupdate') }}",
                        dataType: 'JSON',
                        data: {
                            parcels: parcels,
                            slug: slug,
                            _token: '{{ csrf_token() }}' // Include CSRF token for security
                        },
                        success: function(results) {
                            if (results.success === true) { // Use strict comparison
                                // Show toastr success message for 2 seconds
                                toastr.success('Parcel has been picked up', '', {
                                    timeOut: 2000
                                });
                                // Reload the page after 700ms
                                setTimeout(function() {
                                    location.reload();
                                }, 700);
                            } else {
                                console.log(results.message); // Log the error message
                            }
                        },
                        error: function() {
                            // Handle any errors
                            alert('An error occurred. Please try again.');
                        },
                        complete: function() {
                            // Hide the spinnerr once the request is complete
                            $('#spinnerr').hide();
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

                    var url = "{{ route('editor.parcel.PrintSelectedItems') }}?parcels=" + parcels.join(',');

                    window.open(url, '_blank'); // Open the PDF in a new tab
                });

        });
    </script>
    {{-- Modal Script --}}
    <script>
        $(document.body).on('click', '#pickupmanModal', function() {
            event.preventDefault();
            var id = $(this).data('id');
            var merchant_phone = $(this).data('merchant_phone');
            $('#pickman_hidden_id').val(id);
            $('#pickman_merchant_phone').val(merchant_phone);
            $('#pickupmanModalUpdate').modal('show');
        });
        $(document.body).on('click', '#asignModal', function() {
            event.preventDefault();
            var id = $(this).data('id');
            var merchant_phone = $(this).data('merchant_phone');
            var deliverymanidselectvalue = $(this).data('deliverymanidselectvalue');
            $('#deliveryman_hidden_id').val(id);
            $('#deliveryman_merchant_phone').val(merchant_phone);
            $('#deliverymanidselectvalue').val(deliverymanidselectvalue);
            $('#asignModalUpdate').modal('show');
        });
        $(document.body).on('click', '#agentModal', function() {
            event.preventDefault();
            var id = $(this).data('id');
            var merchant_phone = $(this).data('merchant_phone');
            var agentis_selecetvalue = $(this).data('agentis_selecetvalue');
            $('#agent_hidden_id').val(id);
            $('#agent_merchant_phone').val(merchant_phone);
            $('#agentis_selecetvalue').val(agentis_selecetvalue);
            $('#agentModalUpdate').modal('show');
        });
        $(document.body).on('click', '#sUpdateModal', function() {
            event.preventDefault();
            var id = $(this).data('id');
            var recipientPhone = $(this).data('recipientPhone');
            var parcel_status_update = $(this).data('parcel_status_update');
            $('#par_up_hidden_id').val(id);
            $('#par_up_customer_phone').val(recipientPhone);
            $('#parcel_status_update').val(parcel_status_update);
            $('#sUpdateModalUpdate').modal('show');
        });
        $(document.body).on('click', '#merchantParcelh', function() {
            event.preventDefault();
            var id = $(this).data('id');
            var url = "{{ url('/admin/get_parcel_history') }}/" + id;
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
            $('#firstNamelastName').html(firstname + ' ' + lastname);
            $('#phoneNumber').html(phonenumber);
            $('#emailAddress').html(emailaddress);
            $('#companyName').html(companyname);
            $('#recipientName').html(recipientname);
            $('#recipientAddress').html(recipientaddress);
            $('#deliverytitle').html(delivery);
            $('#pickuptitle').html(pickup);
            $('#cod').html(cod);
            $('#order_number').html(order_number);
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
    </script>
    {{-- Datatabe --}}
    <script>
        $(document).ready(function(event) {
            var currentUrl = window.location.href;
            var urlSegments = currentUrl.split('/');
            var lastSegment = urlSegments[urlSegments.length - 1];
            var parcelSlug = lastSegment.split('?')[0];

            var table33 = $('#example333').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                sorting: false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all']
                ],
                ajax: {
                    url: "{{ url('editor/admin_get_parcel_data') }}" + '/' + parcelSlug,
                    data: {

                    }
                },
                scrollX: true,

                dom: 'trip',

                createdRow: function(row, data, dataIndex) {
                    // Add your desired class to each <tr> element
                    $(row).addClass('data_all_trs');
                }

            });

            // Searching
            $('#SearchDtataButton').click(function() {

                var filter_id = $('input[name="filter_id"]').val();
                var trackId = $('input[name="trackId"]').val();
                var cname = $('input[name="cname"]').val();
                var phoneNumber = $('input[name="phoneNumber"]').val();
                var merchantId = $('input[name="merchantId"]').val();
                var startDate = $('input[name="startDate"]').val();
                var endDate = $('input[name="endDate"]').val();
                var upstartDate = $('input[name="upstartDate"]').val();
                var upendDate = $('input[name="upendDate"]').val();
                var url = "{{ url('editor/admin_get_parcel_data') }}" + '/' + parcelSlug;
                // destroly datatable
                var table33 = $('#example333').DataTable();
                table33.destroy();
                $('#example333').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    sorting: false,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        ['10 rows', '25 rows', '50 rows', 'Show all']
                    ],
                    ajax: {
                        url: "{{ url('editor/admin_get_parcel_data') }}" + '/' + parcelSlug,
                        data: {
                            filter_id: filter_id,
                            trackId: trackId,
                            cname: cname,
                            phoneNumber: phoneNumber,
                            merchantId: merchantId,
                            startDate: startDate,
                            endDate: endDate,
                            upstartDate: upstartDate,
                            upendDate: upendDate,
                        }
                    },
                    scrollX: true,

                    dom: 'trip',

                    createdRow: function(row, data, dataIndex) {
                        // Add your desired class to each <tr> element
                        $(row).addClass('data_all_trs');
                    }

                });
            });



        });
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
        $(document).ready(function() {
            // $("#Mcheck_all_check").change(function() {

            //  });
            var table33 = $('#dd').DataTable({
                dom: 'trip',
                sorting: false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all']
                ],


            });

        });
    </script>
    {{-- Scaning --}}
    <script>
        //  Scaning 
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        var OLDTrackingCode = '';
        var currentUrl = window.location.href;
        var urlSegments = currentUrl.split('/');
        var lastSegment = urlSegments[urlSegments.length - 1];
        var parcelSlug = lastSegment.split('?')[0];

        $('#scannerCodesubmit').on('input', function() {
            let scannedCode = $(this).val();
            var CodeLength = scannedCode.length;

            // Check if the input has a value and is different from the previous scanned code
            if (scannedCode.length > 0 && CodeLength === 12) {
                // Trigger the search function
                searchCode(scannedCode);
                OLDTrackingCode = scannedCode;
            }
        });

        function searchCode(code) {
            console.log('ajax function')
            $.ajax({
                type: 'GET',
                url: "{{ url('editor/deliveryman/view/get_parcel_by_qr/') }}/" + code,
                dataType: 'JSON',
                data: {
                    slug: parcelSlug,
                },
                success: function(results) {
                    console.log('success function')
                    if (results.success) {
                        console.log(results.message);

                        // Clear the table before adding new results
                        $('#example333_wrapper thead').hide();
                        $('#example333 tbody').empty();

                        // Clear the input field after a successful scan
                        $('#scannerCodesubmit').val('');

                        // Append the search results to the table
                        $('#example333').append(results.html);

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
            $('#example333 tbody').empty();

            var table33 = $('#example333').DataTable();
            table33.ajax.reload();
            OLDTrackingCode = '';
            $(".selectedItemshow").text(0);
            $(".selectedItemshow").css('display', 'none');

            // Clear Laravel Session
            var sessionName = 'OLDTrackingCodesQRscans';
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
            // Wait for 1 second before reloading the DataTable
            setTimeout(function() {
                $('#example333_wrapper thead').show();
            }, 800); // 1000 milliseconds = 1 second



        });
    </script>

    {{-- mass Update --}}
    <script>
        $(document).ready(function(event) {
            $('#MassUpdateParcelll').click(function(event) {
                event.preventDefault();
                // alert('clicked');

                var parcels = [];
                var status = [];
                $(':checkbox:checked').each(function(i) {
                    parcels[i] = $(this).val();
                    status[i] = $(this).data('status');
                });
                // console.log(parcels);
                // console.log(status);
                if (parcels.length === 0) {
                    alert('Alert:: Please select minimum 1 parcel');
                } else {
                    $('#par_up_mass_hidden_ids').val(parcels);
                    if (parcels.length === 1) {
                        $('#parcel_status_mass_update').val(status[0]);
                    } else {
                        $('#parcel_status_mass_update').val(status[1]);
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

    {{-- Transfer To Hub --}}
    <script>
        $(document).ready(function(event) {
            $('#TransferToHubParcelll').click(function(event) {
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
                    $('#parcel_id').val(parcels);
                    jQuery('#TransferToHubModal').modal('show');
                }
            });
        });


        $('#example333 thead tr th').css('background-color', '#4634FF');
    </script>
    {{-- Return To Origin Hub --}}
    <script>
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
                    $('#return_parcel_id').val(parcels);
                    jQuery('#ReturnToHubModal').modal('show');
                }
            });
        });


        $('#example333 thead tr th').css('background-color', '#4634FF');
    </script>
@endsection
