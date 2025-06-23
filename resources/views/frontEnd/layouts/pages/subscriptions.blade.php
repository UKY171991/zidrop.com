@extends('frontEnd.layouts.master')
@section('title', 'Get fast shipping – at an unbeatable price, every time | ZiDrop Logistics')

@section('styles')
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/packages.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
     --}}

     <style type="text/css">

/*

body, h1, h2, h3, h4, p {
  font-family: 'Poppins', sans-serif;
  font-weight: 400;
  color: #333;
}

h1{
   font-weight: 600; 
   font-size: 2.5rem;
}


.feature-icon {
  font-size: 24px;
  color: #ff5722; 
}

.pricing-plan {
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
  padding: 30px;
}

.pricing-plan .price {
  font-size: 28px;
  font-weight: 700;
  color: #2c2c2c;
}

.pricing-plan .most-popular {
  background: #5e4bff;
  color: white;
  font-size: 12px;
  padding: 5px 10px;
  border-radius: 20px;
  margin-bottom: 10px;
  display: inline-block;
}


.btn-primary {
  background-color: #c82333;
  border: none;
  border-radius: 6px;
  transition: all 0.3s ease-in-out;
}

.btn-primary:hover {
  background-color: #a71d2a;
}


h2, .section-title {
  font-weight: 600;
  margin-bottom: 20px;
}

.section-padding {
  padding: 60px 20px;
}

p {
    color: #666;
    margin: 0;
    line-height: 24px;
}

*/
     </style>
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
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="">Subscription</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / End Breadcrumb -->

    <!-- quickTech-price -->
    <section class="quickTech-price pt-5 pb-5 text-center">
        <div class="container text-center mt-5 mb-2">
            <div class="row text-center">
                <div class="col-md-12">
                    <h1 class="pk_heding">Get fast shipping – at an unbeatable price, every time.</h1>
                    <p class="pk_text">Select the right shipping plan to scale your online business with speed and confidence.</p>
                </div>
            </div>
        </div>

        <div class="pricing-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="pricing-card">
                        <h3 class="card-title text-left">Business Starter</h3>
                        <div class="price-container text-left">
                            <div class="mb-3">
                            </div>
                            <div class="price-block text-left">
                                <span class="currency">₦</span>
                                <span class="amount">20,000</span>
                                <span class="duration">/mo</span>
                                <span class="pk_crd_sh">Save 10%</span>
        
                            </div>
                        </div>
        
                        <a class="choose-plan-button btn_emp" href="{{url('/merchant/register')}}">Choose Plan</a>
                        <p class="renewal-info">Renews at NGN 20,000/mo. Cancel anytime.</p>
                        <hr class="hr_line">
                        <ul class="feature-list">
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text "><b>10%</b>
                                    discount on shipping charges</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text">Priority Shipping</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text">No Charges on COD (Cash on
                                    Delivery)</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark">Free Insurance cover</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark">A dedicated account
                                    officer</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark">Free Bulk Pick Up for
                                    Interstate delivery</span>
                            </li>
                            <li class="feature-item ">
                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark">
                                    E-commerce business growth kit</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark"> Facebook
                                    Ad Troubleshooting Help</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark"> Media
                                    Visibility and business growth</span>
                            </li>
                            <li class="feature-item text-left">
                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark"> Free
                                    Reverse logistics (handling returns and exchanges)</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 pricing-wrapper">
                    <div class="popular-badge" width="100%">MOST POPULAR</div>
                    <div class="pricing-card most-popular" width="100%">
                        <h3 class="card-title text-left pt-1">Business Premium</h3>
                        <div class="price-container text-left">
                            <div class="mb-3">
                            </div>
                            <div class="price-block text-left">
                                <span class="currency">₦</span>
                                <span class="amount">199,000</span>
                                <span class="duration">/mo</span>
                                <span class="pk_crd_sh">Save 20%</span>
                            </div>
                        </div>
        
                        <a class="choose-plan-button primary" href="{{url('/merchant/register')}}">Choose Plan</a>
                        <p class="renewal-info">Renews at NGN 199,000/mo. Cancel anytime.</p>
                        <hr class="hr_line">
                        <ul class="feature-list text-left">
        
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text "><b>20%</b>
                                    discount on shipping charges</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text">Priority Shipping</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text">No Charges on COD (Cash on
                                    Delivery)</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text">Free Insurance cover</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text">A dedicated account
                                    officer</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text">Free Bulk Pick Up for
                                    Interstate delivery</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text"> E-commerce business growth
                                    kit</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text"> Facebook Ad Troubleshooting
                                    Help</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text"> Media Visibility and
                                    business growth</span>
                            </li>
                            <li class="feature-item">
                                <span class="feature-icon">&#10004;</span> <span class="feature-text"> Free Reverse logistics
                                    (handling returns and exchanges)</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
       

        
        </div>
        <div>
            <p class="simple_pera">
                All plans are billed upfront. The listed rate reflects the monthly cost within your chosen plan.
            </p>
        </div>
        <div class="extra-benefits-section">
            <div class="benefits-text">
                Enjoy all this. At no extra cost.
            </div>
            <ul class="benefits-list">
                <li class="benefit-item">
                    <div class="benefits_icon">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>

                    <span class="benefit-label">Real-Time Tracking</span>
                </li>
                <li class="benefit-item">
                    <div class="benefits_icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <span class="benefit-label">Fast Delivery</span>
                </li>

                <li class="benefit-item">
                    <div class="benefits_icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <span class="benefit-label">SMS & Email Alerts</span>
                </li>
                <li class="benefit-item">
                    <div class="benefits_icon">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <span class="benefit-label">Packaging Services</span>
                </li>
                <li class="benefit-item">
                    <div class="benefits_icon">
                        <i class="fa-solid fa-naira-sign"></i>
                    </div>
                    <span class="benefit-label">Next Day Remittance</span>
                </li>

                <li class="benefit-item">
                    <div class="benefits_icon">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <span class="benefit-label">24/7 customer support</span>
                </li>
            </ul>
        </div>

    </section>

@endsection
@section('custom_js_script')
    <script>
        $(document).ready(function() {
            // Event listener for BankPosterBTN click
            $(document.body).on('click', '.BankPosterBTN', function(e) {
                e.preventDefault();

                // Get the poster image path from data attribute
                var posterPath = $(this).data('poster');

                if (posterPath) {
                    // Set the href and src attributes for the anchor and image
                    $('#PosterImageA').attr('href', posterPath);
                    $('#PosterImage').attr('src', posterPath);

                    // Show the Bootstrap modal
                    $('#PaymentPosterModal').modal('show');
                } else {
                    alert('No poster available');
                }
            });
        });
    </script>
@endsection
