<!-- Design and Development MD.ROBIUL AWAL- CALL :01748-340718-->
@extends('frontEnd.layouts.master') 
@section('title','Zidrop Logistics') 
@section('content')

<!-- Hero Section -->

<section class="style1 home-section">
    <div class="home-container">
        <!-- slider part -->
        <div class="owl-carousel home-slider">
            @foreach($banner as $key=>$value )
            <div class="single-slider-hero">
                <img src="{{asset($value->image)}}" alt="" />
            </div>
            @endforeach
        </div>
        <!-- slider part end -->
        <!-- login area  -->
        <div class="mobile-login">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <!--<li class="nav-item">-->
                <!--    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Mobile Login</a>-->
                <!--</li>-->
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Merchant Login</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="mobile-login-area">
                        <form action="{{url('merchant/login')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" required="" name="phoneOremail" placeholder="Phone" />
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" required="" placeholder="Password" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="login-btn">Login</button>
                            </div>
                        </form>
                        <div class="mobile-login-btn">
                            <div class="mobile-login-btn-left">
                                <a href="{{url('/merchant/register')}}">Register Now</a>
                            </div>
                            <div class="mobile-divider"></div>
                            <div class="mobile-login-btn-right">
                                <a href="{{url('/merchant/forget/password')}}">Forget Password</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="mobile-login-area">
                        <form action="{{url('merchant/login')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" name="phoneOremail" required="" placeholder="Email" />
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" required="" placeholder="Password" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="login-btn">Login</button>
                            </div>
                        </form>
                        <div class="mobile-login-btn">
                            <div class="mobile-login-btn-left">
                                <a href="{{url('/merchant/register')}}">Register Now</a>
                            </div>
                            <div class="mobile-divider"></div>
                            <div class="mobile-login-btn-right">
                                <a href="{{url('/merchant/forget/password')}}">Forget Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- login area end -->
    <!--/ End Single Slider -->
    </div>
</section>


<section id="cta" class="quicktech-traking">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12" >
                <div class="cta-text text-center">
                    <h4 style="color: #af251b">Zidrop Logistics </h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <form action="{{url('/track/parcel/')}}" method="POST" class="form-row">
                @csrf
                    <div class="row">
                        <div class="col-lg-8 col-md-4 col-xs-12">
                            <input type="text" name="trackparcel" class="form-control" placeholder="Stay Updated!" required="" data-error="Please enter your tracking number">
                        </div>
                        <div class="col-lg-2 col-md-4 col-xs-12  text-center">
                            <button type="submit" class="btn btn-common">TRACK PARCEL</button>
                        </div>
                    </div>
                </form>
            </div>                 
        </div>
    </div>
</section>

