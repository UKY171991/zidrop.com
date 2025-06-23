@extends('backEnd.layouts.master')
@section('title', 'Manage Merchant')
@section('content')
<style>
    .action_buttons button,.action_buttons a {
	    width: 200px;
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
                                        <h4><b>
                                                <p style="color:green">Manage Merchant</P>
                                            </b>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="merchantexample" class="table table-bordered table-striped custom-table table-responsibe">
                                    <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Merchant Id</th>
                                            <th>Name</th>
                                            <th>Company Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Register Date</th>
                                            <th>Time</th>
                                            <th>Wallet Uses</th>
                                            <th>Available Wallet</th>
                                            <th>Total Due</th>
                                            <th>UnPaid Delivered Amt</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($merchants as $key => $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->firstName }} {{ $value->lastName }}</td>
                                                <td>{{ $value->companyName }}</td>

                                                <td>{{ $value->phoneNumber }}</td>
                                                <td>{{ $value->emailAddress }}</td>
                                                <td>{{ date('M-d-Y', strtotime($value->created_at)) }}</td>
                                                <td>{{ date('g:i a', strtotime($value->created_at)) }}</td>
                                                @php
                                                    $use = App\RemainTopup::where('merchant_id', $value->id)->sum(
                                                        'amount',
                                                    );
                                                @endphp
                                                <td>{{ $use }}</td>
                                                <td>{{ $value->balance }}</td>
                                                <td>
                                                    @php
                                                        $totaldue = DB::table('parcels')
                                                            ->join(
                                                                'merchants',
                                                                'merchants.id',
                                                                '=',
                                                                'parcels.merchantId',
                                                            )
                                                            ->where('parcels.merchantId', $value->id)
                                                            ->sum('parcels.merchantDue');

                                                        $totalcod = DB::table('parcels')
                                                            ->join(
                                                                'merchants',
                                                                'merchants.id',
                                                                '=',
                                                                'parcels.merchantId',
                                                            )
                                                            ->where('parcels.merchantId', $value->id)
                                                            ->where('parcels.merchantpayStatus', null)
                                                            ->where('parcels.status', 4)
                                                            ->sum('parcels.merchantDue');

                                                    @endphp
                                                    {{ $totaldue }}
                                                </td>

                                                <td>
                                                    {{ $totalcod }}

                                                </td>
                                                <td>{{ $value->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <ul class="action_buttons dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                                            data-toggle="dropdown">Action Button
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu">
                                                            @if (Auth::user()->role_id != 3)
                                                                <li>
                                                                    @if ($value->status == 1)
                                                                        <form
                                                                            action="{{ url('author/merchant/inactive') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="hidden_id"
                                                                                value="{{ $value->id }}">
                                                                            <button type="submit" class="thumbs_up"
                                                                                title="unpublished"><i
                                                                                    class="fa fa-thumbs-up"></i>
                                                                                Inactive</button>
                                                                        </form>
                                                                    @else
                                                                        <form action="{{ url('author/merchant/active') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="hidden_id"
                                                                                value="{{ $value->id }}">
                                                                            <button type="submit" class="thumbs_down"
                                                                                title="published"><i
                                                                                    class="fa fa-thumbs-down"></i>
                                                                                Active</button>
                                                                        </form>
                                                                    @endif
                                                                </li>
                                                                <li>
                                                                    <a class="thumbs_up"
                                                                        href="{{ url('author/merchant/edit/' . $value->id) }}"
                                                                        title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                                                </li>
                                                            @endif
                                                            @if (Auth::user()->role_id <= 4)
                                                                <li>
                                                                    <a class="edit_icon"
                                                                        href="{{ url('author/merchant/view/' . $value->id) }}"
                                                                        title="View & Payment"><i class="fa fa-eye"></i>
                                                                        View & Payment</a>
                                                                </li>
                                                            @endif
                                                            @if (Auth::user()->role_id == 1)
                                                                <li>
                                                                    <form
                                                                        action="{{ url('/author/merchant/delete/' . $value->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button class="btn btn-danger" type="submit"
                                                                            onclick="return confirm('Are you sure want to delete this item?')"><i class="fa fa-trash"></i> Delete</button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            @if (Auth::user()->role_id != 3)
                                                                <li>
                                                                    <a class="edit_icon"
                                                                        href="{{ url('author/merchant/payment/invoice/' . $value->id) }}"
                                                                        title="Payment History"><i class="fa fa-list"></i>
                                                                        Payment History</a>
                                                                </li>
                                                            @endif

                                                            <li>
                                                                <a class="edit_icon"
                                                                    href="{{ url('author/merchant/return-payment/invoice/' . $value->id) }}"
                                                                    title="Return Payment History"><i
                                                                        class="fa fa-list"></i>
                                                                    Return Payment History</a>
                                                            </li>
                                                            <li>
                                                                <a class="edit_icon"
                                                                    href="{{ url('editor/merchant/subscription_history/' . $value->id) }}"
                                                                    title="Return Payment History"><i
                                                                        class="fa fa-list"></i>
                                                                    Subscription History</a>
                                                            </li>

                                                        </ul>
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal Section  -->
    <script>
        $(document).ready(function() {
            $('#merchantexample').DataTable({
                dom: 'Bfrtip',
                "lengthMenu": [
                    [200, 500, -1],
                    [200, 500, "All"]
                ],
                scrollX: true,
                buttons: [{
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'Csv',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },

                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
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
            });
            
            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
