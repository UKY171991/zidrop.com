<!-- Design and Development by ZiDrop Team-->
@extends('frontEnd.layouts.master')
@section('title', 'ZIDROP | Nigeria’s Pay on Delivery Logistics Company | Express Delivery')
@section('meta_description', 'ZiDrop Logistics offers Nigeria’s trusted Pay on Delivery service. Track & manage shipments easily with the ZiDrop Go App. As Quick As A Click.')

@section('canonical', url('https://zidrop.com/'))


@section('og_title', 'Fast Ecommerce Logistics for Smart Reliable, Efficient Deliveries')
@section('og_type', 'website')
@section('og_image', asset('https://zidrop.com/uploads/logo/Logo-For-Zidrop-Logistics%20(1).png'))
@section('og_url', url('https://zidrop.com/'))

@section('twitter_card', 'summary_large_image')
@section('twitter_site', '@zidroplogistics')
@section('twitter_title', 'Fast Ecommerce Logistics for Smart Reliable, Efficient Deliveries')
@section('twitter_description',
    'Zidrop Logistics provides reliable, efficient, and cost-effective logistics solutions
    tailored to meet your transportation, warehousing, and distribution needs.')
@section('twitter_image', asset('https://zidrop.com/uploads/logo/Logo-For-Zidrop-Logistics%20(1).png'))

@section('hreflangs')
    <link rel="alternate" href="{{ url('/') }}" hreflang="en">
    <link rel="alternate" href="{{ url('/') }}" hreflang="hi">
    <link rel="alternate" href="{{ url('/') }}" hreflang="x-default">
@endsection

@php
    // In case it's not already set earlier in your view:
    $currentUrl = url()->current();
@endphp

@section('schema')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "ZiDrop | Nigeria’s Leading Logistics Company | As Quick as a Click",
      "description": "Zidrop Logistics provides reliable, efficient, and cost-effective logistics solutions tailored to meet your transportation, warehousing, and distribution needs.",
      "url": "{{ $currentUrl }}",
      "publisher": {
        "@type": "Organization",
        "name": "ZiDrop Logistics",
        "logo": {
          "@type": "ImageObject",
          "url": "https://zidrop.com/uploads/logo/Logo-For-Zidrop-Logistics%20(1).png"
        }
      }
    }
    </script>
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/home_service.css">

    <!-- Hero Section -->
    <style>
        .form-control:focus {
            border: 1px solid #ccc !important;
            box-shadow: 0 0 0 .0rem rgba(0, 123, 255, .25) !important;
        }
    </style>
    <section class="style1 home-section">
        <div class="home-container">
            <!-- slider part -->
            <div class="owl-carousel home-slider"> <!--home-slider -->
                @foreach ($banner as $key => $value)
                    <!-- <div class="single-slider-hero"> -->
                    <div class="single-slider-hero">
                        <img src="{{ asset($value->image) }}" title="{{ $value->title }}" alt="{{ $value->title }}" />
                    </div>
                @endforeach
            </div>
            <!-- slider part end -->
            <!-- login area  -->
            <?php /*
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
        */
            ?>
            <!-- login area end -->
            <!--/ End Single Slider -->
        </div>
    </section>


    <section id="cta" class="quicktech-traking for_laptop section-bg">
        <div class="container">
            <div class="row home-track-inner justify-content-center">
                <!-- <div class="col-lg-6 col-md-6 col-xs-12" >
                        <div class="cta-text text-center">
                            <h4 style="color: #af251b">Zidrop Logistics </h4>
                        </div>
                    </div> -->
                <div class="col-lg-10 col-md-10 col-xs-12">
                    <form action="{{ url('/track/parcel/') }}" method="POST" class="form-row track_form">
                        @csrf
                        <!-- <div class="row">
                                <div class="col-md-12"> -->
                        <div class="btn-group" role="group" style="width: 100%;">
                            <input type="text" name="trackparcel" class="form-control w-100"
                                placeholder="Type your tracking number" required=""
                                data-error="Please enter your tracking number">
                            <button type="submit" class="btn btn-common trace_book"><i
                                    class="fa fa-search search-icon"></i><span class="search-text">TRACK
                                    PARCEL</span></button>
                            <button type="submit" class="btn btn-common btn_extra"
                                style="background-color: white;color:white;border: none;cursor: unset;"><i
                                    class="fa fa-search search-icon"></i></button>
                            <a onclick="fbq('track', 'P2PBookClick');" href="{{ url('/p2p/') }}"
                                class="btn btn-primary P2PBookClick"><i class="fa fa-car"></i> Book P2P</a>
                            <!-- </div>
                                </div>  -->
                            <!-- <div class="col-lg-8 col-md-4 col-xs-12">
                                    <input type="text" name="trackparcel" class="form-control" placeholder="Stay Updated!" required="" data-error="Please enter your tracking number">
                                </div>
                                <div class="col-lg-2 col-md-4 col-xs-12  text-center">
                                    <button type="submit" class="btn btn-common">TRACK PARCEL</button>
                                </div> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="cta" class="quicktech-traking for_mobile section-bg">
        <div class="container">
            <div class="row home-track-inner justify-content-center">
                <!-- <div class="col-lg-6 col-md-6 col-xs-12" >
                        <div class="cta-text text-center">
                            <h4 style="color: #af251b">Zidrop Logistics </h4>
                        </div>
                    </div> -->
                <div class="col-lg-10 col-md-10 col-xs-12">
                    <form action="{{ url('/track/parcel/') }}" method="POST" class="form-row track_form">
                        @csrf

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home"
                                    type="button" role="tab" aria-controls="nav-home" aria-selected="true"><i
                                        class="fa fa-search" aria-hidden="true"></i> Track Order</button>
                                <a class="nav-link" id="nav-profile-tab" href="{{ url('/p2p/') }}" role="tab"
                                    aria-controls="nav-profile" aria-selected="false"> <i class="fa fa-car"></i> Book
                                    P2P</a>

                            </div>
                        </nav>
                        <div class="tab-content mt-3" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab">
                                <div class="btn-group" role="group" style="width: 100%;">
                                    <input type="text" name="trackparcel" class="form-control w-100"
                                        placeholder="Type your tracking number" required=""
                                        data-error="Please enter your tracking number">
                                    <button type="submit" class="btn btn-common trace_book"><i
                                            class="fa fa-search search-icon"></i><span class="search-text">TRACK
                                            PARCEL</span></button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab"> Testing </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Statistics Section --}}

    <section id="ft-funfact-2" class="ft-funfact-section-2">
        <div class="container">
            <div class="ft-section-title-3 headline text-center">
                <span class="pr-sx-title-tag position-relative">Our Experience</span>
                <h2>OUR NUMBERS SPEAK FOR US</h2>
            </div>
            <div class="ft-funfact-content-2 position-relative">
                <span class="map-bg position-absolute text-center"><img src="{{ asset('/frontEnd/img/map.png') }}"
                        alt=""></span>
                <div class="ft-funfact-inner-items-wrapper position-relative">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="ft-funfact-inner-items text-center">
                                <div
                                    class="ft-funfact-inner-icon d-flex align-items-center justify-content-center position-relative">
                                    <img class="flaticon-delivery-truck"
                                        src="{{ asset('/frontEnd/img/fast-delivery.png') }}" alt="">
                                </div>                                <div class="ft-funfact-inner-text headline pera-content">
                                    <h3><span class="counter">{{ $webStatisticsDetails->total_delivery ?? 0 }}</span>M+</h3>
                                    <p>Completed Deliveries</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="ft-funfact-inner-items text-center">
                                <div
                                    class="ft-funfact-inner-icon d-flex align-items-center justify-content-center position-relative">
                                    <i class=""></i>
                                    <img class="flaticon-community" src="{{ asset('/frontEnd/img/social-justice.png') }}"
                                        alt="">
                                </div>                                <div class="ft-funfact-inner-text headline pera-content">
                                    <h3><span class="counter">{{ $webStatisticsDetails->total_customers ?? 0 }}</span>M+</h3>
                                    <p>Customers Served</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="ft-funfact-inner-items text-center">
                                <div
                                    class="ft-funfact-inner-icon d-flex align-items-center justify-content-center position-relative">
                                    <img class="flaticon-compliance" src="{{ asset('/frontEnd/img/compliance.png') }}"
                                        alt="">
                                </div>                                <div class="ft-funfact-inner-text headline pera-content">
                                    <h3><span class="counter">{{ $webStatisticsDetails->total_years ?? 0 }}</span>+</h3>
                                    <p>Years Experience</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="ft-funfact-inner-items text-center">
                                <div
                                    class="ft-funfact-inner-icon d-flex align-items-center justify-content-center position-relative">
                                    <img class="flaticon-face-detection"
                                        src="{{ asset('/frontEnd/img/face-detection.png') }}" alt="">
                                </div>                                <div class="ft-funfact-inner-text headline pera-content">
                                    <h3><span class="counter">{{ $webStatisticsDetails->total_member ?? 0 }}</span>K+</h3>
                                    <p>Team Members</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Hero Section -->
    <!--Features Area -->
    {{-- <section class="service-accordion-section">
    <div class="container pt-5 px-lg-3 px-md-0">
        <div class="py-5 mx-auto">
            <div class="col-12 py-2">
                <div class="row">
                    @foreach ($features as $key => $value)
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
</section> --}}


    <!-- New design service start -->
    <section class="features-area new_service_section">


        <div class="container text-center new_service">
            <div class="row">
                <div class="col-md-4 title_line">
                    <hr class="title_underline">
                    <h1 id="line">We Offer Reliable Services.</h1>
                </div>
            </div>

            <div class="row align-items-start">


                <?php $i =1; foreach($frnservices as $key=>$value) { ?>

                <div class="col-md-4 ">
                    <div class="card card-hover-effect card<?= $i++ ?> w-100" style="width: 18rem;">
                        <a href="{{ url('our-service/' . $value->id) }}">
                            <img src="{{ $value->image }}" class="card-img-top" title="{{ $value->title }}"
                                alt="{{ $value->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $value->title }}</h5>
                                <p class="card-text">{{ Str::limit($value->text, 140) }}</p>
                            </div>
                        </a>
                    </div>
                </div>

                <?php } ?>

            </div>
        </div>

    </section>


    <!-- New design service start -->

    <?php /*
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

*/
    ?>

    <!--App info start-->
             
                
                  <style>
                    body {
                      margin: 0;
                      font-family: 'Arial', sans-serif;
                    }

                    .zidrop-giggo-section {
                      background-color: #000;
                      color: #fff;
                      padding: 20px 5%;
/*                      margin-bottom: 50px;*/
                    }

                    .zidrop-giggo-wrapper {
                      display: flex;
                      align-items: center;
                      justify-content: space-between;
                      flex-wrap: wrap;
                      width: 100%;
                    }

                    .zidrop-giggo-left {
                      flex: 1 1 50%;
                      min-width: 300px;
                    }

                    .zidrop-giggo-title {
                      font-size: 36px;
                      font-weight: bold;
                      margin-bottom: 30px;
                      line-height: 1.4;
                      color: white;
                    }

                    .zidrop-giggo-buttons a img {
                      height: 60px;
                      margin-right: 15px;
                    }

                    .zidrop-giggo-right {
                      flex: 1 1 50%;
                      text-align: center;
                      margin-top: 20px;
                      min-width: 300px;
                    }

                    .zidrop-giggo-image-circle {
                      background: #eee;
                      border-radius: 50%;
                      display: inline-block;
                      padding: 40px;
                    }

                    .zidrop-giggo-image-circle img {
                      width: 400px;
                      max-width: 100%;
                      border-radius: 20px;
                    }

                    .zidrop-giggo-right {
                      flex: 1 1 50%;
                      display: flex;
                      justify-content: flex-end; /* Align image to the right */
                      align-items: center;
                      margin-top: 0px;
                      min-width: 300px;
                    }

                    .zidrop-giggo-image-circle {
                      background: #eee;
                      border-radius: 50%;
                      padding: 40px;
                    }


                    @media (max-width: 768px) {
                      .zidrop-giggo-wrapper {
                        flex-direction: column;
                        text-align: center;
                      }

                      .zidrop-giggo-title {
                        font-size: 26px;
                      }

                      .zidrop-giggo-image-circle {
                        margin-top: 30px;
                        padding: 20px;
                      }
                      .zidrop-giggo-right {
                          justify-content: center !important;
                        }

                        .zidrop-giggo-section {
                          padding: 60px 5%;
                          margin-bottom: 0px;
                        }

                    }
                  </style>
                </head>
                <body>

                  <section class="zidrop-giggo-section">
                    <div class="zidrop-giggo-wrapper">
                      
                      <!-- Left Content -->
                      <div class="zidrop-giggo-left">
                        <h2 class="zidrop-giggo-title">ZiDrop Go – The shipping app built for eCommerce and beyond.</h2>
                        <div class="zidrop-giggo-buttons">
                          <a href="#"><img src="frontEnd/img/aaaple.png" alt="App Store" class="mt-2"></a>
                          <a href="#"><img src="frontEnd/img/andriod.png" alt="Google Play" class="mt-2"></a>
                        </div>
                      </div>

                      <!-- Right Content -->
                      <div class="zidrop-giggo-right">
                        <div class="zidrop-giggo-image-circle">
                          <!-- <img src="https://giglassets.s3.eu-west-2.amazonaws.com/images/app-screen.png" alt="ZiDrop App"> -->
                          <img src="frontEnd/img/on-demand-service-apps-1.png" alt="ZiDrop App">
                        </div>
                      </div>

                    </div>
                  </section>


            <!--App info end-->

    <!-- End Features Area -->
    <!-- Spark Delivery-price -->
    <section class="quickTech-price section-space zip_drip_why pt-5" style="background:#f1f3f4">
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


                @foreach ($prices as $key => $value)
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="{{ asset($value->image) }}" title="{{ $value->name }}"
                                    alt="{{ $value->name }}" />
                            </div>
                            <div class="col-md-9">
                                <!--<div class="single-quickTech-price" style="margin-top: 0px;"> -->
                                <!--
               <div class="quickTech-price-head">
                
                <div class="icon-bg">{{ $value->price }} N</div>
               </div>
               -->
                                <div class="quickTech-price-content">
                                    <h5><a href="#">{{ $value->name }}</a></h5>
                                    <p>{!! $value->text !!}</p>
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
                            <b style="font-size: 28px;">What Merchants Are Saying So Far...</b>
                            <hr />
                        </div>
                    </div>
                    <div class="testimonial-inner">
                        <div class="testimonial-slider">
                            @foreach ($clientsfeedback as $key => $value)
                                <!-- Single Testimonial -->
                                <div class="single-slider-testimonial">
                                    <ul class="star-list">
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                    </ul>
                                    <p>{{ $value->text }}</p>
                                    <!-- Client Info -->
                                    <div class="t-info">
                                        <div class="t-left">
                                            <div class="client-head"><img src="{{ asset($value->image) }}"
                                                    title="{{ $value->name }}" alt="{{ $value->name }}" /></div>
                                            <h2>{{ $value->description }} <span>{{ $value->name }}</span></h2>
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
                @foreach ($partners as $key => $value)
                    <!-- Single client -->
                    <div class="single-slider">
                        <div class="single-client">
                            <img src="{{ asset($value->image) }}" title="Partners" alt="Partners">
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

        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: "v11.0",
            });
        };

        (function(d, s, id) {
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
        $(document).ready(function() {
            @if (!empty($globNotice))
                $('#globalNoticeModal').modal('show');
            @endif
        });
    </script>

@endsection
