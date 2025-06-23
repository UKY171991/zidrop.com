@extends('backEnd.layouts.master')
@section('title', 'Sales Report')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card SearchSection" >
                            <div class="col-sm-12">
                                <div class="manage-button">
                                    <div class="body-title">
                                        <h4>
                                            <b>
                                                <p style="color:green">Sales Report</P>
                                            </b>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container ">
                                    <div class="row">
                                        <form action="#" method="POST" class="needs-validation" >
                                            @csrf
                                            <div class="modal-body ">
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <label for="report_type" class="col-form-label">Report Type <strong
                                                                style="color:red">*</strong></label>
                                                        <select name="report_type" id="report_type" class="form-control"
                                                            required>
                                                            <option value="">Select Report Type</option>
                                                            <option value="del_charge">Delivery charge</option>
                                                            <option value="cod_charge">COD Charge</option>
                                                            <option value="tax">Tax </option>
                                                            <option value="insurance">Insurance</option>
                                                            <option value="walet_topup">Wallet Topup</option>
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please Select a Report Type.
                                                        </div>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                        @error('report_type')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    {{-- <div class="col-md-3 mb-2">
                                                        <label for="parcel_type" class="col-form-label">Parcel Type <strong
                                                                style="color:red">*</strong></label>
                                                        <select name="parcel_type" id="parcel_type" class="form-control"
                                                            >
                                                            <option value="">Select Parcel Type</option>
                                                            @foreach ($parceltypes as $item)
                                                                <option value="{{ $item->id }}">{{ $item->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please Select a Parcel Type.
                                                        </div>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                        @error('parcel_type')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div> --}}
                                                    {{-- <div class="col-md-3 mb-2">
                                                        <label for="agent_id" class="col-form-label">Agent </label>
                                                        <select name="agent_id" id="agent_id" class="form-control">
                                                            <option value="">Select Agent</option>
                                                            @foreach ($agents as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please Select a valid Agent.
                                                        </div>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                        @error('agent_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label for="merchant_id" class="col-form-label">Merchant </label>
                                                        <select name="merchant_id" id="merchant_id" class="form-control">
                                                            <option value="">Select Merchant</option>
                                                            @foreach ($merchants as $item)
                                                                <option value="{{ $item->id }}">{{ $item->companyName }}
                                                                </option>
                                                            @endforeach


                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please Select a valid Merchant.
                                                        </div>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                        @error('merchant_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label for="deliveryman_id" class="col-form-label">Deliveryman
                                                        </label>
                                                        <select name="deliveryman_id" id="deliveryman_id"
                                                            class="form-control">
                                                            <option value="">Select Deliveryman</option>
                                                            @foreach ($deliverymen as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please Select a valid Deliveryman.
                                                        </div>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                        @error('deliveryman_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div> --}}

                                                    <div class="col-md-4 mb-2">
                                                        <label for="request_date" class="col-form-label">Start Date <strong
                                                                style="color:red">*</strong></label>
                                                        <input type="date" name="start_date" id="start_date"
                                                            class="form-control" value="{{ date('Y-m-d') }}" required>

                                                    </div>


                                                    <div class="col-md-4 mb-2">
                                                        <label for="mode_of_payment" class="col-form-label">End Date <strong
                                                                style="color:red">*</strong></label>
                                                        <input type="date" name="end_date" id="end_date"
                                                            class="form-control" value="{{ date('Y-m-d', strtotime('+30 day')) }}"   required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">

                                                <button type="button" id="datasearch"
                                                    class="btn btn-primary">Search</button>
                                                <!-- <button type="button" id="dataPrint" class="btn btn-primary">Print</button> -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <div class="card">
                            <div class="card-body">
                              <div class=" mb-0" id="datareport" data-pattern="priority-columns">
                                     
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_js_scripts')
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



    <script>
        $(function() {
            $(document.body).on('click', '#btnExport', function() {
                let table = document.getElementsByTagName("table");

                TableToExcel.convert(table[0], {
                    name: `business.xlsx`,
                    sheet: {
                        name: 'business'
                    }
                });
            });
            $(document.body).on('click', '#datasearch', function(e) {
                e.preventDefault();
                // var parcel_type = $('#parcel_type').val();
                // var agent_id = $('#agent_id').val();
                // var merchant_id = $('#merchant_id').val();
                // var deliveryman_id = $('#deliveryman_id').val();
                var report_type = $('#report_type').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                if (report_type == '') {
                    toastr.warning('Please select at-least one Report Type', 'Oops!');
                    return false;
                }
                
                $.ajax({
                    url: "{{ route('admin.report.salse.search') }}",
                    type: "get",
                    data: {
                        report_type,
                        start_date,
                        end_date
                    },
                    success: function(response) {
                        $('#datareport').html(response);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: "The Warrning?",
                            text: "Something Is wrong",
                            icon: 'question',
                            confirmButtonColor: '#556ee6'
                        });
                        return false;
                    }
                });
            });

            $(document.body).on('click','.pdf-download',function(){
            var branch_id = $('#branch_id').val();
            var product_id = $('#product_id').val();
            var agent_id = $('#agent_id').val();
            var customer_id = $('#customer_id').val();
            var document_no = $('#document_no').val();
            var mr_no = $('#mr_no').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            $.ajax({
                url: "{{url('admin/searchReportsDetailsPrint')}}",
                type: "get",
                data: {branch_id, product_id, agent_id, customer_id, document_no, mr_no,start_date, end_date} ,
                success: function (response) {
                    window.open(response);
                
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: "The Warrning?",
                        text: "Something Is wrong",
                        icon: 'question',
                        confirmButtonColor: '#556ee6'
                    });
                    return false;
                }
            });
        });
          
        });
    </script>
@endsection
