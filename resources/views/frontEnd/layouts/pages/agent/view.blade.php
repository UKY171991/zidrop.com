@extends('frontEnd.layouts.pages.agent.agentmaster')
@section('title', 'Dashboard')
@section('content')
    <div class="profile-edit mrt-30">
        <div class="row">
            <div class="col-sm-4">
                <div class="supplier-profile">
                    <div class="company-name">
                        <h2>Contact Info</h2>
                    </div>
                    <div class="supplier-info">
                        <table class="table table-bordered table-responsive-sm">
                            <tr>
                                <td>Name</td>
                                <td>{{ $agentInfo->name }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $agentInfo->phone }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $agentInfo->email }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="supplier-profile">
                    <div class="invoice slogo-area">
                        <div class="supplier-logo">
                            <img src="{{ asset($agentInfo->image) }}" style="200px;width:200px">
                        </div>
                    </div>
                    <div class="supplier-info">

                        <div class="supplier-basic">
                            <h5>{{ $agentInfo->name }}</h5>
                            <p>Member Since : {{ date('M-d-Y', strtotime($agentInfo->created_at)) }}</p>
                            <p>Member Status : {{ $agentInfo->status == 1 ? 'Active' : 'Inactive' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="supplier-profile">
                    <div class="purchase">
                        <h2>Account Info</h2>
                    </div>
                    <div class="supplier-info">
                        <table class="table table-bordered table-responsive-sm">
                            <tr>
                                <td>Total Invoice</td>
                                <td>{{ $parcels->count() }}</td>
                            </tr>
                            <tr>
                                <td>Total Amount</td>
                                <td>{{ $totalamount }}</td>
                            </tr>
                            <tr>
                                <td>Current Due</td>
                                <td>{{ $unpaidamount }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->
    </div>
    <!-- Modal -->
@endsection
