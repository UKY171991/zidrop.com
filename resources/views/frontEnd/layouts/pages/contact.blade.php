@extends('frontEnd.layouts.master')
@section('title','Contact Us')
@section('content')

<style type="text/css">
    #sent_mail {
        position: absolute;
        top: 3.5px;
        right: 6px !important;
        margin: 2px;
        font-size: .8rem;
        z-index: 100;
        top: -65px;
    }
</style>
<!-- Breadcrumb -->
<div class="breadcrumbs" style="background:#db0022;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <!-- Bread Menu -->
                    <div class="bread-menu">
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li><a href="">Contact  Us</a></li>
                        </ul>
                    </div>
                    <!-- Bread Title -->
                    <!--<div class="bread-title"><h2>Contact Us</h2></div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->

<!-- Contact Us -->
<section class="contact-us section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-12">
                <!-- Contact Form -->
                <div class="contact-form-area m-top-30">
                    <h4>Get In Touch</h4>
                    <form class="form" method="post" action="" id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-envelope"></i></div>
                                    <input type="email" id="email_data" name="email" placeholder="Enter your mail address" required value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                  <button type="button" class="btn btn-primary" id="sent_mail" style="right: 0px;color: white !important;background-color: #af251b;border: 2px solid #af251b !important;border-radius: 4px !important;">Get OTP </button>
                                </div>
                                
                            </div>

                            <div class="col-lg-12 col-md-12 col-12">
                                <div id="emailError"></div>
                            </div>
                            
                            <div class="col-lg-12 col-md-12 col-12">
                                  <div class="form-group">
                                    <input type="tel" name="otp" id="otp_val" class="form-control" pattern="[0-9]{6}" placeholder="OTP" readonly required value="" />
                                  </div>
                            </div>

                            

                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-user"></i></div>
                                    <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" readonly required value="{{ old('first_name') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-user"></i></div>
                                    <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" readonly required value="{{ old('last_name') }}">
                                </div>
                            </div>
                            

                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-phone"></i></div>
                                    <input type="text" name="phone" id="phone" placeholder="Enter your phone number" class="form-control" readonly required value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-tag"></i></div>
                                    <input type="text" name="subject" id="subject" placeholder="Type Subjects" class="form-control" readonly required value="{{ old('subject') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group textarea">
                                    <div class="icon"><i class="fa fa-pencil"></i></div>
                                    <textarea type="textarea" class="form-control" name="message" id="message" rows="5"  readonly required>{{ old('message') }}</textarea>
                                </div>
                            </div>
                            @if(config('google_captcha.site_key'))
                                <div class="col-12 mt-3">
                                    <div class="g-recaptcha"
                                         data-sitekey="{{config('google_captcha.site_key')}}">
                                    </div>
                                    @error('g-recaptcha-response')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="alert alert-danger" id="gcaptcha-error" style="display: none"></div>
                                </div>
                            @endif
                            <div class="col-12">
                                <div class="form-group button">
                                    <button type="submit" id="dubmit_data" class="quickTech-btn theme-2" disabled>Send Now</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--/ End contact Form -->
            </div>
            <div class="col-lg-5 col-md-5 col-12">
                <div class="contact-box-main m-top-30">
                    <div class="contact-title">
                        <h2>Contact with us</h2>
                        <p>{{ $contact_info->address }}</p>
                    </div>
                    
                    <!-- Single Contact -->
                    
                    <div class="single-contact-box">
                        <div class="c-icon"><i class="fa fa-phone"></i></div>
                        <div class="c-text">
                            <h4>Call Us Now</h4>
                            <p>{{ $contact_info->phone1 }}<br></p>
                        </div>
                    </div>                    
                    
                    <div class="single-contact-box">
                        <div class="c-icon"><i class="fa fa-mobile-phone"></i></div>
                        <div class="c-text">
                            <h4>Call Us Now</h4>
                            <p>{{ $contact_info->phone2 }}<br></p>
                        </div>
                    </div>
                    <!--/ End Single Contact -->
                    <!-- Single Contact -->
                    <div class="single-contact-box">
                        <div class="c-icon"><i class="fa fa-envelope-o"></i></div>
                        <div class="c-text">
                            <h4>Email Us</h4>
                            <p> {{ $contact_info->email }}</p>
                        </div>
                    </div>
                    <!--/ End Single Contact 
                    <iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=200&amp;height=400&amp;hl=en&amp;q=536, Shamim Sharani&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>-->
                </div>
            </div>
        </div>
    </div>
</section>  
<!--/ End Contact Us -->

@endsection
@section('custom_js_script')
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        {{--@if(config('google_captcha.site_key'))
            $("#contactForm").on('submit', function (e) {
                e.preventDefault();
                let url = "{{ route('frontend.contact-us.validate') }}";
                let captcha = $("#g-recaptcha-response").val();
                let _token = "{{ csrf_token() }}";
                let data  = $("#contactForm").serialize();

                console.log(data);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (result) {
                        if(result.status == 200) {
                            e.currentTarget.submit();
                        } else {
                            $("#gcaptcha-error").html('Please complete the captcha');
                            $("#gcaptcha-error").show();
                        }
                    },
                    error: function () {
                        $("#gcaptcha-error").html('Please complete the captcha');
                        $("#gcaptcha-error").show();
                    }
                });
            });
        @endif--}}

    $(document).on("contextmenu", function(event) {
         event.preventDefault(); // Prevent the default right-click context menu
         //alert("Right-click is disabled on this page.");
     });

