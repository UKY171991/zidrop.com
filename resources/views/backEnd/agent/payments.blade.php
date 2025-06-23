@extends('backEnd.layouts.master')
@section('title', 'Agent payment history')
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
                                        <h5>Agent payment history</h5>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {{-- <form action="{{url('editor/agent/payment')}}" class="filte-form">
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
                                <form action="{{ url('editor/agent/confirm-payment') }}" method="POST" id="myform"
                                    class="bulk-status-form">
                                    @csrf
                                    <input type="hidden" value="{{ request()->startDate }}" name="startDate">
                                    <input type="hidden" value="{{ request()->endDate }}" name="endDate">

                                    <button type="button" class="bulkbutton bulk-status-btn"
                                        onclick="validateSubmitbutton()">Approve Agent Payment</button>
                                    <!-- <a href="javascript:void(0)"
                                        data-href="{{ route('editor.merchant.payment.export-csv') }}"
                                        onclick="exportAgentPayment(this, 'csv')" class="agent-payment-export-btn">
                                        Export CSV
                                    </a>
                                    <a href="javascript:void(0)"
                                        data-href="{{ route('editor.merchant.payment.export-csv') }}"
                                        onclick="exportAgentPayment(this, 'xlsx')" class="agent-payment-export-btn">
                                        Export XLSX
                                    </a> -->
                                    <table id="" class="table table-bordered table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="My-Button"></th>
                                                <th>Id</th>
                                                <th>Agent</th>
                                                <th>Total Due</th>
                                                <th>Request Date</th>
                                                <th>Payment Status</th>
                                                <th>Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($show_data as $key => $value)
                                                @php
                                                    $due = $value->due;
                                                    $agentname = App\Agent::find($value->agentId)->name;
                                                @endphp
                                                @if ($due > 0)
                                                    <tr>
                                                        <td>
                                                            @if($value->status!=1)
                                                            <input type="checkbox" value="{{ $value->id }}"
                                                                name="payment_id[]" form="myform"
                                                                class="selectcheckboxagent">
                                                            @endif
                                                            </td>
                                                        </form>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $agentname }}</td>
                                                        <td>N{{ number_format($due ?? 0, 2) }}</td>
                                                        <td>{{ $value->created_at }}</td>
                                                        <td>{{ $value->status==1 ? 'Approved' : 'Requested' }}</td>
                                                        <td>
                                                             <a href="/editor/agent/payment-invoice/{{$value->id}}" class="btn btn-sm btn-primary" target="__blank">
                                                                <i class="fa fa-eye"></i>
                                                             </a>
                                                        </td>
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
    </section>

    <!-- Modal Section  -->
@endsection
@section('custom_js_scripts')
    <script>
        function exportAgentPayment(button, type) {
            var searchIDs = $("input.selectcheckboxagent:checkbox:checked").map(function() {
                return $(this).val();
            }).get();
            if (searchIDs.length < 1) {
                toastr.warning('Please select at-least one agent', 'Oops!');
                return false;
            }
            var href = $(button).attr('data-href');
            var searchIDsString = encodeURIComponent(JSON.stringify(searchIDs));
            var full_url = href + '?agents=' + searchIDsString + '&type=' + type;
            window.open(full_url, '_blank').focus();
        }

        function validateSubmitbutton() {
            var searchIDs = $("input.selectcheckboxagent:checkbox:checked").map(function() {
                return $(this).val();
            }).get();
            if (searchIDs.length < 1) {
                toastr.warning('Please select at-least one agent', 'Oops!');
                return false;
            } else {
                var url = "{{ route('editor.merchant.payment.export-csv') }}";
                // exportCSV(url, searchIDs);
                $('#myform').submit();
            }

        }

        function exportCSV(url, searchIDs) {
            var searchIDsString = encodeURIComponent(JSON.stringify(searchIDs));
            var full_url = url + '?agents=' + searchIDsString + '&type=' + 'csv';
            window.open(full_url, '_blank').focus();
        }
    </script>
@endsection
