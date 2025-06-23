@extends('frontEnd.layouts.master')
@section('title','Forget Verify')
@section('content')
<section class="quicktech-lr-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="common-login">
                    <div class="row justify-content-center">
                        <div class="col-xl-12 col-lg-12">
                            <div class="section-title text-center mb-70">
                                <h1 class="wow fadeInUp" data-wow-delay=".4s">Agent Password Forget</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12 ">
                            <form action="{{url('auth/agent/reset/password')}}" method="POST">
                                    @csrf
                                    <div class="heading mb-lg-2 mb-2">
                                        <h3 class="head">Reset Forget Password</h3>
                                    </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input class="form-control contact-formquickTechls {{ $errors->has('verifyPin') ? ' is-invalid' : '' }}" type="text" value="{{old('verifyPin')}}" placeholder="Verify Pin" name="verifyPin" required="">
                                                 @if ($errors->has('verifyPin'))
                                                    <span class="invalid-feedback">
                                                      <strong>{{ $errors->first('verifyPin') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input class="form-control contact-formquickTechls {{ $errors->has('newPassword') ? ' is-invalid' : '' }}" type="password" value="{{old('newPassword')}}" placeholder="New Password" name="newPassword" required="">
                                                 @if ($errors->has('newPassword'))
                                                    <span class="invalid-feedback">
                                                      <strong>{{ $errors->first('newPassword') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                              <button type="submit" class="btn submit contact-submit mt-4">Savel Change</button>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 
@endsection