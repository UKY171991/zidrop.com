<!-- Design and Development by ZiDrop Team-->
@extends('frontEnd.layouts.master') 

@php
    // In case it's not already set earlier in your view:
    $currentUrl = url()->current();
@endphp

@section('title','ZiDrop Logistics Shipment Tracking | Track Your Delivery in Real-Time') 
@section('meta_description', 'Easily track your ZiDrop shipments in real-time. Enter your tracking number to get instant updates on your delivery status. Fast, accurate, and secure shipment tracking with ZiDrop Logistics.')

@section('canonical', url('https://zidrop.com/tracking'))


@section('og_title', 'ZiDrop Logistics Shipment Tracking | Track Your Delivery in Real-Time')
@section('og_type', 'website')
@section('og_image', asset('https://zidrop.com/uploads/logo/Logo-For-Zidrop-Logistics%20(1).png'))
@section('og_url', url('{{ $currentUrl }}'))

@section('twitter_card', 'summary_large_image')
@section('twitter_site', '@zidroplogistics')
@section('twitter_title', 'ZiDrop Logistics Shipment Tracking | Track Your Delivery in Real-Time')
@section('twitter_description', 'Easily track your ZiDrop shipments in real-time. Enter your tracking number to get instant updates on your delivery status. Fast, accurate, and secure shipment tracking with ZiDrop Logistics.')
@section('twitter_image', asset('https://zidrop.com/uploads/logo/Logo-For-Zidrop-Logistics%20(1).png'))

@section('hreflangs')
    <link rel="alternate" href="{{ url('/tracking/') }}" hreflang="en">
    <link rel="alternate" href="{{ url('/tracking/') }}" hreflang="hi">
    <link rel="alternate" href="{{ url('/tracking/') }}" hreflang="x-default">
@endsection



@section('schema')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "ZiDrop Logistics Shipment Tracking | Track Your Delivery in Real-Time",
      "description": "Easily track your ZiDrop shipments in real-time. Enter your tracking number to get instant updates on your delivery status. Fast, accurate, and secure shipment tracking with ZiDrop Logistics.",
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

<link rel="stylesheet" href="{{asset('frontEnd/')}}/css/home_service.css">

<!-- Hero Section -->

<style>
    .tracking h3 {
        font-size: 24px;
        font-weight: 500;
        line-height: 36px;
    }
    .tracking p {
        margin-bottom: 1.8rem;
        line-height: 1.7em;
        letter-spacing: .35px;
        font-weight: 400;
        color: #3a3a3a;
        font-size: 15px;
    }
    .tracking h2 {
        font-weight: 600;
        color: #213540;
        letter-spacing: .25px;
        font-size: 36px;
    }
    .tracking a {
        color: #af261c;
        text-transform: uppercase;
        display: inline-flex;
        font-weight: 700;
        font-size: 16px;
    }
    .form-control:focus{
        border: 1px solid #ccc !important;
        box-shadow: 0 0 0 .0rem rgba(0, 123, 255, .25) !important;
    }
    
</style>


<section id="cta" class="quicktech-traking for_laptop">
    <div class="container">
        <h1 class="text-center mt-5">Shipment Tracking</h1>
        <p class="text-center">Track your parcel</p>
        <div class="row home-track-inner justify-content-center">
            <!-- <div class="col-lg-6 col-md-6 col-xs-12" >
                <div class="cta-text text-center">
                    <h4 style="color: #af251b">Zidrop Logistics </h4>
                </div>
            </div> -->
            <div class="col-lg-10 col-md-10 col-xs-12">
                <form action="{{url('/track/parcel/')}}" method="POST" class="form-row track_form">
                @csrf
                    <!-- <div class="row">
                        <div class="col-md-12"> -->
                            <div class="btn-group" role="group" style="width: 100%;">
                                <input type="text" name="trackparcel" class="form-control w-100" placeholder="Type your tracking number" required="" data-error="Please enter your tracking number">
                                <button type="submit" class="btn btn-common trace_book"><i class="fa fa-search search-icon"></i><span class="search-text">TRACK PARCEL</span></button>
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

