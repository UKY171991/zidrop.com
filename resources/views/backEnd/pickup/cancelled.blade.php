@extends('backEnd.layouts.master')
@section('title','Cancelled Pickup')
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
                        <div class="body-title">
                          <h5>Cancelled Pickup</h5>
                        </div>
                      </div>
                    </div>
                  <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Merchant</th>
                        <th>Merchant Phone</th>
                        <th>Pickup Address</th>
                        <th>Time</th>
                        <th>Agent Asign</th>
                        <th>Pickup Man Asign</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($show_data as $key=>$value)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          @php
                            $merchant = App\Merchant::find($value->merchantId);
                            $agentInfo = App\Agent::find($value->agent);
                            $deliverymanInfo = App\Deliveryman::find($value->deliveryman);
                          @endphp
                          <td>{{$merchant->firstName??''}} {{$merchant->lastName??''}}</td>
                          <td>{{$merchant->phoneNumber??''}}</td>
                          <td>{{$value->pickupAddress}}</td>
                          
                          <td>{{date("g:i a", strtotime($value->created_at))}}, {{date('d M Y', strtotime($value->created_at))}}</td>
                          <td>@if($value->agent!=0) {{$agentInfo->name}} @else <button class="btn btn-primary" data-toggle="modal" data-target="#asignModal{{$value->id}}">Asign</button> @endif</td>
                          <td>@if($value->deliveryman) {{$deliverymanInfo->name}} @else <button class="btn btn-primary" data-toggle="modal" data-target="#riderasignModal{{$value->id}}">Asign</button> @endif</td>
                          <td>@if($value->status==0)Not Assigned @elseif($value->status==1) Pending @elseif($value->status==2) Accepted @elseif($value->status==3)Cancelled @endif</td>
                          <td>
                              <!-- Modal -->
                          <div id="riderasignModal{{$value->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Deliveryman Asign</h5>
                                </div>
                                <div class="modal-body">
                                  <form action="{{url('editor/pickup/deliveryman/asign')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                     <input type="hidden" name="merchant_phone"  value="{{$merchant->phoneNumber??''}}">
                                    <div class="form-group">
                                      <select name="deliveryman" class="form-control" id="">
                                        @foreach($deliverymen as $key=>$deliveryman)
                                          <option value="{{$deliveryman->id}}">{{$deliveryman->name}}</option>
                                        @endforeach
                                      </select>
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
                          <!-- Modal -->
                          <div id="asignModal{{$value->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Agent Asign</h5>
                                </div>
                                <div class="modal-body">
                                  <form action="{{url('editor/pickup/agent/asign')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <input type="hidden" name="merchant_phone"  value="{{$merchant->phoneNumber??''}}">
                                    <div class="form-group">
                                      <select name="agent" class="form-control" id="">
                                        @foreach($agents as $key=>$agent)
                                          <option value="{{$agent->id}}" name="agent">{{$agent->name}}</option>
                                        @endforeach
                                      </select>
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
                            <ul class="action_buttons">
                                  <li>
                                      <button class="thumbs_up" title="Action" data-toggle="modal" data-target="#sUpdateModal{{$value->id}}"><i class="fa fa-sync-alt"></i></button>
                                      <!-- Modal -->
                                      <div id="sUpdateModal{{$value->id}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                          <!-- Modal content-->
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title">Pickup Status Update</h5>
                                            </div>
                                            <div class="modal-body">
                                              @if($value->deliveryman!=0)
                                              <form action="{{url('editor/pickup/status-update')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                                <input type="hidden" name="merchant_phone"  value="{{$merchant->phoneNumber??''}}">
                                                <div class="form-group">
                                                    <select name="status"  class="form-control" id="" dissable="dissable">
                                                        <option value="1"@if($value->status==1) selected="selected" @endif>Pending</option>
                                                        <option value="2"@if($value->status==2) selected="selected" @endif>Accepted</option>
                                                        <option value="3"@if($value->status==3) selected="selected" @endif>Cancelled</option>
                                                  </select>
                                                </div>                                    
                                                <!-- form group end -->
                                                <div class="form-group">
                                                  <button class="btn btn-success">Update</button>
                                                </div>
                                                <!-- form group end -->
                                              </form>
                                              @else
                                              <h4>Please first assign a deliveryman</h4>
                                              @endif
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <!-- Modal end -->
                                  </li>
<li>
                                      <button class="edit_icon" href="#"  data-toggle="modal" data-target="#merchantParcel{{$value->id}}" title="View"><i class="fa fa-eye"></i></button>
                                      <div id="merchantParcel{{$value->id}}" class="modal fade" role="dialog">
                                  <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title">Pickup Details</h5>
                                      </div>
                                      <div class="modal-body">
                                        <table class="table table-bordered">
                                          <tr>
                                            <td>Merchant Name</td>
                                            <td>{{$merchant->firstName??''}} {{$merchant->lastName??''}}</td>
                                          </tr>
                                          <tr>
                                            <td>Merchant Phone</td>
                                            <td>{{$merchant->phoneNumber??''}}</td>
                                          </tr>
                                          <tr>
                                            <td>Merchant Email</td>
                                            <td>{{$merchant->emailAddress??''}}</td>
                                          </tr>
                                          <tr>
                                            <td>Company</td>
                                            <td>{{$merchant->companyName??''}}</td>
                                          </tr>
                                          <tr>
                                            <td>Pickup Address</td>
                                            <td>{{$value->pickupAddress}}</td>
                                          </tr>
                                          <tr>
                                            <td>Pickup type</td>
                                            <td>@if($value->pickuptype==1) Next Day Delivery @elseif($value->pickuptype==2) Same Day Delivery @endif</td>
                                          </tr>
                                          <tr>
                                            <td>Note</td>
                                            <td>{{$value->note}}</td>
                                          </tr>
                                          <tr>
                                            <td>Estimed Parcel</td>
                                            <td>{{$value->estimedparcel}}</td>
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




@endsection