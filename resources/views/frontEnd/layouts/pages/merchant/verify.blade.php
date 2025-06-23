@extends('frontEnd.layouts.master')
@section('title','Login Verify')
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
                            <li><a href="#">Login Verify</a></li>
                        </ul>
                    </div>
                    <!-- Bread Title -->
                    <!--<div class="bread-title"><h2>Merchant Log In</h2></div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->

<!-- Contact Us -->
<section class="contact-us section-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-6 col-12">
                <!-- Contact Form -->
                <div class="contact-form-area m-top-30">
                    <h4>Login Verify</h4>
                    <form action="{{url('merchant/phone-verify')}}" method="post" class="form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-user"></i></div>
                                    <input type="number" name="verifyToken" placeholder="Verify Token">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group button">
                                    <button type="submit" class="quickTech-btn theme-2">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="{{url('merchant/phone-resend')}}" method="POST">
                        @csrf
                        <button type="submit" class="resend-button" style="margin-top:10px;"><i class="fa fa-repeat" aria-hidden="true" style="margin-right:5px;"></i>RESEND </button>
                    </form>
                </div>
                <!--/ End contact Form -->
            </div>
            
        </div>
    </div>
</section>  
<!--/ End Contact Us -->
@endsection