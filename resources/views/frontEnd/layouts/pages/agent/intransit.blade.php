@extends('frontEnd.layouts.pages.agent.agentmaster')

@section('title','Dashboard')

@section('content')
    <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">
                                <button class="btn btn-success">Receive Parcel</button>
                            </p>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox"  id="My-Button">
                                    </th>
                                    <th>Sl ID</th>
                                    <th>Tracking ID</th>
                                    <th>Date</th>
                                    <th>Shop Name</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Charge</th>
                                    <th>Sub Total</th>
                                    <th>L. Update</th>
                                    <th>Payment Status</th>
                                    <th>Note</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($allparcel as $key=>$value)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="selectItemCheckbox" value="{{$value->id}}" name="parcel_id[]" form="myform">
                                            </td>

                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$value->trackingCode}}</td>
                                            <td>{{$value->created_at}}</td>
                                            <td>{{$value->companyName}}</td>
                                            <td>{{$value->recipientPhone}}</td>
                                            <td>
                                                @php
                                                    $parcelstatus = App\Parceltype::find($value->status);
                                                @endphp
                                                {{$parcelstatus->title}}
                                            </td>
                                            <td>{{$value->cod}}</td>
                                            <td>{{$value->deliveryCharge+$value->codCharge}}</td>
                                            <td>{{$value->cod-($value->deliveryCharge+$value->codCharge)}}</td>
                                            <td>{{date('F d, Y', strtotime($value->updated_at))}}</td>
                                            <td>
                                                @if($value->merchantpayStatus==NULL) NULL @elseif($value->merchantpayStatus==0) Processing @else Paid @endif
                                            </td>
                                            <td>
                                                @php
                                                    $parcelnote = App\Parcelnote::where('parcelId',$value->id)->orderBy('id','DESC')->first();
                                                @endphp
                                                @if(!empty($parcelnote))
                                                    {{$parcelnote->note}}
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
@endsection