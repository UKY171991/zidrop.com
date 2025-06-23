@extends('frontEnd.layouts.pages.deliveryman.master')
@section('title','Track Order')
@section('content')
<section class="section-padding">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="row addpercel-inner">
					<div class="col-sm-5">
						<div class="track-left">
							<h4>Track Parcel</h4>
							@foreach($trackInfos as $trackInfo)
							<div class="tracking-step">
								<div class="tracking-step-left">
									<strong>{{date('h:i A', strtotime($trackInfo->created_at))}}</strong>
									<p>{{date('M d, Y', strtotime($trackInfo->created_at))}}</p>
								</div>
								<div class="tracking-step-right">
									<p>{{$trackInfo->note}}</p>
								</div>
							</div>
							@endforeach
						</div>
					</div>
					<div class="col-sm-7">
						<div class="track-right">
							<h4>Customer and Parcel Details</h4>
							<div class="item">
								<p>Parcel ID</p>
								<h6><strong>{{$trackparcel->trackingCode}}</strong></h6>
							</div>
							<div class="item">
								<p>Customer Name :</p>
								<h6><strong>{{$trackparcel->recipientName}}</strong></h6>
							</div>
							<div class="item">
								<p>Customer Address :</p>
								<h6><strong>{{$trackparcel->recipientAddress}}</strong></h6>
							</div>
							<div class="item">
								<p>Customer Phone :</p>
								<h6><strong>{{$trackparcel->recipientPhone}}</strong></h6>
							</div>
							<div class="item">
								<p>Pickup City/Town :</p>
								<h6><strong>{{$trackparcel->pickupcity->title}} / {{$trackparcel->pickuptown->title}}</strong></h6>
							</div>
                            <div class="item">
								<p>Delivery City/Town :</p>
								<h6><strong>{{$trackparcel->deliverycity->title}} / {{$trackparcel->deliverytown->title}}</strong></h6>
							</div>
							<div class="item">
								<p>Weight :</p>
								<h6><strong>{{$trackparcel->productWeight}}</strong></h6>
							</div>
							<div class="item">
								<p>Price :</p>
								<h6><strong>{{ number_format($trackparcel->cod, 2) }}</strong></h6> 
							</div>
							
							<div class="item">
								<p>Delivery Charge :</p>
								<h6><strong>{{ number_format($trackparcel->deliveryCharge, 2) }}</strong></h6>
							</div>
							<div class="item">
								<p>Cod :</p>
								<h6><strong>{{ number_format($trackparcel->codCharge, 2) }}</strong></h6>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</section>

@endsection