$(document).ready(function() {
    $("#sent_mail").click(function(event) {
        event.preventDefault(); // Prevent default form submission
        var email = $('#email_data').val().trim(); // Get the email input value and trim spaces
        
        if (email !== '') {
            $.ajax({
                url: "{{ url('front/register-otp') }}", // Adjust this URL as needed
                type: 'GET',
                data: { email: email },
                success: function(res) {
                 // console.log(res.message);
                  if(res.message !=''){
                    //$('#emailError').html("<span style='color:green'>"+ res.message +"</span>"); // Show error message

                    $('#emailError').html("<div class='alert alert-warning' role='alert'><span style='color:#664d03'><i class='fa fa-envelope-o' aria-hidden='true'></i> A One-Time Password (OTP) has been sent to your Entered email address. Please enter the OTP below to proceed with your submit form.</span></div>"); // Show error message
                    $("#otp_val").removeAttr("readonly");
                    $("#sent_mail").html('Resend');
                  }else{
                    $('#emailError').html("<div class='alert alert-danger' role='alert'><span style='color:#664d03'>Please enter a valid email address.</span></div>");
                    $('#emailError').html('<span style="color:red">Failed to process response. Please try again.</span>'); // Show error message

                  }     
                },
            });
        } else {
          $('#emailError').html("<div class='alert alert-danger' role='alert'><span style='color:#664d03'>Please enter a valid email address.</span></div>"); // Show error message
        }
    });

    $("#otp_val").change(function(event) {
        var email = $('#email_data').val();//.trim(); // Trim email input value
        var otp_val = $('#otp_val').val();//.trim(); // Trim OTP input value
        
        // Only proceed if the email is not empty and OTP is entered
        if (email !== '' && otp_val.length === 6) {
            $.ajax({
                url: "{{ url('front/register-verifyOtp') }}", // Ensure this URL is correct
                type: 'GET',
                data: { email: email, otp: otp_val },
                success: function(res) { // Corrected 'succsess' to 'success'
                    if (res.status === 'true') {
                        //$("#otp_val").removeAttr("readonly"); // Remove readonly if OTP is valid
                        $('#emailError').html("<div class='alert alert-success' role='alert'><span style='color:#664d03'>OTP verified successfully.</span></div>");
                        $("#first_name").removeAttr("readonly");
                        $("#last_name").removeAttr("readonly");
                        $("#phone").removeAttr("readonly");
                        $("#subject").removeAttr("readonly");
                        $("#message").removeAttr("readonly");
                        $("#dubmit_data").removeAttr("disabled");
                    } else {
                      $('#emailError').html("<div class='alert alert-danger' role='alert'><span style='color:#664d03'>" + res.message + "</span></div>");
                    }
                },
                error: function(xhr, status, error) {
                    //console.error('Error during AJAX request:', error);
                    $('#emailError').html("<div class='alert alert-danger' role='alert'><span style='color:#664d03'>An error occurred while verifying the OTP.</span></div>");
                }
            });
        } else if (otp_val.length !== 6) {
          $('#emailError').html("<div class='alert alert-danger' role='alert'><span style='color:#664d03'>Please enter a valid 6-digit OTP.</span></div>");
        } else {
          $('#emailError').html("<div class='alert alert-danger' role='alert'><span style='color:#664d03'>Please enter a valid email address.</span></div>");
        }
    });


    if ('OTPCredential' in window) {
            window.addEventListener('DOMContentLoaded', e => {
                const input = document.querySelector('input[name="otp"]');
                const ac = new AbortController();
                navigator.credentials.get({
                    otp: { transport:['sms'] },
                    signal: ac.signal
                }).then(otp => {
                    input.value = otp.code;
                }).catch(err => {
                    console.log(err);
                });
            });
        }

});
    </script>
@endsection
