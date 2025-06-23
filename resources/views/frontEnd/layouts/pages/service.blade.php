@extends('frontEnd.layouts.master')


@php
    $lastSegment = request()->segment(count(request()->segments()));
    $pageTitle = 'ZiDrop | Default Title';
    $pageDescription = 'Default meta description goes here.';
    $canonicalUrl = url('https://zidrop.com/our-service/' . $lastSegment);
    $slugRange = ['1', '2', '3', '4', '5', '6'];

    if ($lastSegment == '1') {
        $pageTitle = 'ZiDrop | Fast & Reliable P2P Delivery Service | Secure Peer-to-Peer Shipping';
        $pageDescription = 'Experience seamless peer-to-peer (P2P) delivery with our fast, secure, and affordable shipping service. Connect directly with trusted individuals for safe and efficient package transfers.';
    } elseif ($lastSegment == '2') {
        $pageTitle = 'ZiDrop | Efficient Merchant Delivery Service | Reliable Business Shipping Solutions';
        $pageDescription = 'Streamline your business logistics with ZiDrop\'s Merchant Delivery Service. Enjoy fast, secure, and cost-effective deliveries tailored for merchants and online sellers. Boost customer satisfaction with reliable shipping.';
    } elseif ($lastSegment == '3') {
        $pageTitle = 'ZiDrop Classic Plan | Affordable & Flexible Delivery Solutions for Everyone';
        $pageDescription = 'Discover the ZiDrop Classic Plan — your go-to solution for reliable and budget-friendly delivery services. Perfect for individuals and small businesses seeking flexibility and efficiency in package shipping.';
    } elseif ($lastSegment == '4') {
        $pageTitle = 'ZiDrop Corporate & SME Delivery | Scalable Logistics for Growing Businesses';
        $pageDescription = 'Empower your business with ZiDrop’s Corporate & SME Delivery service. Designed for scale, speed, and reliability — we simplify logistics for startups, enterprises, and growing teams with tailored delivery solutions.';
    } elseif ($lastSegment == '5') {
        $pageTitle = 'ZiDrop Air Parcel | Fast & Secure Air Cargo Delivery Services';
        $pageDescription = 'Ship your packages with speed and safety using ZiDrop\'s Air Parcel service. Enjoy reliable air cargo solutions for domestic and international deliveries — perfect for urgent and long-distance shipping needs.';
    } elseif ($lastSegment == '6') {
        $pageTitle = 'ZiDrop Line Haul | Long-Distance Delivery & Intercity Transport Services';
        $pageDescription = 'Optimize your intercity logistics with ZiDrop’s Line Haul service. Designed for long-distance and high-volume transport, our service ensures timely, efficient, and cost-effective deliveries across regions.';
    }

@endphp


@php
    $currentUrl = url()->current(); // gets the full current URL

    $ogTitle = 'Fast Ecommerce Logistics for Smart Reliable, Efficient Deliveries';
    $ogType = 'website';
    $ogImage = asset('https://zidrop.com/uploads/logo/Logo-For-Zidrop-Logistics%20(1).png');
    $ogUrl = $currentUrl;

    $twitterCard = 'summary_large_image';
    $twitterSite = '@zidroplogistics';
    $twitterTitle = $ogTitle;
    $twitterDescription = 'Zidrop Logistics provides reliable, efficient, and cost-effective logistics solutions tailored to meet your transportation, warehousing, and distribution needs.';
    $twitterImage = $ogImage;
@endphp

@section('og_title', $ogTitle)
@section('og_type', $ogType)
@section('og_image', $ogImage)
@section('og_url', $ogUrl)

@section('twitter_card', $twitterCard)
@section('twitter_site', $twitterSite)
@section('twitter_title', $twitterTitle)
@section('twitter_description', $twitterDescription)
@section('twitter_image', $twitterImage)



@section('title', $pageTitle)
@section('meta_description', $pageDescription)
@section('canonical', $canonicalUrl)

@if(in_array($lastSegment, $slugRange))
    @section('hreflangs')
        <link rel="alternate" href="{{ url('/our-service/' . $lastSegment) }}" hreflang="en">
        <link rel="alternate" href="{{ url('/our-service/' . $lastSegment) }}" hreflang="hi">
        <link rel="alternate" href="{{ url('/our-service/' . $lastSegment) }}" hreflang="x-default">
    @endsection
@endif

@section('og_title', $ogTitle)
@section('og_type', $ogType)
@section('og_image', $ogImage)
@section('og_url', $ogUrl)

@section('twitter_card', $twitterCard)
@section('twitter_site', $twitterSite)
@section('twitter_title', $twitterTitle)
@section('twitter_description', $twitterDescription)
@section('twitter_image', $twitterImage)



@php
    // In case it's not already set earlier in your view:
    $currentUrl = url()->current();
@endphp

@section('schema')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "{{ $pageTitle }}",
      "description": "{{ $pageDescription }}",
      "url": "{{ $currentUrl }}",
      "publisher": {
        "@type": "Organization",
        "name": "ZiDrop Logistics",
        "logo": {
          "@type": "ImageObject",
          "url": "{{asset($servicedetails->image)}}"
        }
      }
    }
    </script>
@endsection





@section('content')
<!-- Breadcrumb -->
<div class="breadcrumbs" style="background:#db0022;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <!-- Bread Menu -->
                    <div class="bread-menu">
                        <ul>
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><a href="">{{$servicedetails->title}}</a></li>
                        </ul>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->

   <!-- About Section start -->
     <div class="about-area section-padding bg-gray">
         <div class="container">
             <div class="row">
                 <div class="col-lg-6 col-md-12 col-xs-12 info">
                     <div class="about-wrapper wow fadeInLeft" data-wow-delay="0.3s">
                         <div>
                             <div class="site-heading">
                                 <h1 class="section-title">{{$servicedetails->title}}</h1>
                             </div>
                             <div class="content">
                                 <p>
                                    {!! $servicedetails->text !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-6 col-md-12 col-xs-12 wow fadeInRight" data-wow-delay="0.3s">
                     <img class="img-fluid" src="{{asset($servicedetails->image)}}" title="{{$servicedetails->title}}" alt="{{$servicedetails->title}}">
                 </div>
             </div>
         </div>
     </div>
             <!-- Call To Action Section Start -->
     <!-- <section id="cta" class="section-padding bg-gray">
         <div class="container">
             <div class="row">
                 <div class="col-lg-6 col-md-6 col-xs-12 wow fadeInLeft" data-wow-delay="0.3s">
                     <div class="cta-text">
                         <h4>Get 30 days free trial</h4>
                         <p>Praesent imperdiet, tellus et euismod euismod, risus lorem euismod erat, at finibus neque odio quis metus. Donec vulputate arcu quam. </p>
                     </div>
                 </div>
                 <div class="col-lg-6 col-md-6 col-xs-12 text-right wow fadeInRight" data-wow-delay="0.3s">
                     <a href="{{url('/')}}" class="btn btn-common">Register Now</a>
                 </div>
             </div>
         </div>
     </section> -->
     <!-- Call To Action Section Start -->
@endsection