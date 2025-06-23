<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='copyright' content='pavilan'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Title Tag  -->
    <title>@yield('title', 'always on time')</title>

    @if (View::hasSection('meta_description'))
    <meta name="description" content="@yield('meta_description', 'ZiDrop | Nigeria’s Leading Logistics Company | As Quick as a Click')">
    @endif

    <?php 

        if ($_SERVER['REQUEST_URI'] === '/public/' || $_SERVER['REQUEST_URI'] === '/public') {
            header("Location: https://zidrop.com/", true, 301);
            exit;
        }

    ?>

    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon.png') }}">

    <!-- Web Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/animate.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/cubeportfolio.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/font-awesome.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/magnific-popup.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/owl-carousel.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/slicknav.min.css">
    <link rel="stylesheet" href="{{ asset('backEnd/') }}/dist/css/toastr.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.cdnfonts.com/css/roboto" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/reset.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/new_footer.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/counter.css">
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/responsive.css">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">


    <!-- Meta Pixel Code -->

    <script>
        ! function(f, b, e, v, n, t, s)

        {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?

                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };

            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';

            n.queue = [];
            t = b.createElement(e);
            t.async = !0;

            t.src = v;
            s = b.getElementsByTagName(e)[0];

            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',

            'https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '1836393513600149');

        fbq('track', 'PageView');

        
    </script>

    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1836393513600149&ev=PageView&noscript=1" /></noscript>

    <!-- End Meta Pixel Code -->
   
    @yield('styles')
    <style>
        .sticky-header {
            position: sticky;
            /* background-color: #ffffff96; */
            /* backdrop-filter: blur(10px); */
            top: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    
        .middle-header {
            position: relative;
            transition: none;
            box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.1);
            z-index: 1000;
            backdrop-filter: none;
        }
    
        .middle-header.scrolled {
            /* background-color: #ffffff96; */
            background: rgb(255 255 255 / .7);
            backdrop-filter: blur(10px);

        }

        /* Optional: Add media queries if needed for specific responsive adjustments */
        @media (max-width: 768px) { /* Example breakpoint for mobile */
            .middle-header {
                transition: none;
            }
        }

        @media (min-width: 769px) { /* Example breakpoint for desktop */
            .middle-header {
                transition: none;
            }
        }
    </style>
</head>

