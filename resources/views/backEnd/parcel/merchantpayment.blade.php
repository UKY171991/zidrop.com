@extends('backEnd.layouts.master')
@section('title', 'Merchant payment history')
@section('content')
    <style>
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
        .alart{
            color: rgb(57, 56, 56);
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .icon{
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
        .attentionIcon{
            width: 150px;
            height: 150px;
            margin-right: 10px;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card">
                            <div class="col-sm-12">
                                <div class="manage-button">
                                    <div class="body-title">
                                        <h5>Merchant payment history</h5>
                                    </div>
                                </div>
                            </div>
                            <h4 class="alart"><img class="icon" src="{{asset('warning.png')}}" alt="">Before processing the merchant payment, ensure that you export the CSV file</h4>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {{-- <form action="{{url('editor/merchant/payment')}}" class="filte-form">
                         @csrf 
                        <div class="row">
                          <input type="hidden" value="1" name="filter_id">
                          <div class="col-sm-2 mt-2">
                            <input type="date" class="flatDate form-control" placeholder="Create Date Form" name="startDate">
                          </div>
                          <!-- col end -->
                          <div class="col-sm-2 mt-2">
                            <input type="date" class="flatDate form-control" placeholder="Create Date To" name="endDate">
                          </div>
                          
                          <!-- col end -->
                          <div class="col-sm-2 mt-2">
                            <button type="submit" class="btn btn-success">Submit </button>
                          </div>
                          <!-- col end -->
                        </div>
                      </form> --}}
                            </div>
                            <div class="card-body">
                                <form action="{{ url('editor/merchant/confirm-payment') }}" method="POST" id="myform"
                                    class="bulk-status-form">
                                    @csrf
                                    <input type="hidden" value="{{ request()->startDate }}" name="startDate">
                                    <input type="hidden" value="{{ request()->endDate }}" name="endDate">

                                    <button type="button" class="bulkbutton bulk-status-btn" id="MakepayButton">Make Merchant Payment</button>
                                    <a href="javascript:void(0)"
                                        data-href="{{ route('editor.merchant.payment.export-csv') }}"
                                        onclick="exportMerchantPayment(this, 'csv')" class="merchant-payment-export-btn"
                                        id="exportMerchantpayButton">
                                        Export CSV
                                    </a>
                                 
                                    {{-- <a href="{{ route('editor.merchant.payment.export-pdf') }}"
                                         class="merchant-payment-export-btn">
                                        Export PDF
                                    </a> --}}
                                    {{-- <a href="javascript:void(0)"
                                        data-href="{{ route('editor.merchant.returnpayment.export-pdf') }}"
                                        onclick="exportMerchantPayment(this, 'pdf')" class="merchant-payment-export-btn"
                                        >
                                        Export PDF
                                    </a> --}}
                                    <table id="" class="table table-bordered table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="My-Button"></th>
                                                <th>Id</th>
                                                <th>Merchant</th>
                                                <th>Payment Method</th>
                                                <th>Total Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($show_data as $key => $value)
                                                @php
                                                    $due = 0;
                                                    foreach ($value->parcels as $parcel) {
                                                        if ($parcel->status == 4 || $parcel->status == 6 ) {
                                                            // $due = $due + ($parcel->codCharge + $parcel->deliveryCharge - $parcel->cod);
                                                            $due = $due + $parcel->merchantDue;
                                                        }
                                                    }
                                                @endphp
                                                @if ($due > 0)
                                                    <tr>
                                                        <td><input type="checkbox" value="{{ $value->id }}"
                                                                name="parcel_id[]" form="myform"
                                                                class="selectcheckboxmerchant">
                                </form>
                                </td>
                                <td>{{ ++$key }}</td>
                                <td>{{ $value->companyName }}</td>
                                <td>{{ $value->paymentMethod ?? 'Not Set Yeat' }}</td>

                                <td>{{ $due }}</td>
                                </tr>
                                @endif
                                @endforeach
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
        {{-- <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId">
          Launch
        </button>
         --}}
        <!-- Modal -->
        <div class="modal fade" id="MakePayModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container-fluid text-center">
                            <img class="attentionIcon" src="{{asset('attention.png')}}" alt="">
                            <h5 class="text-bold">HAVE YOU EXPORTED CSV FILE?</h5>
                           <h6> Before processing the merchant payment, ensure that you export the CSV file.</h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <a href="javascript:void(0)"
                            data-href="{{ route('editor.merchant.payment.export-csv') }}"
                            onclick="exportMerchantPayment(this, 'csv')" class="merchant-payment-export-btn"
                            id="exportMerchantpayButton">
                            Export CSV
                        </a>
                        <button type="button" class="bulkbutton bulk-status-btn"
                        onclick="validateSubmitbutton()">Process</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            $('#exampleModal').on('show.bs.modal', event => {
                var button = $(event.relatedTarget);
                var modal = $(this);
                // Use above variables to manipulate the DOM
                
            });
        </script>
    </section>

    <!-- Modal Section  -->
@endsection
@section('custom_js_scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        function exportMerchantPayment(button, type) {
            var searchIDs = $("input.selectcheckboxmerchant:checkbox:checked").map(function() {
                return $(this).val();
            }).get();
            if (searchIDs.length < 1) {
                toastr.warning('Please select at-least one merchant', 'Oops!');
                return false;
            }
            var href = $(button).attr('data-href');
            var searchIDsString = encodeURIComponent(JSON.stringify(searchIDs));
            var full_url = href + '?merchants=' + searchIDsString + '&type=' + type;
            window.open(full_url, '_blank').focus();
        }

        function validateSubmitbutton() {
            var searchIDs = $("input.selectcheckboxmerchant:checkbox:checked").map(function() {
                return $(this).val();
            }).get();
            if (searchIDs.length < 1) {
                toastr.warning('Please select at-least one merchant', 'Oops!');
                return false;
            } else {
                // var url = "{{ route('editor.merchant.payment.export-csv') }}";
                // exportCSV(url, searchIDs);
                // var button = document.getElementById('exportMerchantpayButton');
                // button.click();
                $('#myform').submit();

            }

        }

        function exportCSV(url, searchIDs) {
            var searchIDsString = encodeURIComponent(JSON.stringify(searchIDs));
            var full_url = url + '?merchants=' + searchIDsString + '&type=' + 'csv';
            window.open(full_url, '_blank').focus();
        }
        $(document).ready(function() {
            $('#MakepayButton').click(function() {
    
                $('#MakePayModal').modal('show');
            });
        });
    </script>
@endsection
