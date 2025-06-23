@extends('backEnd.layouts.master')
@section('title','Deliveryman Profile')
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
                        <div class="body-titleer">
                            <div class="row">
                                <div class="col-sm-6"><h5>{{$agentInfo->name}}</h5></div>
                                <div class="col-sm-6 text-right"><button class="btn btn-primary" title="Action" data-toggle="modal" data-target="#fullprofile"><i class="fa fa-eye"></i> Full Profile</button></div>
                            </div>
                        </div>
                       </div>
                    </div>
                </div>
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
										<td>{{$agentInfo->name}}</td>
									</tr>
									<tr>
										<td>Phone</td>
										<td>{{$agentInfo->phone}}</td>
									</tr>
									<tr>
										<td>Email</td>
										<td>{{$agentInfo->email}}</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					
					<div class="col-sm-4">
						<div class="supplier-profile">
							<div class="invoice slogo-area">
								<div class="supplier-logo">
									<img src="{{asset($agentInfo->image)}}" alt="">
								</div>
							</div>
							<div class="supplier-info">
								
								<div class="supplier-basic">
									<h5>{{$agentInfo->name}}</h5>
									<p>Member Since : {{date('M-d-Y', strtotime($agentInfo->created_at))}}</p>
									<p>Member Status : {{$agentInfo->status==1? 'Active':'Inactive'}}</p>
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
										<td>{{$parcels->count()}}</td>
									</tr>
									<tr>
										<td>Total Amount</td>
										<td>{{$totalamount}}</td>
									</tr>
									<tr>
										<td>Current Due</td>
										<td>{{$unpaidamount}}</td>
									</tr>                  
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h3 class="mt-3">Scan Parcel</h3>
						<form action="{{url('author/parcel/deliveryman/asign')}}" class="form-row" method="POST">
							@csrf
							<div class="col-sm-12">
								<div class="form-group">
									<input type="text" placeholder="Keep your cursor and scan parcel" name="trackingCode" value="" class="form-control">
								</div>
							</div>
							<input type="hidden" value="{{$agentInfo->id}}" name="deliverymanId">
						</form>
					</div>
				</div>
				<div class="row">
					<div class="card-body">
                	<form action="{{url('admin/agent-payment/bulk-option')}}" method="POST" id="myform">
						<input type="hidden" value="{{$agentInfo->id}}" name="agentId">
                	@csrf
                    <table id="example" class="table table-bordered  table-striped custom-table table-responsive">
                    	@if(Auth::user()->role_id <= 2)
		                <select name="selectptions" class="bulkselect" form="myform" required="required">
		                  <option value="">Select..</option>
		                  <option value="0">Processing</option>
		                  <option value="1">Paid</option>
		                </select>
		                <button type="submit" class="bulkbutton" onclick="return confirm('Are you want change this?')">Apply</button>
		                @endif
                      <thead>
                      <tr>
                        <th><input type="checkbox"  id="My-Button"></th>
                        <th>SL</th>
                        <th>Id</th>
                        <th>Date</th>
                        <th>COD</th>
                        <th>L. Update</th>
                        <th>Subtotal</th>
                        <th>Charge</th>
                        <th>Payment</th>
                        <th>Pay Status</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($parcels as $key=>$value)
                        <tr>
                            <td><input type="checkbox"  value="{{$value->id}}" name="parcel_id[]" form="myform"></td>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$value->trackingCode}}</td>
                          <td> {{date('F d Y', strtotime($value->created_at))}} {{date('H:i:s:A', strtotime($value->created_at))}}</td>
                          <td>{{$value->cod}}</td>
                          <td>{{date('F d, Y', strtotime($value->updated_at))}}</td>
                          <td>{{$value->merchantAmount}}</td>
                          <td>{{$value->codCharge+$value->deliveryCharge}}</td>
                          <td>{{$value->agentAmount}}</td>
                           <td>@if($value->agentPaystatus==NULL) # @elseif($value->agentPaystatus==0) Processing @else Paid @endif</td>
                           
                          <td>@php $parceltype = App\Parceltype::find($value->status); @endphp @if($parceltype!=NULL) {{$parceltype->title}} @endif</td>
                          <td>
                              <ul class="action_buttons">
                                  <li>
                                      <a class="edit_icon" href="#"  data-toggle="modal" data-target="#agentPanel{{$value->id}}" title="View"><i class="fa fa-eye"></i></a>
                                      <div id="agentPanel{{$value->id}}" class="modal fade" role="dialog">
			                            <div class="modal-dialog">
			                              <!-- Modal content-->
			                              <div class="modal-content">
			                                <div class="modal-header">
			                                  <h5 class="modal-title">Parcel Details</h5>
			                                </div>
			                                <div class="modal-body">
			                                  <table class="table table-bordered table-responsive-sm">
			                                  	<tr>
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
			                                  		<td>Last Update</td>
			                                  		<td>{{$value->updated_at}}</td>
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
                      </tbody>
                    </table>
                  </div>
				</div>
            </div>
          </div>
        </div>
    </div>
        <div id="fullprofile" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Agent Profile</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <table class="table table-bordered table-responsive table-striped">
                  <tbody>
                      <tr>
                      <td>Name</td>
                      
                      <td>{{$agentInfo->name}}</td>
                  </tr>
                  <tr>
                      <td>Phone Number</td>
                      <td>{{$agentInfo->phone}}</td>
                  </tr>
                  <tr>
                      <td>Email</td>
                      <td>{{$agentInfo->email}}</td>
                  </tr>
                  <tr>
                      <td>Commision</td>
                      <td> {{ $agentInfo->commision }} </td>
                  </tr>
                  <tr>
                    <td>Commision Type</td>
                    <td>
                       @if($agentInfo->commision==1) Flat @else Percent @endif 
                     </td>
                  </tr>
                  </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal end -->
  </section>
@endsection