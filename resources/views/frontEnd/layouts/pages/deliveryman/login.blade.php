@extends('frontEnd.layouts.master')
@section('title','Supporting Agent Login')
@section('content')
<section class="quicktech-lr-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="common-login">
                    <div class="row justify-content-center">
                        <div class="col-xl-12 col-lg-12">
                            <div class="section-title text-center mb-70">
                                <h1 class="wow fadeInUp" data-wow-delay=".4s">Supporting Deliveryman Login</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <form action="{{url('auth/deliveryman/login')}}" method="post">
                               @csrf
                                 <div class="row">

                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <input type="text" placeholder="Email Address " id="email" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="email" required data-error="Please enter your Email">
                                             <div class="help-block with-errors"></div>
                                              @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                  <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                              @endif
                                         </div>
                                     </div>

                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <input type="password" placeholder="Password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required data-error="Please enter your Password">
                                             <div class="help-block with-errors"></div>
                                             @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                  <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                              @endif
                                         </div>
                                     </div>
                                     <div class="col-sm-6">
                                         <div class="rememberme text-danger">
                                             <input type="checkbox" name="rememberme" id="rememberme"> <label for="rememberme"> Remember Me</label>
                                         </div>
                                     </div>
                                     <div class="col-sm-6 text-right">
                                         <a href="{{url('deliveryman/forget/password')}}" class="text-danger">Forget Password</a>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="submit-button text-left mt-3">
                                             <button class="btn btn-common btn-success" id="form-submit" type="submit">Login Now</button>
                                             <div id="msgSubmit" class="h3 text-center hidden"></div>
                                             <div class="clearfix"></div>
                                         </div>
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
