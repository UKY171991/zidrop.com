@extends('frontEnd.layouts.master')
@section('title','Faq')
@section('content')
 
<!--Quicktech Carrier Section Start -->
 <section id="quickTech-carrier" class="bg-gray">
        <div class="section-header mb-4">
            <div class="breadcrumbs" style="background:#db0022;">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="bread-inner">
                                <!-- Bread Menu -->
                                <div class="bread-menu">
                                <ul>
                                <li><a href="{{url('/')}}">Home</a></li>
                                <li><a href="#">Frequently asked questions</a></li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    <!-- / End Breadcrumb -->
    <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
    <div class="container">
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark text-dark">
					<div class="card-header" id="heading1">
						<h5 href="#collapse2" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
								<h5 class="p-0 text-danger"><span class="rounded-circle"> + </span> What is Spark Delivery?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse2" class="collapse p-2" data-parent="#accordian">
                        <p class="text-justify border text-light p-2">Spark Delivery is a delivery service provider. Our main object is to support Bangladesh to grow e-commerce and f-commerce sector by providing a better service.
                        Our service can be used to deliver across Bangladesh, SPARK DELIVERY provides services for both trades and entities. Spark Delivery is relied for the fast and effective delivery.
                        We provide home delivery for any parcels of multiple sizes and weights all over Bangladesh.</p>
					</div>
				</div>
             </div>
        </div>
        <hr class="bg-dark">
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark text-dark">
					<div class="card-header" id="heading1">
						<h5 href="#collapse3" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
								<h5 class="p-0 text-danger"><span class="rounded-circle"> + </span>After delivery how much time it will take to get my payment?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse3" class="collapse p-2" data-parent="#accordian">
                    <br>
                    <p class="text-justify border text-light p-2">
                    	ISD – Same day after delivery
                        <br>
                    </p>
                    <p class="text-justify border text-light p-2">
                    	Dhaka Sub – 2 Working days
                    </p>
                    <p class="text-justify border text-light p-2">
                    	Outside Dhaka – 2 Working days
                    </p>
					</div>
				</div>
             </div>
        </div>
        <hr class="bg-dark">
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark text-dark">
					<div class="card-header" id="heading1">
						<h5 href="#collapse4" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
								<h5 class="p-0 text-danger"><span class="rounded-circle"> + </span>How do I contact you?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse4" class="collapse p-2" data-parent="#accordian">
                    <br>
                    <p class="text-justify border text-light p-2">
                    	You can directly call us at +880 01303 355623 or can communicate through email at contact@sparkdelivery.com.bd.
                    	Also you can reach us through our <a href="https://www.facebook.com/spark.delivery.service"><span class="text-primary">Facebook Page</span></a>
                        <br>
                    </p>
					</div>
				</div>
             </div>
        </div>
        <hr class="bg-dark">
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark text-dark">
					<div class="card-header" id="heading1">
						<h5 href="#collapse5" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
								<h5 class="p-0 text-danger"><span class="rounded-circle"> + </span>What is the procedure to send a parcel via Spark Delivery?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse5" class="collapse p-2" data-parent="#accordian">
                    <br>
                    <p class="text-justify border text-light p-2">
                      Just need to complete the registration from <a href ="https://sparkdelivery.com.bd/merchant/register"><span class="text-primary">Here</span></a>, or download the SPARK DELIVERY app       
                      from <a href=""><span class="text-primary">Google Play Store</span></a> to avail our services. You can also contact us at 01303-355623 or   
                      through our <a href="https://www.facebook.com/spark.delivery.service"><span class="text-primary">Facebook Page</span></a>
                    </p>
					</div>
				</div>
             </div>
        </div>
        <hr class="bg-dark">
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark text-dark">
					<div class="card-header" id="heading1">
						<h5 href="#collapse6" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
								<h5 class="p-0 text-danger"><span class="rounded-circle"> + </span>How can I track my parcel?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse6" class="collapse p-2" data-parent="#accordian">
                    <br>
                    <p class="text-justify border text-light p-2">
                      You can your parcel from our website <a href="https://sparkdelivery.com.bd"><span class="text-primary">Here</span></a>
                    </p>
					</div>
				</div>
             </div>
        </div>
        <hr class="bg-dark"> 
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark text-dark">
					<div class="card-header" id="heading1">
						<h5 href="#collapse7" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
								<h5 class="p-0 text-danger"><span class="rounded-circle"> + </span>Do you hold a parcel for more than 3 days?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse7" class="collapse p-2" data-parent="#accordian">
                    <br>
                    <p class="text-justify border text-light p-2">
                     Currently we do not hold any parcel for more than 3 days once a parcel reaches its last mile hub. For more information, call us at 01303-355623.
                    </p>
					</div>
				</div>
             </div>
        </div>
        <hr class="bg-dark">
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark text-dark">
					<div class="card-header" id="heading1">
						<h5 href="#collapse8" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
								<h5 class="p-0 text-danger"><span class="rounded-circle"> + </span>How I will get my payment?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse8" class="collapse p-2" data-parent="#accordian">
                    <br>
                    <p class="text-justify border text-light p-2">
                      You need to fill up the payment info from your account setting. We are providing payment through Bank/Bkash
                    </p>
					</div>
				</div>
             </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark ">
					<div class="card-header" id="heading1">
						<h5 href="#collapse9" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
							    <h5 class="p-0 text-danger"><span class="rounded-circle"> + </span>Do you have any COD charges?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse9" class="collapse p-2" data-parent="#accordian">
                    <br>
                    <p class="text-justify border text-light p-2">
                        Currently, we do not have any COD charges for Inside Dhaka City.
                        For Outside Dhaka and Dhaka suburb 1% COD charges applicable.
                    </p>
					</div>
				</div>
             </div>
        </div>
        <hr class="bg-dark">
        <div class="row mb-2">
            <div class="col">
                <div class="card my-2 bg-dark ">
					<div class="card-header" id="heading1">
						<h5 href="#collapse10" data-toggle="collapse" class="text-dark">
							<a class="mb-0">
							    <h5 class="p-0 text-danger"><span class="rounded-circle"> + </span>Do you have any return charges?</h5>
							</a>
						</h5>
					</div>
					<div id="collapse10" class="collapse p-2" data-parent="#accordian">
                    <br>
                    <p class="text-justify border text-light p-2">
                        Currently, we do not have any return charges for Inside Dhaka City.
                        For Outside Dhaka and Dhaka suburb return charges are "Delivery charges + half of delivery charges".
                    </p>
					</div>
				</div>
             </div>
        </div>
    </div>
 </section>
@endsection
