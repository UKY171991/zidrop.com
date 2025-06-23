@extends('frontEnd.layouts.master')
@section('title','Login')
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
                                    <li><a href="#">Log In</a></li>
                                </ul>
                            </div>
                            <!-- Bread Title -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / End Breadcrumb -->
        
<!-- Contact Us -->
<section class="contact-us">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8">                
                <div class="mobile-register">
                    <div class="mobile-register-text">
                        <h5>Login Now</h5>
                    </div>
                    
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="phone" role="tabpanel" aria-labelledby="phone-tab">
                            <div class="mobile-register-area">
                                <form action="{{url('merchant/login')}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="phoneOremail" required="" placeholder="01XXXXXXXXX" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" required="" placeholder="Password" />
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="submit">login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="email" role="tabpanel" aria-labelledby="email-tab">
                            <div class="mobile-register-area">
                                <form action="{{url('merchant/login')}}" method="POST" id="loginForm">
                                    @csrf                                    
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="phoneOremail" required="" placeholder="Email" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" required="" placeholder="Password" />
                                    </div>
                                    <div class="form-group">
                                        @if(config('google_captcha.site_key'))
                                            <div class="g-recaptcha"
                                                data-sitekey="{{config('google_captcha.site_key')}}">
                                            </div>
                                            @error('g-recaptcha-response')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="alert alert-danger" id="gcaptcha-error" style="display: none"></div>
                                        @endif
                                    </div>
                                    <div class="row">
                                    <div class="col-sm-6">
                                        <div class="rememberme text-danger">
{{--                                            <input type="checkbox" name="rememberme" id="rememberme"> <label for="rememberme"> Remember Me</label>--}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="{{url('merchant/forget/password')}}" class="text-danger">Forget Password</a>
                                    </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" class="submit">login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
    @include('frontEnd.layouts._notice_modal')
@endsection

@section('custom_js_script')
    <script>
        $(document).ready(function () {
            @if(!empty($globNotice))
                $('#globalNoticeModal').modal('show');
            @endif
        });
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        @if(config('google_captcha.site_key'))
            $("#loginForm").on('submit', function (e) {
                if (grecaptcha.getResponse() === '') {
                    event.preventDefault(); // Stop form from submitting
                    e.preventDefault();
                    $("#gcaptcha-error").html('Please complete the captcha');
                    $("#gcaptcha-error").show();
                    //alert('Please complete the reCAPTCHA.');
                }     
            });
        @endif
    </script>
@endsection