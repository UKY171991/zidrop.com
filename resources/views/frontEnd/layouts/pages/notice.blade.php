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
                                    <li><a href="">Notice</a></li>
                                </ul>
                            </div>
                            
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
                                <h1>Notice</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($notices as $key=>$value)
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="quickTech-carrier-item">
                            <div class="content">
                                <div class="info-text">
                                    <h3><a href="#">{{$value->title}}</a></h3>
                                    <p>{{$value->created_at}}</p>
                                </div>
                                <p>{{$value->description}}</p>
                                <br />
                                <a href="{{url('notice/'.$value->id.'/'.$value->slug)}}" class="btn btn-border">Read More</a>
                            </div>
                        </div>
                        <!--Quicktech Carrier Item Ends -->
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--/ End quickTech-price -->


@endsection