@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title', 'Payments')
@section('content')
    <div class="profile-edit mrt-30">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 style="margin-bottom: 10px;">Payments</h4>
                    </div>
                    <div class="col-sm-12">
                        <div class="payments-inner table-responsive-sm">
                            <table class="table  table-striped" id="Paymentinvoice">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Total Invoice</th>
                                    <th>Total Payment</th>
                                    <th>More</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($merchantInvoice as $key => $value)
                                
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $value->updated_at }}</td>
                                        <td>{{ $value->total_parcel }}</td>
                                          <td>{{ number_format($value->total, 2) }}N</td>
                                        <td>
                                            <form action="{{ url('merchant/payment/invoice-details') }}" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $value->updated_at }}" name="update">
                                            <input type="hidden" value="{{ $value->merchantId }}" name="merchant_id">
                                                <button class="btn btn-primary" type="submit"><i class="fa fa-eye"></i> View</button>
                                            </form>
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




    <script>
        $(document).ready(function() {
            $('#Paymentinvoice').DataTable( {
                dom: 'Bfrtip',
                "lengthMenu": [[ 10, 20, 50, -1], [ 10, 20, 50, "All"]],
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'Csv',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [  0,1, 2, 3]
                        }
                    },

                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: [  0,1, 2, 3]
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
            } );

        });
    </script>

@endsection
