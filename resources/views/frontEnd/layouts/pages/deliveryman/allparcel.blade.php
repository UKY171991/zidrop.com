@extends('frontEnd.layouts.pages.deliveryman.master')
@section('title','Dashboard')
@section('content')
<div class="profile-edit mrt-30">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <form action="" class="filte-form">
            @csrf
            <div class="row">
              <input type="hidden" value="1" name="filter_id">
              <div class="col-sm-2">
                <input type="text" class="form-control" placeholder="Track Id" name="trackId">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="number" class="form-control" placeholder="Phone Number" name="phoneNumber">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <button type="submit" class="btn btn-success">Submit </button>
              </div>
              <!-- col end -->
            </div>
          </form>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">
               <table id="example" class="table  table-striped">
                <thead>
                  <tr>
                   <th>SL ID</th>
                   <th>Tracking ID</th>
                   <th>Date</th>
                   <th>Shop Name</th>
                   <th>Phone </th>
                   <th>Status</th>
                   
                   <th>Sub Total</th>
                   <th>Charge</th>
                   
                   <th>Total</th>
                   <th>L. Update</th>
                   <th>Payment Status</th>
                   <th>Note</th>
                   <th>More</th>
                 </tr>
                </thead>
                <tbody>
                @foreach($allparcel as $key=>$value)
                 <tr>
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
                   
                   <td> {{$value->cod-($value->deliveryCharge+$value->codCharge)}}</td>
                   <td> {{$value->cod}}</td>
                   
                   <td> {{$value->deliveryCharge+$value->codCharge}}</td>
                    <td>{{date('F d, Y', strtotime($value->updated_at))}}</td>
                   <td>@if($value->merchantpayStatus==NULL) NULL @elseif($value->merchantpayStatus==0) Processing @else Paid @endif</td>
                   <td>
                    @php 
                        $parcelnote = App\Parcelnote::where('parcelId',$value->id)->orderBy('id','DESC')->first();
                    @endphp
                    @if(!empty($parcelnote))
                    {{$parcelnote->note}}
                    @endif
                 </td>
                   <td> <button class="btn btn-info" href="#"  data-toggle="modal" data-target="#merchantParcel{{$value->id}}" title="View"><i class="fa fa-eye"></i></button>
                      <div id="merchantParcel{{$value->id}}" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Parcel Details</h5>
                          </div>
                          <div class="modal-body">
                            <table class="table table-bordered">
                              <tr>
                                <td>Merchant Name</td>
                                <td>{{$value->firstName}} {{$value->lastName}}</td>
                              </tr>
                              <tr>
                                <td>Merchant Phone</td>
                                <td>{{$value->phoneNumber}}</td>
                              </tr>
                              <tr>
                                <td>Merchant Email</td>
                                <td>{{$value->emailAddress}}</td>
                              </tr>
                              <tr>
                                <td>Company</td>
                                <td>{{$value->companyName}}</td>
                              </tr>
                                <td>Recipient Name</td>
                                <td>{{$value->recipientName}}</td>
                              </tr>
                              <tr>
                                <td>Recipient Address</td>
                                <td>{{$value->recipientAddress}}</td>
                              </tr>
                              <tr>
                                <td>COD</td>
                                <td>{{$value->cod}}</td>
                              </tr>
                              <tr>
                                <td>C. Charge</td>
                                <td>{{$value->codCharge}}</td>
                              </tr>
                              <tr>
                                <td>D. Charge</td>
                                <td>{{$value->deliveryCharge}}</td>
                              </tr>
                              <tr>
                                <td>Sub Total</td>
                                <td>{{$value->merchantAmount}}</td>
                              </tr>
                              <tr>
                                <td>Paid</td>
                                <td>{{$value->merchantPaid}}</td>
                              </tr>
                              <tr>
                                <td>Due</td>
                                <td>{{$value->merchantDue}}</td>
                              </tr>
                            </table>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Modal end -->
                    <button class="btn btn-danger" title="Action" data-toggle="modal" data-target="#sUpdateModal{{$value->id}}"><i class="fa fa-sync-alt"></i></button> 
                    <a class="btn btn-primary" a href="{{url('deliveryman/parcel/invoice/'.$value->id)}}"  title="Invoice"><i class="fas fa-list"></i></a><!-- Modal -->
                      <div id="sUpdateModal{{$value->id}}" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Parcel Status Update</h5>
                            </div>
                            <div class="modal-body">
                              <form action="{{url('deliveryman/parcel/status-update')}}" method="POST">
                                @csrf
                                <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                <input type="hidden" name="customer_phone" value="{{$value->recipientPhone}}">
                                <div class="form-group">
                                    <select name="status"  onchange="percelDelivery(this)" class="form-control" id="">
                                         @foreach($parceltypes as $key=>$ptvalue)
                                         @if($key == 1 || $key == 3 || $key == 4 || $key == 5 || $key == 6 || $key == 7 || $key == 8) 
                                          <option value="{{$ptvalue->id}}"@if($value->status==$ptvalue->id) selected="selected" @endif  @if($value->status > $ptvalue->id) disabled @endif>{{$ptvalue->title}}</option>
                                          @endif
                                          @endforeach
                                  </select>
                                </div>                                    
                                <!-- form group end -->
                                 <div class="form-group mrt-15">
                                  <select name="note" class="form-control" id="">
                                    <option value="">Select</option>
                                     @foreach($allnotelist as $key=>$notelist)
                                    <option value="{{$notelist->title}}">{{$notelist->title}}</option>
                                    @endforeach
                                  </select>
                                </div>
                                <style>
                                .partialpayment{
                                  display: none;
                                }  
                                </style>   
                                 <!-- form group end -->
                                 <div class="form-group">
                                  <div id="customerpaid" class="partialpayment">
                                      <input type="text" class=" form-control" value="{{old('customerpay')}}" id="customerpay" name="partial_payment"  placeholder="customer pay" /><br />
                                  </div>
                                </div>
                                <!-- form group end -->
                                <div class="form-group">
                                  <button class="btn btn-success">Update</button>
                                </div>
                                <!-- form group end -->
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Modal end -->
                      <!-- @if($value->status >= 2)-->
                      <!--<a class="btn btn-primary" a href="{{url('deliveryman/parcel/invoice/'.$value->id)}}"  title="Invoice"><i class="fas fa-list"></i></a>-->
                      <!--  @endif-->
                  </td>
                 </tr>
                 @endforeach
                </tbody>
               </table>
             </div>
        </div>
    </div>
    <!-- row end -->
</div>
@endsection