<!--/ End Hero Section -->
<!--Features Area -->
<section class="service-accordion-section">
    <div class="container pt-5 px-lg-3 px-md-0">
        <div class="py-5 mx-auto">
            <div class="col-12 py-2">
                <div class="row">
                    @foreach($features as $key=>$value)
                    <div class="col-md-6 pl-0 pr-4">
                        <div class="card feature-accordion mb-4 border-0">
                            <div id="feature-{{$key+1}}" class="card-header py-2 bg-white border-bottom-0">
                                <div data-toggle="collapse" data-target="#collapse{{$key+1}}" aria-expanded="false" aria-controls="collapse{{$key+1}}" class="cursor-pointer py-3 d-flex justify-content-between align-items-center collapsed">
                                    <div class="d-block">
                                        <span>
                                            <i class="fa {{$value->icon}}" style="color: #af261c; font-size: 20px;"></i>
                                        </span>
                                        <span class="pl-2 font-18 font-h-md-16 font-medium">{{$value->title}}</span>
                                    </div>
                                    <span class="tgl-icon">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="collapse{{$key+1}}" aria-labelledby="feature-{{$key+1}}" class="collapse" style="">
                                <div class="card-body border-top">
                                    {{$value->subtitle}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-area section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                <div class="section-title default text-center">
                    <div class="section-top">
                    <!-- <hr class="title_underline"> -->
                        <h1 class="text-dark"><span>Our</span><b>Services</b></h1>
                    </div>
                    <div class="section-bottom">
                        <div class="text">
                            <p>We Love to Serve Delightful Experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($frnservices as $key=>$value)
            <div class="col-lg-3 col-md-6 col-12">
                <!--Single Feature -->
                <div class="single-feature">
                    <div class="icon-head"><i class="fa {{$value->icon}}"></i></div>
                    <h4><a href="{{url('our-service/'.$value->id)}}">{{$value->title}} </a></h4>
                    <p>{{Str::limit($value->text,140)}}</p>
                    <div class="button">
                        <a href="{{url('our-service/'.$value->id)}}" class="quickTech-btn"><i class="fa fa-arrow-circle-o-right"></i>More Detail</a>
                    </div>
                </div>
                <!--/ End Single Feature -->
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- End Features Area -->
<!-- Spark Delivery-price -->
<section class="quickTech-price section-space" style="background:#f1f3f4">
    <div class="container">
        <!--<div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                <div class="section-title default text-center">
                    <div class="section-top">
                        <h1><span>Our</span><b>Charges</b></h1>
                        
                    </div>
                    <div class="section-bottom">
                        <div class="text">
                            <p>We Love to Serve Delightful Experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <hr class="title_underline">
		<h4 class="common-heading"><b>Why <span>ZiDrop </span></b></h4><br>
        <div class="row">
		
		
            @foreach($prices as $key=>$value)
            <div class="col-lg-4 col-md-4 col-12">
				<div class="row">
					<div class="col-md-3">
						<img src="{{asset($value->image)}}" alt="{{$value->name}}" />
					</div>
					<div class="col-md-9">
						<!--<div class="single-quickTech-price" style="margin-top: 0px;"> -->
							<!--
							<div class="quickTech-price-head">
								
								<div class="icon-bg">{{$value->price}} N</div>
							</div>
							 -->
							<div class="quickTech-price-content">
								<h5><a href="#">{{$value->name}}</a></h5>
								<p>{!!$value->text!!}</p>
								<!--<a class="btn" href="#"><i class="fa fa-arrow-circle-o-right"></i>Book Now</a> -->
							</div>
						<!--</div> -->
					</div>
				</div>
                <!-- Single quickTech-price -->
                
                <!--/ End Single zuri Express-price -->
            </div>
            @endforeach
        </div>
    </div>
</section>

<!--/ End zuri Express-price -->




<!-- Testimonials -->
<section class="testimonials section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title default">
                    <div class="section-top justify-content-center">
                        <b style="font-size: 28px;">What Our Merchants Are Saying So Far...</b>
                        <hr />
                    </div>
                </div>
                <div class="testimonial-inner">
                    <div class="testimonial-slider">
                        @foreach($clientsfeedback as $key=>$value)
                        <!-- Single Testimonial -->
                        <div class="single-slider-testimonial">
                            <ul class="star-list">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                            <p>{{$value->text}}</p>
                            <!-- Client Info -->
                            <div class="t-info">
                                <div class="t-left">
                                    <div class="client-head"><img src="{{asset($value->image)}}" alt="#" /></div>
                                    <h2>{{$value->description}} <span>{{$value->name}}</span></h2>
                                </div>
                                <div class="t-right">
                                    <div class="quote"><i class="fa fa-quote-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- / End Single Testimonial -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--End Testimonials -->

<!-- partner slider -->
<section>
    <div class="container">
        <div class="partner-slider owl-carousel">
        @foreach($partners as $key=>$value)
            <!-- Single client -->
            <div class="single-slider">
                <div class="single-client">
                    <img src="{{asset($value->image)}}" />
                </div>
            </div>
            <!--/ End Single client -->
        @endforeach
        </div>
    </div>
</section>

@include('frontEnd.layouts._notice_modal')
<!-- Messenger Chat Plugin Code -->
<div id="fb-root"></div>

<!-- Your Chat Plugin code -->
<div id="fb-customer-chat" class="fb-customerchat"></div>

<script>

    var chatbox = document.getElementById("fb-customer-chat");
    chatbox.setAttribute("page_id", "109961004701121");
    chatbox.setAttribute("attribution", "biz_inbox");

    window.fbAsyncInit = function () {
        FB.init({
            xfbml: true,
            version: "v11.0",
        });
    };

    (function (d, s, id) {
        var js,
            fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
        fjs.parentNode.insertBefore(js, fjs);
    })(document, "script", "facebook-jssdk");
</script>
@endsection

@section('custom_js_script')
    <script>
        $(document).ready(function () {
            @if(!empty($globNotice))
            $('#globalNoticeModal').modal('show');
            @endif
        });
    </script>
@endsection