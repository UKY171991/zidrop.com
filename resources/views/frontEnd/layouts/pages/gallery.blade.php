@extends('frontEnd.layouts.master')
@section('title','Gallery')
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
                                <li><a href="">Gallery</a></li>
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
    <section class="quickTech-price  section-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                    <div class="section-title default text-center">
                        <div class="section-top">
                            <h1>Gallery</h1>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($galleries as $key=>$value)
                <div class="col-lg-6 col-md-4 col-xs-12 info">
                    <div class="gal-wrapper" >
                        <div class="img">
                            <img class="img-fluid" src="{{$value->image}}" alt="" />
                        </div>
                        <div class="site-heading">
                            <p class="mb-3">{{$value->title}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection