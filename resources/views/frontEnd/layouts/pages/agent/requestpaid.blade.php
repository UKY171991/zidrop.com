@extends('frontEnd.layouts.pages.agent.agentmaster')
@section('title', 'Request Paid')
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
    </style>
    <div class="container-fluid">
        <div class="box-content">
            <div class="row">

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card">
                        
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between border p-4 mb-4">
                                <div>
                                    <h3>Total Due: <b> N{{ number_format($due ?? 0, 2) }}</b></h3>
                                </div>
                                <div>
                                    @if($due>0)
                                    <form action="{{ route('agent.parcel.requestpaid') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="due" value="{{ $due }}">
                                        <button type="submit" class="btn btn-primary"> Confirm Payment </button>
                                    </form>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-inner table-responsive">
                                <table id="example"
                                    class="table table-bordered table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="col-2">SL Id</th>
                                            <th class="col-3">Due Amount</th>
                                            <th class="col-2">Request Date</th>
                                            <th class="col-3">Payment Status</th>
                                            <th class="col-3">Total Invoice</th>
                                            <th class="col-2">Invoice</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                        <tr>
                                            <td> {{ $loop->iteration }} </td>
                                            <td> N{{ number_format( $payment->due ?? 0, 2) }}</td>
                                            <td> {{ $payment->created_at }} </td>
                                            <td> 
                                                @if($payment->status === 0)
                                                    <button class="btn btn-sm btn-secondary" disabled>Request sent</button>
                                                @elseif($payment->status == 1)
                                                    <button class="btn btn-sm" style="color:#fff; background-color:#28a745; border-color:#28a745;" disabled>Approved</button>
                                                @else
                                                    {{ $payment->status }}
                                                @endif

                                            </td>
                                            <td>
                                                @php
                                                $totalinvoice = App\Agentpaymentdetail::where('paymentId', $payment->id)->count();
                                                @endphp
                                                {{ $totalinvoice }}
                                            </td>
                                            <td>
                                                @if($payment->status === 1)
                                                    <a href="/agent/payment/invoice-details/{{$payment->id}}" target="_blank">
                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></button>
                                                    </a>
                                                @endif
                                            </td>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
    </div>
        
@endsection




 