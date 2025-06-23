@extends('backEnd.layouts.master')
@section('title', 'Merchant Payment Details')
@section('content')
    <div class="profile-edit mrt-30">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <form action="{{ url('/editor/merchant/payment/submit-payment') }}" method="POST" id="myform" class="">
                    @csrf

                    <input type="hidden" name="merchant_id" value="{{ $merchant->id }}">
                    <button type="submit" class="btn btn-primary" style="float: left;">Confirm Payment</button>
                    <div class="tab-inner table-responsive">
                        <hr>
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="My-Button"></th>
                                    <th>Sl ID</th>
                                    <th>Tracking ID</th>
                                    <th>COD</th>
                                    <th>Due Bills</th>
                                    <th>Parcel Type</th>
                                    <th>COD Charge</th>
                                    <th>Delivery Charge</th>
                                    <th>Status</th>
                                    <th>Delivered Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($merchant->parcels as $key => $value)
                                    @if ($value->merchantpayStatus == 1)
                                        <tr>
                                            <td><input type="checkbox" value="{{ $value->id }}" name="parcel_id[]"
                                                    form="myform">
                                            </td>
                </form>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $value->trackingCode }}</td>
                <td>{{ $value->cod }}</td>
                <td>{{ $value->codCharge + $value->deliveryCharge - $value->cod }}</td>
                <td>{{ $value->parcelType == 1 ?'Regular':'Liquid' }}</td>
                <td>
                    {{ $value->codCharge }}
                </td>
                <td>
                    {{ $value->deliveryCharge }}
                </td>
                <td> {{ $value->PT->title }}</td>
                <td>{{ $value->created_at }}</td>

                </tr>
                @endif
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- row end -->
    </div>
    <!-- Modal -->

@endsection