<section id="cta" class="quicktech-traking for_mobile">
    <div class="container">
        <h2 class="text-center mt-5">Shipment Tracking</h2>
        <p class="text-center">Track your parcel</p>
        <div class="row home-track-inner justify-content-center">
            <!-- <div class="col-lg-6 col-md-6 col-xs-12" >
                <div class="cta-text text-center">
                    <h4 style="color: #af251b">Zidrop Logistics </h4>
                </div>
            </div> -->
            <div class="col-lg-10 col-md-10 col-xs-12">
                <form action="{{url('/track/parcel/')}}" method="POST" class="form-row track_form">
                @csrf

                    <nav> 
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fa fa-search" aria-hidden="true"></i> Track Order</button>
                            <a class="nav-link" id="nav-profile-tab" href="{{url('/p2p/')}}"  role="tab" aria-controls="nav-profile" aria-selected="false"> <i class="fa fa-car"></i> Book P2P</a>
                            
                        </div>
                    </nav>
                    <div class="tab-content mt-3" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="btn-group" role="group" style="width: 100%;">
                                <input type="text" name="trackparcel" class="form-control w-100" placeholder="Type your tracking number" required="" data-error="Please enter your tracking number">
                                <button type="submit" class="btn btn-common trace_book"><i class="fa fa-search search-icon"></i><span class="search-text">TRACK PARCEL</span></button>    
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"> Testing </div>
                    </div>

                   
                           
                </form>
            </div>                 
        </div>
    </div>
</section>


<?php 
if(isset($_GET['trackingCode'])){
    if($count !=0){ ?>

        <!--/ End Hero Section -->
        <!--Features Area -->
        <section class="service-accordion-section tracking">
            <div class="container pt-5 px-lg-3 px-md-0">
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="py-5 mx-auto">
                            <?php
                            foreach($parcel as $parcels){
                                $timestamp = strtotime($parcels->updated_at);
                                ?>
                                <table class="table">
                                    <tr>
                                        <td>Tracking Code</td>
                                        <td><?=$parcels->trackingCode;?></td>
                                    </tr>
                                    <tr>
                                        <td>Recipient Name</td>
                                        <td><?=$parcels->recipientName;?></td>
                                    </tr>
                                    <tr>
                                        <td>Recipient Address</td>
                                        <td><?=$parcels->recipientAddress;?></td>
                                    </tr>
                                    <tr>
                                        <td>Note</td>
                                        <td><?=$parcels->note;?></td>
                                    </tr>
                                    <tr>
                                        <td>Recipient Phone</td>
                                        <td><?=$parcels->recipientPhone;?></td>
                                    </tr>
                                    <tr>
                                        <td>Product Name</td>
                                        <td><?=$parcels->productName;?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            <?php 
                                                if($parcels->status ==1){
                                                    echo "PENDING";
                                                }elseif($parcels->status==2){
                                                    echo "IN TRANSIT";
                                                }elseif($parcels->status == 3){
                                                    echo 'OUT FOR DELIVERY';
                                                }elseif($parcels->status == 4){
                                                    echo 'DELIVERED';
                                                }elseif($parcels->status == 5){
                                                    echo 'DISPUTED PACKAGE';
                                                }elseif($parcels->status == 6){
                                                    echo 'PARTIAL DELIVERY';
                                                }elseif($parcels->status == 7){
                                                    echo 'RETURN TO ORIGIN HUB';
                                                }elseif($parcels->status == 8){
                                                    echo 'RETURN TO MERCHANT';
                                                }elseif($parcels->status == 9){
                                                    echo 'CANCELLED';
                                                }elseif($parcels->status == 10){
                                                    echo 'ARRIVED AT HUB';
                                                }elseif($parcels->status == 11){
                                                    echo '';
                                                }else{
                                                    echo 'PICKED UP';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Updated at</td>
                                        <td><?=date("d M Y h:i:s a", $timestamp);?> <??></td>
                                    </tr>
                                </table>
                               
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    <?php }else{
        echo "<p class='text-center'>Not found  any parcel. Please check Tracking Code</p>";
    }
}else{ ?>
    <!--/ End Hero Section -->
    <!--Features Area -->
    <section class="service-accordion-section tracking">
        <div class="container pt-5 px-lg-3 px-md-0">
            <div class="row justify-content-center">
                <div class="col-md-7 text-center">
                    <div class="py-5 mx-auto">
                        <h4>Interested to know more?</h4>
                        <p>If you are looking for a complete logistics solution for your brand don’t hesitate to get in touch with us. We are looking forward to answering any queries that you have regarding our services.</p>
                        <a class="btn-link" href="{{url('/contact-us/')}}">contact us</a>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
<?php } ?>


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