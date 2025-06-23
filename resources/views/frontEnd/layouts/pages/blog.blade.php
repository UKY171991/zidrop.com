@extends('frontEnd.layouts.master')
@section('title','Blog')
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
                            <h2>Blog</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / End Breadcrumb -->

       <!-- Blog  -->
    <section class="blog-layout news-default section-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                    <div class="section-title default text-center">
                        <div class="section-top">
                            <h1><span>Latest</span><b> Blog</b></h1>
                        </div>
                        <div class="section-bottom">
                            <div class="text">
                                <p>Lorem Ipsum Dolor Sit Amet, Conse Ctetur Adipiscing Elit, Sed Do Eiusmod Tempor Ares Incididunt Utlabore. Dolore Magna Ones Baliqua</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                @foreach($blogs as $key=>$value)
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Single Blog -->
                    <div class="single-news ">
                        <div class="news-head overlay">
                            <img src="{{asset($value->image)}}" alt="#">

                        </div>
                        <div class="news-body">
                            <div class="news-content">
                                <h3 class="news-title"><a href="{{url('blog/details/'.$value->id)}}">{{$value->title_en}}</a></h3>
                                <div class="news-text">
                                    <p>{{Str::limit($value->text_en,50)}}</p>
                                </div>
                                <a href="{{url('blog/details/'.$value->id)}}" class="more">বিস্তারিত জানুন... <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <!--/ End Single Blog -->
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12">
                    <!-- Pagination -->
                    <div class="pagination-plugin">
                        <ul class="pagination-list">
                           {{$blogs->links()}} 
                        </ul>
                    </div>
                    <!--/ End Pagination -->
                </div>
            </div>
        </div>
    </section>
@endsection