@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@if($parceltype)
@section('title', $parceltype->title)
@else
@section('title', 'MY ALL PARCEL')
@endif
@section('content')
<div class="profile-edit mrt-30">
    <style>
        .cust-action-btn {
    width:130px;
}
.cust-action-btn li {
    display:inline-block;
}
.action_buttons ul {
	padding: 18px 15px;
}
.action_buttons li {
	list-style: none;
	display: inline-block;
}
.action_buttons button,.action_buttons a {
	border: 0;
    text-align: left;
	border-radius: 5px;
	padding: 6px 12px;
	color: #fff;
	float: left;;
}
.action_buttons button{
	cursor:pointer;
}
.action_buttons span {
	margin-left: 10px;
	margin-top: 8px;
	display: inline-block;
}

    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="body-title">
                @if($parceltype)
                <h5 class="pageStatusTitle">{{ $parceltype?->title }} PARCEL</h5>
                @else
                <h5 class="pageStatusTitle">MY ALL PARCEL</h5>
                @endif
            </div>
        </div>
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
                        <input type="text" class="form-control" placeholder="Customer name"
                            name="cname">
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
                        <button type="button" class="btn btn-success"
                            id="SearchDtataButton">Submit</button>
                    </div>
                    <!-- col end -->
                </div>
            </form>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner ">
                <table id="example333" class="table table-bordered table-striped custom-table table-responsive">
                    <thead>
                        <tr>
                            {{-- <th>Test Id</th> --}}
                            <th>Tracking ID</th>
                            <th>More</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Rider</th>
                            <th>Total</th>
                            <th>Declared Value</th>
                            <th>Charge</th>
                            <th>Cod Charge</th>
                            <th>Tax</th>
                            <th>Insurance</th>
                            <th>Sub Total</th>
                            <th>L. Update</th>
                            <th>Payment Status</th>
                            <th>Your Note</th>
                            <th>Admin Note</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- row end -->
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
</div>
@endsection
@section('custom_js_scripts')
<script>
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
    $(document).ready(function(event) {
        var slug = '{{ $slug }}' ?? '';
        var table33 = $('#example333').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            sorting: false,
            responsive: true,
            Xscroll: true,
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            ajax: {
                url: "{{ request()->has('month') && request()->month == 1 ? url('merchant/get_parcel_data_month') : url('merchant/get_parcel_data') }}" + '/' + slug,
                data: {

                }
            },

            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                {
                    extend: 'copy',
                    text: 'Copy',
                    exportOptions: {
                        columns: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
                        rows: function(idx, data, node) {
                            let found = false;
                            let selectedRowIndexes = table33.rows('.selected').indexes();
                            for (let index = 0; index < selectedRowIndexes.length; index++) {
                                if (idx == selectedRowIndexes[index]) {
                                    found = true;
                                    break;
                                }
                            }
                            return found;
                        }
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        columns: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                        rows: function(idx, data, node) {
                            let found = false;
                            let selectedRowIndexes = table33.rows('.selected').indexes();
                            for (let index = 0; index < selectedRowIndexes.length; index++) {
                                if (idx == selectedRowIndexes[index]) {
                                    found = true;
                                    break;
                                }
                            }
                            return found;
                        }
                    }
                },
                {
                    extend: 'excel',
                    text: 'D_Man',
                    exportOptions: {
                        columns: [1, 3, 4, 5, 7, 8, 10, 14],
                        rows: function(idx, data, node) {
                            let found = false;
                            let selectedRowIndexes = table33.rows('.selected').indexes();
                            for (let index = 0; index < selectedRowIndexes.length; index++) {
                                if (idx == selectedRowIndexes[index]) {
                                    found = true;
                                    break;
                                }
                            }
                            return found;
                        }
                    }
                },

                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        columns: [1, 3, 4, 5, 6, 7, 8, 9, 10],
                        rows: function(idx, data, node) {
                            let found = false;
                            let selectedRowIndexes = table33.rows('.selected').indexes();
                            for (let index = 0; index < selectedRowIndexes.length; index++) {
                                if (idx == selectedRowIndexes[index]) {
                                    found = true;
                                    break;
                                }
                            }
                            return found;
                        }
                    }
                },

                {
                    extend: 'print',
                    text: 'Print all',
                    exportOptions: {
                        columns: [1, 3, 4, 5, 6, 7, 8, 9, 10],
                        rows: function(idx, data, node) {
                            let found = true;
                            let selectedRowIndexes = table33.rows('.selected').indexes();
                            for (let index = 0; index < selectedRowIndexes.length; index++) {
                                if (idx == selectedRowIndexes[index]) {
                                    found = false;
                                    break;
                                }
                            }
                            return found;
                        }
                    }
                },
                {
                    extend: 'colvis',
                },

            ],
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
            var cname = $('input[name="cname"]').val();
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
                    url: "{{ url('merchant/get_parcel_data') }}" + '/' + slug,
                    data: {
                        filter_id: filter_id,
                        trackId: trackId,
                        phoneNumber: phoneNumber,
                        startDate: startDate,
                        endDate: endDate,
                        cname: cname,

                    }
                },

                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16,
                                17
                            ],
                            rows: function(idx, data, node) {
                                let found = false;
                                let selectedRowIndexes = table33.rows('.selected')
                                    .indexes();
                                for (let index = 0; index < selectedRowIndexes
                                    .length; index++) {
                                    if (idx == selectedRowIndexes[index]) {
                                        found = true;
                                        break;
                                    }
                                }
                                return found;
                            }
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16,
                                17
                            ],
                            rows: function(idx, data, node) {
                                let found = false;
                                let selectedRowIndexes = table33.rows('.selected')
                                    .indexes();
                                for (let index = 0; index < selectedRowIndexes
                                    .length; index++) {
                                    if (idx == selectedRowIndexes[index]) {
                                        found = true;
                                        break;
                                    }
                                }
                                return found;
                            }
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'D_Man',
                        exportOptions: {
                            columns: [1, 3, 4, 5, 7, 8, 10, 14],
                            rows: function(idx, data, node) {
                                let found = false;
                                let selectedRowIndexes = table33.rows('.selected')
                                    .indexes();
                                for (let index = 0; index < selectedRowIndexes
                                    .length; index++) {
                                    if (idx == selectedRowIndexes[index]) {
                                        found = true;
                                        break;
                                    }
                                }
                                return found;
                            }
                        }
                    },

                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: [1, 3, 4, 5, 6, 7, 8, 9, 10],
                            rows: function(idx, data, node) {
                                let found = false;
                                let selectedRowIndexes = table33.rows('.selected')
                                    .indexes();
                                for (let index = 0; index < selectedRowIndexes
                                    .length; index++) {
                                    if (idx == selectedRowIndexes[index]) {
                                        found = true;
                                        break;
                                    }
                                }
                                return found;
                            }
                        }
                    },

                    {
                        extend: 'print',
                        text: 'Print all',
                        exportOptions: {
                            columns: [1, 3, 4, 5, 6, 7, 8, 9, 10],
                            rows: function(idx, data, node) {
                                let found = true;
                                let selectedRowIndexes = table33.rows('.selected')
                                    .indexes();
                                for (let index = 0; index < selectedRowIndexes
                                    .length; index++) {
                                    if (idx == selectedRowIndexes[index]) {
                                        found = false;
                                        break;
                                    }
                                }
                                return found;
                            }
                        }
                    },
                    {
                        extend: 'colvis',
                    },

                ],
                createdRow: function(row, data, dataIndex) {
                    // Add your desired class to each <tr> element
                    $(row).addClass('data_all_trs');
                }

            });
        });
    });
</script>
@endsection