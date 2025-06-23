@extends('frontEnd.layouts.master')
@section('title','Password Forget')
@section('content')
 <section class="quicktech-lr-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="common-login">
                    <div class="row justify-content-center">
                        <div class="col-xl-12 col-lg-8">
                            <div class="section-title text-center mb-70">
                                <h1 class="wow fadeInUp" data-wow-delay=".4s"> Password Forget</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <form action="{{url('auth/deliveryman/password/reset')}}" method="post" class="contact-wthree-do">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input class="form-control contact-formquickTechls {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" value="{{old('email')}}" placeholder="Email Address" name="email" required="">
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
</section> 
@endsection