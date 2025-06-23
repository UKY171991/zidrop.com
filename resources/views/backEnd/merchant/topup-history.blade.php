@extends('backEnd.layouts.master')
@section('title', 'Topup History')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card">
                            <div class="col-sm-12">
                                <div class="manage-button">
                                    <div class="body-title two-side-btn">
                                        <h4>
                                            <b>
                                                <p style="color:green">Top up Management and History</P>
                                            </b>
                                        </h4>

                                        <div class="tools">
                                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#manualSubtractTopupModal">Subtract Balance</button>
                                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#manualAddTopupModal">Add Balance</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('author.topup.add-manual-balance') }}" method="post">
                                @csrf
                                <div id="manualAddTopupModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Add Balance</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Merchant</label>
                                                            <select name="merchant" class="form-control" required onchange="changeMerchant(this, '#manualAddTopupModal')">
                                                                <option value="">Select Merchant</option>
                                                                @if(!empty($merchants))
                                                                    @foreach($merchants as $merchant)
                                                                        <option value="{{ $merchant->id }}" data-email="{{ $merchant->emailAddress }}" data-mobile="{{ $merchant->phoneNumber }}">{{ $merchant->companyName }} ({{ $merchant->firstName . ' ' . $merchant->lastName }})</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Reference</label>
                                                            <input type="text" class="form-control" name="reference" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Mobile</label>
                                                            <input type="text" class="form-control" name="mobile" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Amount</label>
                                                            <input type="text" step="any" class="form-control CommaSeperateValueSet" name="amount" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('author.topup.subtract-manual-balance') }}" method="post">
                                @csrf
                                <div id="manualSubtractTopupModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Subtract Balance</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Merchant</label>
                                                            <select name="merchant" class="form-control" required>
                                                                <option value="">Select Merchant</option>
                                                                @if(!empty($merchants))
                                                                    @foreach($merchants as $merchant)
                                                                        <option value="{{ $merchant->id }}" data-email="{{ $merchant->emailAddress }}" data-mobile="{{ $merchant->phoneNumber }}">{{ $merchant->companyName }} ({{ $merchant->firstName . ' ' . $merchant->lastName }})</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Reference</label>
                                                            <input type="text" class="form-control" name="reference" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Amount</label>
                                                            <input type="text" step="any" class="form-control CommaSeperateValueSet" name="amount" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="merchantexample" class="table table-bordered table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th>Serial</th>
                                                <th>Merchant Name</th>
                                                <th>Merchant Company</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Amount</th>
                                                <th>Transaction Reference</th>
                                                <th>Transaction Channel</th>
                                                <th>Currency</th>
                                                <th>created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topup as $key => $value)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $value->merchant->firstName ?? 'Merchant Deleted' }}</td>
                                                    <td>{{ $value->merchant->companyName ?? 'Merchant Deleted' }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->mobile }}</td>
                                                    <td>{{ $value->amount }}</td>
                                                    <td>{{ $value->reference }}</td>
                                                    <td>{{ $value->channel }}</td>
                                                    <td>{{ $value->currency }}</td>
                                                    <td>{{ $value->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        {{ $topup->links() }}
                                    </table>
                                </div>

                                <hr>

                                <div class="table-responsive">
                                    <h5>Used Wallet History</h5>
                                    <table id="usedWalletHistoryTable" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>##</th>
                                            <th>Merchant</th>
                                            <th>Parcel Recepient Info</th>
                                            <th>Transaction Amount</th>
                                            <th>Created_at</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($usedtopup as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    @if($item->merchant)
                                                        {{ $item->merchant->companyName }}
                                                    ({{ $item->merchant->firstName . ' ' . $item->merchant->lastName }})
                                                        <br>
                                                    @else
                                                        <b style="color: red">Merchant Deleted by Admin.</b>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->type == 'parcel')
                                                        @if($item->parcel)
                                                            {{ $item->parcel->recipientName }} <br>
                                                            {{ $item->parcel->parceltype->title }}
                                                        @else
                                                            <b style="color: red">Parcel Deleted by Admin.</b>
                                                        @endif
                                                    @else
                                                        <b style="color: red">Manual by Admin. ({{ $item->reference }})</b>
                                                    @endif
                                                </td>
                                                <td>N{{ number_format($item->amount, 2) }}</td>
                                                <td>{{ $item->created_at }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js_scripts')
    <script>
        $(document).ready(function() {
            $('#merchantexample').DataTable(  );
            $('#usedWalletHistoryTable').DataTable(  );
        });
        function changeMerchant(select,parentElement) {
            let email = $('option:selected', select).attr('data-email');
            let mobile = $('option:selected', select).attr('data-mobile');
            $(parentElement + ' input[name=email]').val(email);
            $(parentElement + ' input[name=mobile]').val(mobile);
        }
    </script>
@endsection