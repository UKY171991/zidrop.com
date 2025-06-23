@extends('frontEnd.layouts.master')
@section('title','Notice')
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
                            <li><a href="">Our services</a></li>
                        </ul>
                    </div>
                    <!-- Bread Title -->
                    <!--<div class="bread-title">-->
                    <!--    <h2>Our services</h2>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->

<!-- Features Area -->
<section class="features-area section-bg">
    <div class="container">
    <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                <div class="section-title default text-center">
                    <div class="section-top">
                        <h1><span>Our</span><b>Services</b></h1>
                    </div>
                    <div class="section-bottom">
                        <div class="text">
                            <p>We Love to Serve Delightful Experience </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($features as $key=>$value)
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Single Feature -->
                <div class="single-feature">
                    <div class="icon-head"><i class="fa {{$value->icon}}"></i></div>
                    <h4><a href="{{url('/features/details/'.$value->id)}}">{{$value->title}} </a></h4>
                    <p>{{Str::limit($value->subtitle,140)}}</p>
                    <div class="button">
                        <a href="{{url('/features/details/'.$value->id)}}" class="quickTech-btn"><i class="fa fa-arrow-circle-o-right"></i>More detail</a>
                    </div>
                </div>
                <!--/ End Single Feature -->
            </div>
            @endforeach
        </div>
    </div>
</section>
<!--/ End Features Area -->
@endsection