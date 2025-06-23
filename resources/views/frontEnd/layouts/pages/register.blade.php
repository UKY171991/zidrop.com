@extends('frontEnd.layouts.master') @section('title', 'Register')
@section('content')

    <style>
        #sent_mail {
            position: absolute;
            top: 3.5px;
            right: 18px !important;
            margin: 2px;
            font-size: .8rem;
        }

        .align_btn_input {
            vertical-align: middle;
            display: -webkit-box;
        }

        #sent_mail:hover {
            /* color: #fff !important; */
            opacity: .7;
        }

        .btn.focus,
        .btn:focus {
            outline: 0;
            box-shadow: none !important;
        }
    </style>
    <!-- Breadcrumb -->
    <div class="breadcrumbs" style="background: #db0022">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <!-- Bread Menu -->
                        <div class="bread-menu">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="#"> Sign Up</a></li>
                            </ul>
                        </div>
                        <!-- Bread Title -->
                        <!--<div class="bread-title"><h2>Merchant Sign Up</h2></div>-->
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
                            <h5>Register Now</h5>
                        </div>
                        <?php /*
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link" id="phone-tab" data-toggle="tab" href="#phone" role="tab" aria-controls="phone" aria-selected="true">Mobile register</a>-->
                            <!--</li>-->
                            <li class="nav-item">
                                <a class="nav-link active" id="email-tab" data-toggle="tab" href="#email" role="tab"
                                    aria-controls="email" aria-selected="false">Email register</a>
                            </li>
                        </ul>
                        */ ?>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="phone" role="tabpanel" aria-labelledby="phone-tab">
                                <div class="text-center">
                                    <h3 class="h5 text-muted text-uppercase">become a merchant</h3>
                                </div>
                                <div class="mobile-register-area">
                                    <form action="{{ url('auth/merchant/register') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="companyName"
                                                        required="" placeholder="Name of Business" />
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="firstName"
                                                        required="" placeholder="Your Name" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" name="phoneNumber" class="form-control" required=""
                                                placeholder="01XXXXXXXXX" />
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="password" name="password" class="form-control"
                                                        required="" placeholder="Password" />
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="password" name="confirmed" id="confirmed"
                                                        class="form-control" required=""
                                                        placeholder="Confirm Password" />
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-check pl-4">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1"
                                                value="1" name="agree" />
                                            <label class="form-check-label" for="exampleCheck1">I agree to
                                                <a href="{{ url('termscondition') }}">terms and condition.</a></label>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="submit">register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="email" role="tabpanel"
                                aria-labelledby="email-tab">
                                <div class="text-center">
                                    <h3 class="h5 text-muted text-uppercase">become a merchant</h3>
                                </div>
                                <div class="mobile-register-area">
                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <ul class="p-0 m-0" style="list-style: none">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ url('auth/merchant/register') }}" method="POST" id="registerForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <!-- <div class="btn-group" role="group" aria-label="Basic example" style="width: 100%;">
                            <input type="email" class="form-control" required="" id="email_data" name="emailAddress" placeholder="Email" value="{{ old('emailAddress') }}" />
                            <button type="button" class="btn btn-primary" id="sent_mail" style="right: 0px;color: white !important;background-color: #af251b;border: 2px solid #af251b !important;border-radius: 0px 4px 4px 0px !important;">Get OTP </button>
                          </div> -->
                                                <div class="align_btn_input">
                                                    <div class="form-group" role="group" aria-label="Basic example"
                                                        style="width: 100%;">
                                                        <input type="email" class="form-control" required=""
                                                            id="email_data" name="emailAddress" placeholder="Email"
                                                            value="{{ old('emailAddress') }}" />

                                                    </div>
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-primary" id="sent_mail"
                                                            style="right: 0px;color: white !important;background-color: #af251b;border: 2px solid #af251b !important;border-radius: 4px !important;">Get
                                                            OTP </button>
                                                    </div>
                                                </div>
                                                <div id="emailError"></div>

                                            </div>

                                            <div class="col-sm-12 mt-3">
                                                <div class="form-group">
                                                    <input type="tel" name="otp" id="otp_val"
                                                        class="form-control" pattern="[0-9]{6}" placeholder="OTP"
                                                        readonly required value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" required=""
                                                        id="companyName_val" name="companyName"
                                                        placeholder="Name of Business" readonly
                                                        value="{{ old('companyName') }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="tel" name="phoneNumber" id="phoneNumber_val"
                                                        class="form-control" placeholder="Enter your phone number" required readonly />
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" required=""
                                                        id="firstName_val" name="firstName" placeholder="Your Name"
                                                        readonly value="{{ old('firstName') }}" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="password" class="form-control" required=""
                                                        id="password_val" name="password" readonly
                                                        placeholder="Password" />
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="password" class="form-control" required=""
                                                        name="confirmed" readonly id="confirmed_val"
                                                        placeholder="Confirm Password" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-check pl-4">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck2"
                                                value="" name="agree" required="" />
                                            <!--value="1"-->
                                            <label class="form-check-label" for="exampleCheck2">I agree to <a
                                                    href="{{ url('termscondition') }}">terms and condition</a></label>
                                        </div>

                                        {{-- <div class="form-group">
                                            @if (config('google_captcha.site_key'))
                                                <div class="g-recaptcha"
                                                    data-sitekey="{{ config('google_captcha.site_key') }}">
                                                </div>
                                                @error('g-recaptcha-response')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="alert alert-danger" id="gcaptcha-error"
                                                    style="display: none"></div>
                                            @endif
                                        </div> --}}

                                        <div class="form-group">
                                            <button type="submit" class="submit" id="dubmit_data"
                                                disabled>register</button>
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
@endsection

