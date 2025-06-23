@extends('frontEnd.layouts.master')
@section('title','Forget Password')
@section('content')
<div class="breadcrumbs" style="background:#db0022;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="bread-inner">
                            <!-- Bread Menu -->
                            <div class="bread-menu">
                                <ul>
                                    <li><a href="{{url('/')}}">Home</a></li>
                                    <li><a href="">Forget Password</a></li>
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / End Breadcrumb -->
 <!-- Hero Area End -->
    <div class="section-auth-common section-padding bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <div class="modal-content">
                    <div class="row">
            <div class="col-sm-6">
                <div class="auth-left">
                    <img src="{{asset('public/frontEnd/images/login.png')}}" alt="">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="auth-common-right">
                    

                <div class="form-content">
                    <h4>Forget Password</h4>
                    <p>Welcome back, please login to your account.</p>
                    <form action="{{url('auth/merchant/password/reset')}}" method="post" class="contact-wthree-do">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input class="form-control contact-formquickTechls {{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" value="{{old('email')}}" placeholder="Email" name="email" required="">
                                     @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                          <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-cont-quicktech btn-block mt-2">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</div> 
    @endsection