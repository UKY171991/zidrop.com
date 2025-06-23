@extends('frontEnd.layouts.master')
@section('title','Notice Details')
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
                            <li><a href="">Our Services</a></li>
                        </ul>
                    </div>
                    <!-- Bread Title -->
                    <!--<div class="bread-title">-->
                    <!--    <h2>Key Features</h2>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->

<!-- Features Single Area -->

<section class="about-us section-space">
    <div class="container">
        <div class="about-content section-title default text-left">
            <div class="section-top">
                <h1><span>Service Detail</span><b>{{$feature->title}} </b></h1>
            </div>
            <div class="section-bottom">
                <div class="text">
                    <p>{{$feature->text}}</p>
                </div>

            </div>
        </div>
    </div>
</section>

<!--/ End Features Single Area -->
@endsection