@extends('backEnd.layouts.master')
@section('title','Merchant Payment History')
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
                                <div class="col-sm-6"><h4>
                                    
                                        <a href="{{url('/author/merchant/manage')}}">   <div class="card p-2" style="width: -moz-fit-content;
                                        width: fit-content;">Back</div></a>                                    
                                    
                                    <b><p style="color:green">{{$merchantInfo->companyName}}( {{$merchantInfo->firstName}} {{$merchantInfo->lastName}} )</p></b></h4>
                                </div>
                                    

                                    
                                  

                                 
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
							  
								<table class="table table-bordered table-responsive">
								    
									<tr>
										<td>Name</td>
										<td>{{$merchantInfo->firstName}} {{$merchantInfo->lastName}}</td>
										
									</tr>
									<tr>
										<td>Phone</td>
										<td>{{$merchantInfo->phoneNumber}}</td>
									</tr>
									<tr>
										<td>Email</td>
										<td>{{$merchantInfo->emailAddress}}</td>
									</tr>
									<tr>
										<td>Pickup Address</td>
										<td>{{$merchantInfo->pickLocation}}</td>
									</tr>
									
									
								</table>
								
							</div>
							
						</div>
						
					</div>
					
					
					<div class="col-sm-4">
						<div class="supplier-profile">
							<div class="invoice slogo-area">
								<div class="supplier-logo">
									<img src="{{asset($merchantInfo->logo)}}" alt="">
								</div>
							</div>
							<div class="supplier-info">
								
								<div class="supplier-basic">
									<h5>{{$merchantInfo->companyName}}</h5>
									<p>Member Since : {{date('M-d-Y', strtotime($merchantInfo->created_at))}}</p>
									<p>Member Status : {{$merchantInfo->status==1? 'Active':'Inactive'}}</p>
								</div>
							</div>
						</div>
					</div>

@php
$totalamount = 0;
$totaldue = 0;
$totalcod=0;

foreach($parcels as $p){
    if($p->status == 4 || $p->status == 6 || $p->status == 10 ){
        $totalamount += $p->merchantAmount;
        $totaldue += $p->merchantDue;
        $totalcod += $p->cod;
    }
}
@endphp
					<div class="col-sm-4">
						<div class="supplier-profile">
							<div class="purchase">
								<h2>Account Info</h2>
                <p>DP = Delivered Parcels</p>
							</div>
							<div class="supplier-info">
								<table class="table table-bordered table-responsive">
									<tr>
										<td>Total Invoice</td>
										<td>{{$parcels->count()}}</td>
										
									</tr>
									<tr>
										<td>Total Amount (DP)</td>
										<td>{{$totalamount}}</td>
									</tr>
									<tr>
										<td>Current Due (DP)</td>
										<td>{{$totaldue}}</td>
									</tr>
									
									<tr>
									    
										<td>Total COD Amount (DP)</td>
										<td>{{$totalcod}}</td>
									</tr>
									
									<tr>
										<td>Payment Method</td>
										<td>
										    @if($merchantInfo->paymentMethod==1) Bkash @endif 
										    @if($merchantInfo->paymentMethod==2) Bank @endif 
										    @if($merchantInfo->paymentMethod==3) Cash @endif 
										    @if($merchantInfo->paymentMethod==4) Others @endif 
										 </td>
									</tr>
									<tr>
										<td>Payment Mode</td>
										<td>
										   {{ $merchantInfo->paymentmode }} 
										 </td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>

@if(Auth::user()->role_id != 3)
    <a href="{{url('author/merchant/payment/invoice/'.$merchantInfo->id)}}">   <div class="card p-2" style="width: -moz-fit-content;
  width: fit-content;">Payment History</div></a>
@endif  
  
				
				<div class="row">
					<div class="card-body">
					<form action="{{url('admin/merchant-payment/bulk-option')}}" method="POST" id="myform">
						<input type="hidden" value="{{$merchantInfo->id}}" name="merchantId">
						<input type="hidden" value="{{$merchantInfo->id}}" name="parcelId">
                	@csrf
                    <table id="merchantexample" class="table table-bordered table-striped custom-table">
         
                        
                    	@if(Auth::user()->role_id != 3)
                    	
		                <select name="selectptions" class="bulkselect" form="myform" required="required">
		                  <option value="">Select..</option>
		                  <option value="0">Processing</option>
		                  <option value="1">Paid</option>
		                </select>
		                <button type="submit" class="bulkbutton" onclick="return confirm('Are you want change this?')">Apply</button>

		                
		                @endif
                      <thead>
                          
                          
                      <tr>
                      	<th><id="My-Button"></id></th>
                        <th>SL</th>
                        <th>Tracking</th>
                        <th>Create Date</th>
                        <th>Time</th>
                         <th>___Last Update___</th>                       
                        <th>COD</th>

                        <th>Subtotal</th>

                        <th>Due Bills</th>
                        <th>COD Charge</th>
                        <th>Delivery Charge</th>
                         <th>Merchant Amt</th>                       
                        <th>Pay Status</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($parcels as $key=>$value)
                        <tr>
                            
                          <td>
                              
                              @if($value->merchantpayStatus==NULL || $value->merchantpayStatus==0) 
                              
                              <input type="checkbox"  value="{{$value->id}}" name="parcel_id[]" form="myform">
                              
                             @endif
                              
                              
                              
                              </td>
                          
                          <td>{{$loop->iteration}}</td>
                          <td>{{$value->trackingCode}}</td>