<body id="bg" style="">
    <!-- Boxed Layout -->
    <div id="page" class="site boxed-layout">
        <!-- Header -->
        <header class="header sticky-header" >
            <!-- Middle Header -->
            <div class="middle-header">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="middle-inner">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-12">
                                        <!-- Logo -->
                                        <div class="logo">
                                            <!-- Image Logo -->
                                            <div class="img-logo">
                                                <a href="{{ url('/') }}">
                                                    @foreach ($whitelogo as $wlogo)
                                                        <img src="{{ asset($wlogo->image) }}" alt="">
                                                    @endforeach
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mobile-nav"></div>
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-12">
                                        <div class="menu-area">
                                            <!-- Main Menu -->
                                            <nav class="navbar navbar-expand-lg">
                                                <div class="navbar-collapse">
                                                    <div class="nav-inner">
                                                        <div class="menu-home-menu-container">
                                                            <!-- Naviagiton -->
                                                            <ul id="nav" class="nav main-menu menu navbar-nav">
                                                                <li class="nav-item"><a
                                                                        href="{{ url('/') }}">Home</a></li>
                                                                <li class="nav-item custom-dropdown"><a> Services <i
                                                                            class="fa fa-angle-down"
                                                                            aria-hidden="true"></i></a>
                                                                    <ul class="custom-dropdown-menu">
                                                                        @foreach ($services as $key => $value)
                                                                            <li><a
                                                                                    href="{{ url('our-service/' . $value->id) }}"><i
                                                                                        class="fa {{ $value->icon }}"></i>{{ $value->title }}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                                <!-- <li class="nav-item"><a href="{{ url('price') }}">Charges</a></li> -->
                                                                <!-- <li><a href="{{ url('about-us') }}">About Us</a></li> -->
                                                                <!--<li class="nav-item"><a href="{{ url('merchant/register') }}">Pick & Drop</a></li>-->
                                                                <!--<li class="nav-item"><a href="{{ url('branches') }}">Branches</a></li>-->
                                                                <li class="nav-item"><a
                                                                        href="{{ url('tracking') }}">Tracking</a></li>

                                                                <!-- <li class="nav-item"><a href="{{ url('gallery') }}">Gallery</a></li> -->
                                                                <li class="nav-item"><a
                                                                        href="{{ url('notice') }}">Notice</a></li>
                                                                <li class="nav-item"><a
                                                                        href="{{ route('frontend.branches') }}">Hubs</a>
                                                                </li>
                                                                <li class="nav-item"><a
                                                                        href="{{ route('frontend.subscriptions') }}">Subscriptions</a>
                                                                </li>
                                                                {{-- <li class="nav-item"><a href="{{url('contact-us')}}">Contact Us</a></li> --}}
                                                                <div class="button">
                                                                    <a href="{{ url('merchant/register') }}"
                                                                        class="quickTech-btn register"><i
                                                                            class="fa fa-file-text-o me-1"></i>
                                                                        Register</a>
                                                                    <a href="{{ url('merchant/login') }}"
                                                                        class="quickTech-btn login"><i
                                                                            class="fa fa-user me-1"></i> Login</a>
                                                                    <a href="https://support.zidrop.com/"
                                                                        class="quickTech-btn login" target="_blank"><i
                                                                            class="fa fa-paper-plane-o me-1"></i>
                                                                        Submit Ticket</a>

                                                                </div>
                                                            </ul>
                                                            <!--/ End Naviagiton -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </nav>
                                            <!--/ End Main Menu -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ End Middle Header -->

        </header>
        <!--/ End Header -->




        @yield('content')

        <?php /*
        <!-- Footer -->
        <footer class="footer" style="background-image: url({{asset('frontEnd/images/footer.svg')}});">
            <!-- Footer Top -->
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Footer Links -->
                            <div class="single-widget f-link widget">
                                <h3 class="widget-title">Services</h3>
                                <ul>
                                    <li><a href="{{url('/')}}">Home Delivery</a></li>
                                    <li><a href="{{url('/')}}">Warehousing</a></li>
                                    <li><a href="{{url('/')}}">Pick and Drop</a></li>
                                </ul>
                            </div>
                            <!--/ End Footer Links -->
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Footer Links -->
                            <div class="single-widget f-link widget">
                                <h3 class="widget-title">Earn</h3>
                                <ul>
                                    <li><a href="{{url('/')}}">Become Merchant</a></li>
                                    <li><a href="{{url('/')}}">Become Rider</a></li>
                                    <li><a href="{{url('/')}}">Become Delivery Man</a></li>
                                </ul>
                            </div>
                            <!--/ End Footer Links -->
                        </div>
                        <div class="col-lg-2 col-md-6 col-12">
                            <!-- Footer Links -->
                            <div class="single-widget f-link widget">
                                <h3 class="widget-title">Company</h3>
                                <ul>
                                    <li><a href="{{url('about-us')}}">About Us</a></li>
                                    <li><a href="{{url('contact-us')}}">Contact us</a></li>
                                    <li><a href="{{url('/')}}">Our Goal</a></li>
                                                                        <li><a href="{{route('frontend.understanding-tracking-status')}}">Understanding Tracking Status</a></li>

                                </ul>
                            </div>
                            <!--/ End Footer Links -->
                        </div>

                        <div class="col-lg-4 col-md-6 col-12">
                            <!-- Footer Contact -->
                            <div class="single-widget footer_contact widget">
                                <h3 class="widget-title">Contact</h3>
                                <p>Don’t miss any updates of our Offer</p>
                                <div class="newsletter"  style="border-color: #0a0603;">
                                    <form action="" class="d-flex flex-nowrap">
                                        <div class="form-group h-100 m-0 p-2 w-100">
                                            <input type="email" placeholder="Email Address" class="form-control px-1 bg-transparent h-100 border-0 without-focus"/>
                                        </div>
                                        <button type="button" class=" btn font-20 font-light m-1" style="background-color: #0a0603;color:white">Subscribe</button>
                                    </form>
                                </div>
                            </div>
                            <!--/ End Footer Contact -->
                        </div>
                    </div>

                </div>
            </div>
            
            
            
            
            
        
            <!-- Copyright -->
            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="copyright-content">
                                <div class="img-logo text-left">
                                    <a href="{{url('/')}}">
                                    @foreach($whitelogo as $wlogo)
                                        <img src="{{asset($wlogo->image)}}" alt="">
                                    @endforeach
                                    </a>
                                </div>
                                
                                
                                <ul class="address-widget-list">
                                    <li class="footer-mobile-number" style="color: #0a0603;"><i class="fa fa-phone" style="color: #0a0603;"></i>{{ $contact_info->phone1 }}</li>
                                    <li class="footer-mobile-number" style="color: #0a0603;"><i class="fa fa-mobile-phone" style="color: #0a0603;"></i></i>{{ $contact_info->phone2 }}</li>
                                    <li class="footer-mobile-number" style="color: #0a0603;"><i class="fa fa-envelope" style="color: #0a0603;"></i> {{ $contact_info->email }}</li>
                                    <li class="footer-mobile-number" style="color: #0a0603;"><i class="fa fa-map-marker" style="color: #0a0603;"></i>{{ $contact_info->address }}</li>
                                </ul>
                                
                                
                            </div>
                        </div>
                        
                        <div class="col-sm-5">
                            <div class="align-items-center copyright-content d-flex justify-content-center">
                                <!-- Copyright Text -->
                                <p style="color: #0a0603;">© Copyright Zidrop Logistics 2021.</p>
                            </div>
                        </div>
               
                        
                        
                        <div class="col-sm-3">
                            <div class="align-items-center copyright-content d-flex justify-content-end">
                                
                                <ul class="social-widget-list">
                                    @foreach($socialmedia as $key=>$value)
                                    <li class="footer-mobile-number"><a href="{{$value->link}}"><i class="{{$value->icon}}"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--/ End Copyright -->
            
            
            
            
   

        </footer>

        */
        ?>


        <!-- New footer started  -->

        <div class="container-fluid footer bg-dark py-5 wow fadeIn" data-wow-delay="0.2s"
            style="visibility: visible; animation-delay: 0.2s; animation-name: fadeIn;">
            <div class="container py-5">
                <div class="row g-5 mb-5 align-items-center">
                    <div class="col-lg-7">
                        <div class="position-relative mx-auto">
                            <input class="form-control w-100 py-3 ps-4 pe-5" type="text"
                                placeholder="Email address to Subscribe" fdprocessedid="eazn2d">
                            <button type="button"
                                class="btn btn-primary position-absolute top-0 end-0 py-2 px-4 mt-2 me-2"
                                fdprocessedid="4f9znp">Subscribe</button>
                        </div>
                    </div>
                    <div class="col-lg-5 mt-4">
                        <?php
                        use Illuminate\Support\Facades\DB;
                        $socialMediaLinks = DB::table('socialmedia')->where('status', 1)->get();
                        ?>
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-end">
                            @foreach ($socialMediaLinks as $social)
                                @if (!empty($social->link))
                                    <a class="btn btn-light btn-md-square me-3" href="{{ $social->link }}"><i
                                            class="{{ $social->icon }}"></i></a>
                                @endif

                                <!-- <a class="btn btn-light btn-md-square me-3" href="{{ $social->link }}"><i class="fa fa-facebook-f"></i></a>
                        <a class="btn btn-light btn-md-square me-3" href="{{ $social->icon }}"><i class="fa fa-twitter"></i></a>
                        <a class="btn btn-light btn-md-square me-3" href="{{ $social->icon }}"><i class="fa fa-instagram"></i></a>
                        <a class="btn btn-light btn-md-square me-0" href="{{ $social->icon }}"><i class="fa fa-linkedin"></i></a> -->
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-3 mb-5">
                        <div class="footer-item d-flex flex-column">
                            <div class="footer-item">
                                <h3 class="text-white mb-4"><i class="fa fa-bolt text-primary me-3"></i>ZiDrop</h3>
                                <p class="mb-3" style="color: #666; font-weight: 400;">Our guiding principle is "As
                                    Quick as a Click." Manage, monitor, and supervise deliveries in real-time with the
                                    ZiDrop Application.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3 mb-5">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Quick Links</h4>
                            <a href="{{ url('/') }}"> Home</a>
                            <a href="{{ url('about-us') }}"> About us</a>
                            <a href="{{ url('contact-us') }}"> Contact Us</a>
                            <a href="{{ url('contact-us') }}"> Our Goal</a>
                            <a href="{{ route('frontend.understanding-tracking-status') }}"> Understanding Tracking
                                Status</a>
                            <!-- <a href="{{ url('tracking') }}"> Tracking</a> -->

                            <a href="{{ url('gallery') }}">Gallery</a>

                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-xl-3 mb-5">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Earn</h4>
                            <a href="{{ url('/') }}"> Become Merchant</a>
                            <a href="{{ url('/') }}"> Become Rider</a>
                            <a href="{{ url('/') }}"> Become Delivery Man</a>
                        </div>
                    </div>

                    <?php
                    
                    $contactInfos = DB::table('contacts')->get();
                    ?>
                    <div class="col-md-6 col-lg-6 col-xl-3 mb-5">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Contact Info</h4>
                            @foreach ($contactInfos as $info)
                                <a href="#"><i class="fa fa-map-marker text-primary me-2"></i>
                                    {{ $info->address }}</a>
                                <a href="mailto:{{ $info->email }}"><i class="fa fa-envelope text-primary me-2"></i>
                                    {{ $info->email }}</a>
                                <!-- <a href="mailto:info@zidrop.com"><i class="fa fa-envelope text-primary me-2"></i> info@zidrop.com</a> -->
                                <a href="tel:{{ $info->phone1 }}"><i class="fa fa-phone text-primary me-2"></i>
                                    {{ $info->phone1 }}</a>
                                <a href="tel:{{ $info->phone2 }}" class="mb-3"><i
                                        class="fa fa-print text-primary me-2"></i> {{ $info->phone2 }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-md-0">
                        <span class="text-body"><a href="#" class="border-bottom text-white"><i
                                    class="fa fa-copyright text-light me-2"></i>ZiDrop Express Alliance Limited</a>,
                            All right reserved.</span>
                    </div>
                    <div class="col-md-6 text-center text-md-end text-body">
                        Designed By <a class="border-bottom text-white" href="{{ url('/') }}"> ZiDrop Team</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- New footer end -->




        <!-- Jquery JS -->
        <script src="{{ asset('frontEnd/') }}/js/jquery.min.js"></script>
        <script src="{{ asset('frontEnd/') }}/js/jquery-migrate-3.0.0.js"></script>
        <!-- Popper JS -->
        <script src="{{ asset('frontEnd/') }}/js/popper.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="{{ asset('frontEnd/') }}/js/bootstrap.min.js"></script>
        <!-- Modernizr JS -->
        <script src="{{ asset('frontEnd/') }}/js/modernizr.min.js"></script>
        <!-- ScrollUp JS -->
        <script src="{{ asset('frontEnd/') }}/js/scrollup.js"></script>
        <!-- FacnyBox JS -->
        <script src="{{ asset('frontEnd/') }}/js/jquery-fancybox.min.js"></script>
        <!-- Cube Portfolio JS -->
        <script src="{{ asset('frontEnd/') }}/js/cubeportfolio.min.js"></script>
        <!-- Slick Nav JS -->
        <script src="{{ asset('frontEnd/') }}/js/slicknav.min.js"></script>
        <!-- Slick Slider JS -->
        <script src="{{ asset('frontEnd/') }}/js/owl-carousel.min.js"></script>
        <!-- Easing JS -->
        <script src="{{ asset('frontEnd/') }}/js/easing.js"></script>
        <!-- Magnipic Popup JS -->
        <script src="{{ asset('frontEnd/') }}/js/magnific-popup.min.js"></script>
        <!-- Active JS -->
        <script src="{{ asset('frontEnd/') }}/js/counter.js"></script>
        <script src="{{ asset('frontEnd/') }}/js/active.js"></script>
        <script src="{{ asset('backEnd/') }}/dist/js/toastr.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $('.select2').select2();
        </script>
      
        <script>
            // Comma Seperate Numeric Value 
            // Select all elements with the class 'CommaSeperateValueSet'
            const productValueInputs = document.getElementsByClassName('CommaSeperateValueSet');

            // Loop through each element and add an event listener
            Array.from(productValueInputs).forEach((productValueInput) => {
                productValueInput.addEventListener('input', function(e) {
                    let value = e.target.value;

                    // Remove any non-numeric characters, including commas
                    value = value.replace(/,/g, '');

                    // Format the number with commas
                    const formattedValue = Number(value).toLocaleString('en-US');

                    // Update the input field
                    e.target.value = formattedValue;
                });
            });

            function convertCommaSeparatedToNumber(value) {
                if (!value) return 0; // Return 0 for empty or undefined values
                // Convert the value to a string, remove commas, and parse as a number
                return Number(String(value).replace(/,/g, '')) || 0;
            }
        </script>

        {!! Toastr::message() !!}


        <script src="{{ asset('js/common.js') }}"></script>
        @yield('custom_js_script')

        <script>
           document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.middle-header');
            const scrollPosition = window.scrollY;

            if (scrollPosition > 30) { // Adjust scroll threshold as needed
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    });
        </script>
</body>


</html>
<!-- Messenger Chat Plugin Code -->
<div id="fb-root"></div>

<!-- Your Chat Plugin code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>

<script>
    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "109961004701121");
    chatbox.setAttribute("attribution", "biz_inbox");

    window.fbAsyncInit = function() {
        FB.init({
            xfbml: true,
            version: 'v11.0'
        });
    };

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5974a3d50d1bb37f1f7a5788/1fl29dl6p';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->