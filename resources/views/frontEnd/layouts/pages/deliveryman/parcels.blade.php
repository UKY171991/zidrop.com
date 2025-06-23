@extends('frontEnd.layouts.pages.deliveryman.master')
@if ($parceltype)
    @section('title', $parceltype->title)
@else
    @section('title', 'MY ALL PARCEL')
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
    </style>
    <div class="profile-edit mrt-30">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="body-title">
                    @if ($parceltype)
                        <h5 class="pageStatusTitle">{{ $parceltype?->title }} PARCEL</h5>
                    @else
                        <h5 class="pageStatusTitle">MY ALL PARCEL</h5>
                    @endif
                </div>
            </div>
            {{-- Searching Form --}}
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
                            <input type="number" class="form-control" placeholder="Phone Number" name="phoneNumber">
                        </div>
                        <!-- col end -->
                        <div class="col-sm-2">
                            <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate">
                        </div>
                        <!-- col end -->
                        <div class="col-sm-2">
                            <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate">
                        </div>
                        <!-- col end -->
                        <div class="col-sm-2">
                            <button type="button" id="SearchDtataButton" class="btn btn-success">Submit </button>
                        </div>
                        <!-- col end -->
                    </div>
                </form>
            </div>
            {{-- Table --}}
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="tab-inner table-responsive">
                    <table id="example333" class="table  table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="My-Buttonn"></th>
                                <th>Tracking ID</th>
                                <th>More</th>
                                <th>Date</th>
                                <th>Shop Name</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Charge</th>
                                <th>Tax</th>
                                <th>Insurance</th>
                                <th>Sub Total</th>
                                <th>L. Update</th>
                                <th>Payment Status</th>
                                <th>Merchant Id</th>
                                <th>Assign Type</th>
                                <th>Note</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- row end -->
    </div>
   
    {{-- View Modal --}}
        <div class="modal fade" id="merchantParcelUpdate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">

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
    {{-- S Update Modal --}}
    <!-- Modal -->
        <div class="modal fade" id="sUpdateModalUpdate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">

        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Parcel Status Update</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ url('deliveryman/parcel/status-update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="hidden_id" value="" id="par_up_hidden_id">
                        <input type="hidden" name="customer_phone" value="" id="par_up_customer_phone">
                        <div class="form-group">
                            <select name="status" onchange="percelDelivery(this)" class="form-control"
                                id="parcel_status_update">

                                @foreach ($parceltypes as $key => $ptvalue)
                                    @php
                                        if (in_array($ptvalue->id, [1,2, 10, 12, 8, 9, 11])) {
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
  
@endsection
@section('custom_js_scripts')
    <script>
    

        $(document.body).on('click', '#merchantParcel', function(event) {
            event.preventDefault(); // Fix: Pass event
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
            var cod = $(this).data('cod');
            var order_number = $(this).data('order_number');
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
    </script>
    <script>
        // {{-- Datatable --}}
        $(document).ready(function(event) {
            var slug = '{{ $slug }}' ?? '';

            var table33 = $('#example333').DataTable({
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
                    url: "{{ url('rider/rider_get_parcel_data') }}" + '/' + slug,
                    data: {

                    }
                },

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
                var phoneNumber = $('input[name="phoneNumber"]').val();
                var startDate = $('input[name="startDate"]').val();
                var endDate = $('input[name="endDate"]').val();
                var UpStatusArray = $('#Upstatuss').val() ?? null;
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
                        url: "{{ url('rider/rider_get_parcel_data') }}" + '/' + slug,
                        data: {
                            filter_id: filter_id,
                            trackId: trackId,
                            phoneNumber: phoneNumber,
                            startDate: startDate,
                            endDate: endDate,
                            UpStatusArray: UpStatusArray,
                        }
                    },

                    dom: 'trip',

                    createdRow: function(row, data, dataIndex) {
                        // Add your desired class to each <tr> element
                        $(row).addClass('data_all_trs');
                    }

                });
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
                    selectedItemshow = 0 ;
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
    </script>
@endsection