@section('custom_js_script')

<!-- intl-tel-input CSS/JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/intlTelInput.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Company name uppercase
        let input = document.getElementById("companyName_val");
        if (input) {
            input.placeholder = input.placeholder.toUpperCase();
            input.addEventListener("input", function () {
                this.value = this.value.toUpperCase();
            });
        }

        // intl-tel-input for phone number (Nigeria only)
        var phoneInput = document.querySelector("#phoneNumber_val");
        if (phoneInput && window.intlTelInput) {
            var iti = window.intlTelInput(phoneInput, {
                initialCountry: "ng", // Nigeria
                onlyCountries: ["ng"],
                nationalMode: true,
                formatOnDisplay: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
            });

            // Custom formatting for Nigerian numbers: 0806 833 4781
            phoneInput.addEventListener('input', function() {
                let value = phoneInput.value.replace(/\D/g, '');
                if (value.length > 11) value = value.substring(0, 11);
                let formatted = value;
                if (value.length > 4 && value.length <= 7) {
                    formatted = value.substring(0, 4) + ' ' + value.substring(4);
                } else if (value.length > 7) {
                    formatted = value.substring(0, 4) + ' ' + value.substring(4, 7) + ' ' + value.substring(7);
                }
                phoneInput.value = formatted;
            });
        }
    });
</script>

    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        @if (config('google_captcha.site_key'))
            $("#registerForm").on('submit', function(e) {
                if (grecaptcha.getResponse() === '') {
                    event.preventDefault(); // Stop form from submitting
                    e.preventDefault();
                    $("#gcaptcha-error").html('Please complete the captcha');
                    $("#gcaptcha-error").show();
                    // alert('Please complete the reCAPTCHA.');
                }
            });
        @endif


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
                        url: "{{ url('merchant/register-otp') }}", // Adjust this URL as needed
                        type: 'GET',
                        data: {
                            email: email
                        },
                        success: function(res) {
                            // console.log(res.message);
                            if (res.message != '') {
                                //$('#emailError').html("<span style='color:green'>"+ res.message +"</span>"); // Show error message

                                $('#emailError').html(
                                    "<div class='alert alert-warning' role='alert'><span style='color:#664d03'><i class='fa fa-envelope-o' aria-hidden='true'></i> A One-Time Password (OTP) has been sent to your email address. Please enter the OTP below to continue with your merchant registration. Check your spam folder if you don't see an email.</span></div>"
                                    ); // Show error message
                                $("#otp_val").removeAttr("readonly");
                                $("#sent_mail").html('Resend');
                            } else {
                                $('#emailError').html(
                                    "<div class='alert alert-danger' role='alert'><span style='color:#664d03'>Please enter a valid email address.</span></div>"
                                    );
                                $('#emailError').html(
                                    '<span style="color:red">Failed to process response. Please try again.</span>'
                                    ); // Show error message

                            }
                        },
                    });
                } else {
                    $('#emailError').html(
                        "<div class='alert alert-danger' role='alert'><span style='color:#664d03'>Please enter a valid email address.</span></div>"
                        ); // Show error message
                }
            });

            $("#otp_val").on("input", function() {
                var email = $('#email_data').val(); //.trim(); // Trim email input value
                var otp_val = $('#otp_val').val(); //.trim(); // Trim OTP input value

                // Only proceed if the email is not empty and OTP is entered
                if (email !== '' && otp_val.length === 6) {
                    $.ajax({
                        url: "{{ url('merchant/register-verifyOtp') }}", // Ensure this URL is correct
                        type: 'GET',
                        data: {
                            email: email,
                            otp: otp_val
                        },
                        success: function(res) { // Corrected 'succsess' to 'success'
                            if (res.status === 'true') {
                                //$("#otp_val").removeAttr("readonly"); // Remove readonly if OTP is valid
                                $('#emailError').html(
                                    "<div class='alert alert-success' role='alert'><span style='color:#664d03'>OTP verified successfully.</span></div>"
                                    );
                                    $("#phoneNumber_val").prop("readonly", false);
                                    $("#companyName_val").prop("readonly", false);
                                    $("#firstName_val").prop("readonly", false);
                                    $("#password_val").prop("readonly", false);
                                    $("#confirmed_val").prop("readonly", false);
                                    $("#dubmit_data").prop("disabled", false);
                            } else {
                                $('#emailError').html(
                                    "<div class='alert alert-danger' role='alert'><span style='color:#664d03'>" +
                                    res.message + "</span></div>");
                            }
                        },
                        error: function(xhr, status, error) {
                            //console.error('Error during AJAX request:', error);
                            $('#emailError').html(
                                "<div class='alert alert-danger' role='alert'><span style='color:#664d03'>An error occurred while verifying the OTP.</span></div>"
                                );
                        }
                    });
                } else if (otp_val.length !== 6) {
                    $('#emailError').html(
                        "<div class='alert alert-danger' role='alert'><span style='color:#664d03'>Please enter a valid 6-digit OTP.</span></div>"
                        );
                } else {
                    $('#emailError').html(
                        "<div class='alert alert-danger' role='alert'><span style='color:#664d03'>Please enter a valid email address.</span></div>"
                        );
                }
            });


            if ('OTPCredential' in window) {
                window.addEventListener('DOMContentLoaded', e => {
                    const input = document.querySelector('input[name="otp"]');
                    const ac = new AbortController();
                    navigator.credentials.get({
                        otp: {
                            transport: ['sms']
                        },
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
