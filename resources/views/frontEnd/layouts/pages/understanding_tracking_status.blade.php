@extends('frontEnd.layouts.master')
@section('title', 'Understanding Tracking Status')
@section('content')
    <style>
        .ups-componentt {
            background: linear-gradient(318.8deg, #DFDBD7 -11.42%, #F2F1EF 58.01%) !important;
            /* background-image: url("{{ asset('frontEnd/hero-bg.svg') }}"); */
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            padding-top: 150px;
            padding-bottom: 150px;

        }

        .section-space {
            padding: 10px 0;
        }

        .title {
            font-size: 2.5rem;
            font-weight: 400;
        }

        @media (min-width: 768px) {
            .ups-component.promo-teaser.slim-banner .card {
                padding: 1.5rem 2rem;
            }

            .ups-component.promo-teaser {
                padding-top: 2.5rem;
                padding-bottom: 2.5rem;
            }

            .ups-component.promo-teaser.slim-banner .card-body-content {
                font-size: 1rem;
                max-width: 72%;
            }

            .ups-cta {
                width: auto;
            }

            .ups-cta-primary {
                background-color: #af251b;
                color: #fff !important;
            }

            .ups-cta {
                position: relative;
                display: inline-block;
                vertical-align: middle;
                user-select: none;
                font-size: 1rem;
                font-weight: 500;
                line-height: 1.5;
                border-radius: 2.5rem;
                padding: .625rem 1.5rem;
                cursor: pointer;
                border: none;
                min-width: 100px;
                text-align: center;
                color: #fff !important;
                text-decoration: none;
                transition: all .35s ease-in-out;
                z-index: 1;
                width: 100%;
                margin-bottom: 1rem;
                margin-right: .75rem;
            }

        }
      

        .ups-container,
        .ups-component.anchor-links .anchor-content-container .back-to-top {
            padding: 0 1.5rem;
        }

        .ups-component.promo-teaser.bg-grey .card {
            background-image: linear-gradient(318.8deg, #DFDBD7 -11.42%, #F2F1EF 58.01%);
        }

        .ups-component.promo-teaser.full-bleed .card {
            position: relative;
            padding: 1.5rem;
            overflow: hidden;
            display: block;
        }

        .ups-component.promo-teaser .card {
            z-index: 1;
            transition: all 150ms ease-in-out;
        }

        .ups-card {
            border-radius: 0;
            border: 0;
            background-color: transparent;
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
        }

        .ups-cta span.icon {
            transition: transform .35s ease-in-out;
            transform: translateX(0);
            margin-left: .5rem;
            display: inline-block;
            font-size: 1rem;
        }

        .icon {
            font-family: "upsicons";
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            speak: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }


        .ups-icon-right-arrow::after {
            content: ">";
            top: 0;
            left: 0;
            font-size: 18px;
            /* Adjust size as needed */
            color: #fff !important;
            /* Adjust color as needed */
            font-weight: 700;
        }

        .card-text {
            font-size: 14px;
            padding-top: 10px;
            font-weight: 600;
        }

        .arc-container {
            position: static;
            max-height: 100%;
        }

        .ups-component.hero .arc-container .arc {
            width: 100vw;
            height: calc((100vw / 1440) * 72);
            min-height: 1.5rem;
        }

        svg {
            overflow: hidden;
            vertical-align: middle;
        }

        .ups-component.hero.no-image {
            min-height: auto;
        }

        .ups-component.hero.has-breadcrumbs {
            padding-top: 44px;
        }

        .ups-component.hero-default {
            background: linear-gradient(318.8deg, #DFDBD7 -11.42%, #F2F1EF 58.01%);
        }

        @media (min-width: 1600px) {

            .ups-component.hero {
                min-height: 500px;
            }
        }

        @media (min-width: 1280px) {

            .ups-component.hero {
                min-height: 450px;
            }
        }

        .ups-component.hero {
            position: relative;
            overflow: hidden;
        }

        .ups-component.hero.no-image .card {
            padding-bottom: 0;
        }

        @media (min-width: 1280px) {

            .ups-component.hero .card {
                padding-top: 2.5rem;
                padding-bottom: 0;
                gap: 0;
            }
        }

        @media (min-width: 768px) {

            .ups-component.hero .card {
                gap: 2rem;
                padding-bottom: 2rem;
                padding-top: 2rem;
                flex-direction: row-reverse;
            }
        }

        .ups-component.hero .card {
            flex-direction: column-reverse;
            padding-top: 1.5rem;
            padding-bottom: 2rem;
            z-index: 5;
        }

        .ups-card {
            border-radius: 0;
            border: 0;
            background-color: transparent;
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 0.25rem;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1.25rem;
        }

        .ups-component.hero.no-image .card-body-content {
            max-width: 800px;
            min-height: auto;
        }

        @media (min-width: 1280px) {

            .ups-component.hero .card-body-content {
                max-width: 488px;
                min-height: 242px;
            }
        }

        .ups-component.hero.no-image .arc-container {
            position: static;
            max-height: 100%;
        }

        @media (min-width: 768px) {

            .ups-component.hero .arc-container {
                max-height: calc((100vw / 1440) * 102);
                position: relative;
            }
        }

        .ups-component.hero .arc-container {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            color: #fff;
            z-index: 1;
            max-height: 200px;
        }

        .ups-component.hero .arc-container .arc {
            width: 100vw;
            height: calc((100vw / 1440) * 72);
            min-height: 1.5rem;
        }

        svg {
            overflow: hidden;
            vertical-align: middle;
        }

        @media (min-width: 1280px) {

            .ups-component.hero .card-body-content h1,
            .ups-component.hero .card-body-content h2 {
                font-size: 3rem;
            }
        }

        @media (min-width: 768px) {

            .ups-component.hero .card-body-content h1,
            .ups-component.hero .card-body-content h2 {
                font-size: 2.5rem;
            }
        }

        .ups-component.hero .card-body-content h1,
        .ups-component.hero .card-body-content h2 {
            font-weight: 400;
            line-height: 1.25;
            margin-bottom: 1.25rem;
            font-size: 2rem;
        }

        .ups-component.hero.no-image .card-body-content p:last-child {
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {

            .ups-component.hero .card-body-content p {
                font-size: 1.25rem;
            }
        }

        .card {
            background: transparent;
            border: none;
        }

        .col-md-6 {
            padding-left: 0px !important;
        }

        .status_describe_col {
            border-bottom: 2px solid #c7c5c5;
            padding-bottom: 10px !important;
        }

        .status_describe_col a {
            font-size: 20px;
            color: #0363ac;
        }

        .description_rows {
            margin-left: 20% !important;
            margin-right: 20% !important;
        }

        .title_underline {
            width: 100px;
            height: 5px;
            margin-top: 0px;
            background: #af251b;
            margin-top: 10px;

        }
          .header_text{
    margin: 0;
    color: #000;
    position: relative;
    font-family: 'Poppins', sans-serif;
    font-weight: 600 !important;
}
    </style>
    <!-- Breadcrumb -->
    <div class="breadcrumbs" style="background:#db0022;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <!-- Bread Menu -->
                        <div class="bread-menu">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="">Understanding Tracking Status</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / End Breadcrumb -->
    <div data-utg-onsite-ad-click="templatedata/content/hero/data/en_US/understanding-tracking-status-hero.dcr"
        data-utg-onsite-ad-position="1" class="ups-component has-breadcrumbs hero hero-default  no-image"
        data-utg-content-block-id="trn_hero">
        <div class="ups-container">
            <div class="card ups-card container">
                <div class="card-body fade-in-up-light ">
                    <div class="card-body-content ">
                        <h1 class="header_text">What Does My ZiDrop Tracking Status Mean?</h1>
                        <hr class="title_underline">
                        <p>Each time your package is scanned, shipment movement information is captured. Here's a list of
                            what
                            those common tracking statuses mean.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="arc-container"><svg xmlns="http://www.w3.org/2000/svg" width="1440" class="arc" height="72"
                viewBox="0 0 1440 72" fill="none" aria-hidden="true" focusable="false">
                <path d="M-400 176C139.222 -24.794 1028.42 -10.941 1440 13.8751V176L-400 176Z" fill="white"></path>
            </svg></div>
    </div>
    <div>
        <div class="ups-container">
            <div class=" ups-card container">
                <div class="card-body fade-in-up-light ">
                    <div class="card-body-content ">
                        <div class="row" style="margin-top: 20px; width: 100%;">
                            @foreach ($parcelTypeDescribes as $key => $value)
                                <div class="col-md-4 mt-5 ">
                                    <div class="status_describe_col ">
                                        <!-- Add anchor link with data attribute -->
                                        <a href="#" class="heading-link" data-target="{{ $value->id }}">
                                            {{ $value->parcelType->title }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <div class="row" style="margin-top: 20px; width: 100%;">
        <div class="col-md-3">

        </div>
        <div class="col-md-6">
           <div class="row">
            @foreach ($parcelTypeDescribes as $key => $value)
            <div class="" id="description_link{{ $value->id }}">
                <div class="col-md-12 mt-5 description">
                    <div class="section-title default text-left">
                        <h2 class="title">{{ $value->parcelType->title }}</h2>
                        <hr class="title_underline">
                    </div>
                    <p>{{ $value->description }}</p>
                </div>
            </div>
            @endforeach
           </div>
        </div>
        <div class="col-md-3">

        </div>
        
    </div>

    <div class="iw_component" id="iw_comp1631216335044">
        
        <div class="ups-component promo-teaser secondary-promo full-bleed bg-grey slim-banner"
            data-utg-content-block-id="snd_teas">
            <div class="ups-container container">
                <div class="card ups-card">

                    <div class="card-body">
                        <h1 class="">Can't Find What You're Looking For?</h1>
                        <div class="card-body-contet">
                            <div class="row">
                                <div class="col-md-9">
                                    <p class="card-text">We're here to help. Use our virtual assistant, or find the customer
                                        service route best suited for you.</p>
                                </div>
                                <div class="col-md-3">
                                    <p><a href="https://support.zidrop.com/guest/openticket" title="Contact UPS"
                                            class="ups-cta ups-cta-primary ups-analytics" data-utg-event-id="21"
                                            data-utg-link-type="link" data-utg-onsite-ad-position="1">Contact ZiDrop<span
                                                class="icon ups-icon-right-arrow" aria-hidden="true"></span></a></p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

    <!--/ End quickTech-price -->


@endsection

@section('custom_js_script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Smooth scroll to description when heading link is clicked
            $('.heading-link').click(function(e) {
                e.preventDefault(); // Prevent default link behavior
                var targetId = $(this).data('target'); // Get the ID of the target description
                var menuHeight = $('.middle-inner').outerHeight(); // Get the height of the sticky menu
                var targetOffset = $('#description_link' + targetId).offset().top -
                    menuHeight; // Adjust the target offset to account for the sticky menu
                $('html, body').animate({
                    scrollTop: targetOffset // Smoothly scroll to the target description
                }, 1000); // Adjust the duration of the animation as needed
            });
        });
    </script>

@endsection
