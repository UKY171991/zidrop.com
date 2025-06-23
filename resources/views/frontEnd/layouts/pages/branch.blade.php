@extends('frontEnd.layouts.master') 
@section('title','Branch') 
@section('content')
<!-- Breadcrumb -->
<div class="breadcrumbs" style="background: #db0022;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <!-- Bread Menu -->
                    <div class="bread-menu">
                        <ul>
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><a href="">Branches</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->




@endsection