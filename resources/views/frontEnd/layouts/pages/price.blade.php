@extends('frontEnd.layouts.master')
@section('title','Charges')
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
                                    <li><a href="">Charges</a></li>
                                </ul>
                            </div>
                            <!-- Bread Title -->
                            <!--<div class="bread-title">-->
                            <!--    <h2>Charges - Spark Delivery</h2>-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / End Breadcrumb -->

       <!-- quickTech-price -->
        <section class="quickTech-price  section-space">
            <div class="container">
                <div class="row">
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
                </div>
                <div class="row">
                     @foreach($prices as $key=>$value)
                        <div class="col-lg-4 col-md-4 col-12">
                            <!-- Single quickTech-price -->
                            <div class="single-quickTech-price">
                                <div class="quickTech-price-head">
                                    <img src="{{asset($value->image)}}" alt="#">
                                    <div class="icon-bg">{{$value->price}} tk</div>
                                </div>
                                <div class="quickTech-price-content">
                                    <h4><a href="#">{{$value->name}}</a></h4>
                                    <p>{!!$value->text!!}</p>
                                    <a class="btn" href="#"><i class="fa fa-arrow-circle-o-right"></i>Book Now</a>
                                </div>
                            </div>
                            <!--/ End Single quickTech-price -->
                        </div>
                        @endforeach
                </div>
            </div>
        </section>
        <!--/ End quickTech-price -->


@endsection