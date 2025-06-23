@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title', 'Topup')
@section('content')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <section class="section-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row addpercel-inner">
                        <div class="col-sm-12">
                            <div class="addpercel-top">
                                <h3>Top up</h3>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="fraud-search">
                                <form id="paymentForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    value="{{ $merchant->companyName }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                    id="email" placeholder="Customer Email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                                    id="mobile" placeholder="Mobile" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control CommaSeperateValueSet {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                    id="amount" placeholder="Amount" required>
                                            </div>
                                        </div>
                                        <button type="submit" onclick="payWithPaystack(event)">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
            </div>
        </div>
    </section>

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="tab-inner">
            <h5>Wallet History</h5>
            <table id="walletHistoryTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Transaction Date</th>
                        <th>Mobile Number</th>
                        <th>Transaction Amount</th>
                        <th>Payment Reference</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topup as $key => $item)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->mobile }}</td>
                            <td>N{{ number_format($item->amount, 2) }}</td>
                            <td>{{ $item->reference }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="tab-inner">
            <h5>Used Wallet History</h5>
            <table id="usedWalletHistoryTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>##</th>
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
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        const paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener("submit", payWithPaystack, false);


        function payWithPaystack(e) {
            e.preventDefault();
            var AmountVal = parseFloat(convertCommaSeparatedToNumber($("#amount").val()));
            let handler = PaystackPop.setup({
                key:'<?= $results->public; ?>',
                //key: 'pk_live_28c1a12799b3241d151449ceb00cd5661beec03e',
                // key: 'pk_test_e0681589da7d4b5c05d4a4f6f736600ae01d0362',
                email: document.getElementById("email").value,
                phone: document.getElementById("mobile").value,
                amount: AmountVal * 100,
                ref: 'Zi_' + Math.floor(Math.random() * 9999) + '_' + Math.floor(Math.random() * 99999999) + '_' +
                    Math.floor(Math.random() * 99999),

                onClose: function() {
                    alert('Window closed.');
                },
                callback: function(response) {
                    let reference = response.reference;
                    $.ajax({
                        type: "GET",
                        url: "{{ URL::to('merchant/get/verify-payment/') }}/" + reference,
                        success: function(response) {
                            console.log(response[0])
                            if (response[0].status === true) {
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    }
                                });
                                $.ajax({
                                    type: "post",
                                    url: "{{ URL::to('merchant/get/store-payment') }}",
                                    data: {
                                        email: response[0].data.customer.email,
                                        reference: response[0].data.reference,
                                        amount: response[0].data.amount,
                                        status: response[0].data.status,
                                        channel: response[0].data.channel,
                                        currency: response[0].data.currency,
                                        mobile: response[0].data.customer.phone,
                                    },
                                    success: function(res) {
                                        if (res.status == true) {

                                            $('tbody').prepend(`
                                                <tr>
                                                    <td>##</td>
                                                    <td>${res.top.created_at} </td>
                                                    <td>${res.top.mobile} </td>   
                                                    <td>N${res.top.amount.toFixed(2)} </td>   
                                                    <td>${res.top.reference} </td>   
                                                    <td>${res.top.status} </td>   
                                                </tr>
                                            `);

                                            const Toast = Swal.mixin({
                                                toast: true,
                                                position: 'top-end',
                                                showConfirmButton: false,
                                                timer: 3000,
                                                timerProgressBar: true,
                                                didOpen: (toast) => {
                                                    toast.addEventListener(
                                                        'mouseenter',
                                                        Swal.stopTimer)
                                                    toast.addEventListener(
                                                        'mouseleave',
                                                        Swal.resumeTimer
                                                    )
                                                }
                                            })


                                            document.getElementById("email").value = '';
                                            document.getElementById("amount")
                                                .value = '';
                                            document.getElementById("mobile")
                                                .value = '';

                                            Toast.fire({
                                                icon: 'success',
                                                title: 'Your transaction completed successfully!!'
                                            })


                                        } else {
                                            const Toast = Swal.mixin({
                                                toast: true,
                                                position: 'top-end',
                                                showConfirmButton: false,
                                                timer: 3000,
                                                timerProgressBar: true,
                                                didOpen: (toast) => {
                                                    toast.addEventListener(
                                                        'mouseenter',
                                                        Swal.stopTimer)
                                                    toast.addEventListener(
                                                        'mouseleave',
                                                        Swal.resumeTimer
                                                    )
                                                }
                                            })

                                            Toast.fire({
                                                icon: 'error',
                                                title: 'Something went wrong! Please contact with service provider.'
                                            })
                                        }
                                    }
                                });
                            }
                        }
                    });
                },

            });

            handler.openIframe();
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#walletHistoryTable').DataTable(  );
            $('#usedWalletHistoryTable').DataTable(  );
        });
    </script>
@endsection