<!--                      <td> {{date('F d Y', strtotime($value->created_at))}} {{date('H:i:s:A', strtotime($value->created_at))}}</td>-->

                          <td>{{date('d M Y', strtotime($value->created_at))}}</td> <! create Date -->
                          <td>{{date("g:i a", strtotime($value->created_at))}}</td> <! create Time -->
                          <td>{{date('F d, Y', strtotime($value->updated_at))}}</td>  <! Last Update Date and time -->
                          
                          <td>{{$value->cod}}</td>
                          <td>{{$value->cod-($value->deliveryCharge+$value->codCharge)}}</td>

                          <td>{{$value->merchantDue}}</td>
                          <td>{{$value->codCharge}}</td>
                          <td>{{$value->deliveryCharge}}</td>
                          <td>{{$value->cod-($value->deliveryCharge+$value->codCharge)}}</td>
                          
                          <td>@if($value->merchantpayStatus==NULL) <div class ="text-danger"> NULL </div> @elseif ($value->merchantpayStatus==0) <div class ="text-warning"> Processing </div> @else <div class ="text-success"> Paid </div> @endif</td>
                          
                          <td>@php $parceltype = App\Parceltype::find($value->status); @endphp @if($parceltype!=NULL) {{$parceltype->title}} @endif</td>
                          <td>
                              <ul class="action_buttons">
                                 @if($value->status==3)
                                  <li>
                                      <a class="edit_icon anchor" a href="{{url('editor/parcel/invoice/'.$value->id)}}"  title="Invoice"><i class="fa fa-list"></i></a>
                                       <!-- Modal -->
                                  </li>
                                  @endif
                                  <li>
                                      <a class="edit_icon" href="#"  data-toggle="modal" data-target="#merchantParcel{{$value->id}}" title="View"><i class="fa fa-eye"></i></a>
                                      <div id="merchantParcel{{$value->id}}" class="modal fade" role="dialog">
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
			                                  		<td>{{$value->cod-($value->deliveryCharge+$value->codCharge)}}</td>
			                                  	</tr>
			                                  	<tr>
			                                  		<td>Paid</td>
			                                  		<td>{{$value->merchantPaid}}</td>
			                                  	</tr>
			                                  	<tr>
			                                  		<td>Due</td>
			                                  		<td>{{$value->merchantDue}}</td>
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
              <h4 class="modal-title"><b><p style="color:green">Merchant Profile</p></b></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <table class="table table-bordered table-responsive table-striped">
                  <tbody>
                      <tr>
                      <td>Name</td>
                      
                      <td>{{$merchantInfo->firstName}} {{$merchantInfo->lastName}}</td>
                  </tr>
                      <tr>
                      <td>User Name</td>
                      <td>{{$merchantInfo->username}}</td>
                  </tr>
                  <tr>
                      <td>Company</td>
                      <td>{{$merchantInfo->companyName}}</td>
                  </tr>
                  <tr>
                      <td>Phone Number</td>
                      <td>{{$merchantInfo->phoneNumber}}</td>
                  </tr>
                  <tr>
                      <td>Email</td>
                      <td>{{$merchantInfo->emailAddress}}</td>
                  </tr>
                  <tr>
                      <td>Pickup Location</td>
                      <td>{{$merchantInfo->pickLocation}}</td>
                  </tr>
                  <tr>
                      <td>Bank Name</td>
                      <td>{{$merchantInfo->nameOfBank}}</td>
                  </tr>
                  <tr>
                      <td>Branch</td>
                      <td>{{$merchantInfo->bankBranch}}</td>
                  </tr>
                  <tr>
                      <td>Acc Holder</td>
                      <td>{{$merchantInfo->bankAcHolder}}</td>
                  </tr>
                  <tr>
                      <td>Bank Account No</td>
                      <td>{{$merchantInfo->bankAcNo}}</td>
                  </tr>
                  <tr>
                      <td>Bkash Number</td>
                      <td>{{$merchantInfo->bkashNumber}}</td>
                  </tr>
                  <!--<tr>-->
                  <!--    <td>Roket Number</td>-->
                  <!--    <td>{{$merchantInfo->roketNumber}}</td>-->
                  <!--</tr>-->
                  <!--<tr>-->
                  <!--    <td>Nagod Number</td>-->
                  <!--    <td>{{$merchantInfo->nogodNumber}}</td>-->
                  <!--</tr>-->
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




<!--my code--->

<script>
       $(document).ready(function() {
          $('#merchantexample').DataTable( {
              dom: 'Bfrtip',
              "lengthMenu": [[ 200, 500, -1], [ 200, 500, "All"]],
              buttons: [
                  {
                      extend: 'copy',
                      text: 'Copy',
                      exportOptions: {
                           columns: [ 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                      }
                  },
                  {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                           columns: [ 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                      }
                  },
                  {
                      extend: 'csv',
                      text: 'Csv',
                      exportOptions: {
                           columns: [ 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      exportOptions: {
                           columns: [ 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
                      }
                  },
                  
                  {
                      extend: 'print',
                      text: 'Print',
                      exportOptions: {
                           columns: [ 1, 2, 3, 4, 5, 6,7,8,9,10,11,12,13]
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
          
           table.buttons().container()
              .appendTo( '#example_wrapper .col-md-6:eq(0)' );
      });
</script>



@endsection








