@extends('frontEnd.layouts.master')
@section('title','Password Reset Verify')
@section('content')
 <!-- Hero Area Start -->
<div class="breadcrumbs" style="background:#db0022;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="bread-inner">
                            <!-- Bread Menu -->
                            <div class="bread-menu">
                                <ul>
                                    <li><a href="{{url('/')}}">Home</a></li>
                                    <li><a href="">Password Reset</a></li>
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <form action="{{url('auth/merchant/reset/password')}}" method="POST">
                        @csrf
                          <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Verify PIN</label>
                                        <input class="form-control contact-formquickTechls {{ $errors->has('verifyPin') ? ' is-invalid' : '' }}" type="text" value="{{old('verifyPin')}}" placeholder="Verify Pin" name="verifyPin" required="" autocomplete="disadsabled">
                                         @if ($errors->has('verifyPin'))
                                            <span class="invalid-feedback">
                                              <strong>{{ $errors->first('verifyPin') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input class="form-control contact-formquickTechls {{ $errors->has('newPassword') ? ' is-invalid' : '' }}" type="password" value="{{old('newPassword')}}" placeholder="New Password" name="newPassword" required="" autocomplete="disdsadsaabled">
                                         @if ($errors->has('newPassword'))
                                            <span class="invalid-feedback">
                                              <strong>{{ $errors->first('newPassword') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                      <button type="submit" class="btn btn-common">Save Change</button>
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