@extends('frontEnd.layouts.master')
@section('title','Blog Details')
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
                            <li><a href="">Blog</a></li>
                        </ul>
                    </div>
                    <!-- Bread Title -->
                    <div class="bread-title">
                        <h2>Blog Details</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->

<!-- Blog Single -->
<section class="news-area archive blog-single section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="blog-single-main">
                            <div class="main-image">
                                <img src="{{asset($blogdetails->image)}}" alt="#">
                            </div>
                            <div class="blog-detail">
                                <!-- News meta -->
                                
                                <h2 class="blog-title">{{$blogdetails->title_en}}</h2>
                                <p>{{$blogdetails->text_en}}</p>
                                
                            </div>
                        </div>
                    </div>
                </div>
                                        
            </div>      
        </div>
    </div>
</section>  
<!--/ End Services -->
@endsection