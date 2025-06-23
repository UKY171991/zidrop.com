@extends('frontEnd.layouts.master')
@section('title', 'P2P')
@section('styles')
    <link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/p2p.css">
    <style>
        .booking-page .select2 {
            /* padding: 10px 12px !important; */
            border-radius: 4px;
            resize: none;
            border: 1px solid #c9d1d5;
            color: #213540;
            display: block;
            width: 100% !important;
            font-size: 1rem;
            line-height: 1.5;
            background-clip: padding-box;
            font-weight: 300;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #fff;
            border-radius: 4px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="url"]:focus,
        input[type="password"]:focus,
        input[type="search"]:focus,
        input[type="number"]:focus,
        input[type="tel"]:focus,
        input[type="range"]:focus,
        input[type="date"]:focus,
        input[type="month"]:focus,
        input[type="week"]:focus,
        input[type="time"]:focus,
        input[type="datetime"]:focus,
        input[type="datetime-local"]:focus,
        input[type="color"]:focus,
        textarea:focus {
            outline: none !important;
            color: #595959 !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none !important;
            /* Remove outline on focus */
            color: #595959;
            /* Optional: You can customize the border color on focus */
        }

        .quickTech-price a,
        .quickTech-price ul,
        .quickTech-price ul li,
        .quickTech-price button,
        .quickTech-price input,
        .quickTech-price textarea {
            font-family: 'Poppins', sans-serif;
            font-weight: 400 !important;
        }

        .form-control,
        .contact-form .wpcf7-form-control,
        .contact-form .wpcf7-textarea {
            background: #fff !important;
        }

        .booking-parcel-info h6 {
            font-size: 14px !important;
            font-weight: 500 !important;
            color: #535050 !important;
            font-family: 'Poppins' !important;
        }
    </style>
@endsection
@section('content')
    <style>

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
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="">P2P</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / End Breadcrumb -->

    <!-- quickTech-price -->
    <section class="quickTech-price pt-5 pb-5" style="margin-bottom: -80px">
        <div class="container-fluid">

            <section id="booking-page" class="booking-page">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="booking-page-link">
                                <ul>
                                    <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i> Track
                                            Order</a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-car"></i> Book P2P</a></li>
                                </ul>
                            </div>
                            <form id="shipping-form" class="shipping-form">

                                <!--step-01 -->
                                <div class="slide-content" id="step-01" style="display: block;">
                                    <h1 class="booking-page-heading">Sender Details</h1>
                                    <div class="step-nav">
                                        <ul>
                                            <li class="active" onclick="slideItem('step-01');">Sender</li>
                                            <li onclick="senderInfoVerification();">Recipient</li>
                                            <li onclick="recivierInfoVerifaction();">Parcel Details</li>
                                            <li onclick="parcelDetailVerifiaction();">Review</li>
                                        </ul>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Sender Name <sup>*</sup></label>
                                        <input class="form-control" value="" type="text" name="sender_name"
                                            id="sender_name" placeholder="Enter sender name">
                                        <p class="promo-failure sender_name_error" style="display:none;"> Sender name
                                            required</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Sender Mobile No.<sup>*</sup></label>
                                        <input class="form-control" name="sender_mobile" id="sender_mobile"
                                            onblur="checkMobile(this.value);" placeholder="0802 123 4567"
                                            value="">
                                            <div class="wp2p_nigeria_flag"></div>
                                        <p class="promo-failure sender_mobile_error" style="display:none;"> Sender mobile
                                            required,
                                            11 digit, Nigerian number
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Email <sup>*</sup></label>
                                        <input class="form-control" type="text" name="sender_email" id="sender_email"
                                            onblur="checkEmailType(this.value);" value=""
                                            placeholder="Enter sender email">
                                        <p class="promo-failure sender_email_error" style="display:none;"> Sender email
                                            required and
                                            valid format</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Pickup City<sup>*</sup></label>
                                        <div class="select-style">

                                            <select class=" select2 form-control" value="" name="sender_pickupcity"
                                                id="sender_pickupcity" required="required">
                                                <option value="" selected="selected">Pickup City</option>
                                                @foreach ($wcities as $key => $value)
                                                    <option value="{{ $value->id }}" data-title="{{ $value->title }}">
                                                        {{ $value->title }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <p class="promo-failure sender_pickupcity_error" style="display:none;"> Sender
                                            City Required</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Pickup Town<sup>*</sup></label>
                                        <div class="select-style">

                                            <select type="text" class="select2 form-control"
                                                value="{{ old('sender_pickuptown') }}" name="sender_pickuptown"
                                                id="sender_pickuptown" required="required">
                                                <option value="" selected="selected">Pickup Town</option>
                                            </select>
                                        </div>
                                        <p class="promo-failure sender_district_error" style="display:none;"> Sender
                                            Town Required </p>
                                    </div>


                                    <div class="form-group">
                                        <label class="form-label">Sender Address <sup>*</sup></label>
                                        <textarea maxlength="200" placeholder="Enter sender address" class="form-control" name="sender_address"
                                            id="sender_address" rows="5"></textarea>
                                        <p class="promo-failure sender_address_error" style="display:none;"> Sender
                                            address required
                                        </p>
                                    </div>

                                    <a class="common-btn" onclick="senderInfoVerification();">CONTINUE</a>
                                </div>
                                <!--step-02 -->
                                <div class="slide-content" id="step-02" style="display: none;">
                                    <h1 class="booking-page-heading">Recipient Details</h1>
                                    <div class="step-nav">
                                        <ul>
                                            <li class="active done" onclick="slideItem('step-01');">Sender</li>
                                            <li class="active" onclick="slideItem('step-02');">Recipient</li>
                                            <li onclick="recivierInfoVerifaction();">Parcel Details</li>
                                            <li onclick="parcelDetailVerifiaction();">Review</li>
                                        </ul>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Recipient Name <sup>*</sup></label>
                                        <input class="form-control" type="text" name="recivier_name"
                                            id="recivier_name" placeholder="Enter recipient name">
                                        <p class="promo-failure recipient_name_error" style="display:none;"> Recipient
                                            Name required
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Recipient Mobile No.<sup>*</sup></label>
                                        <input class="form-control"  type="text"
                                            onblur="checkMobile(this.value)" name="recivier_mobile" id="recivier_mobile"
                                            placeholder="0802 123 4567">
                                            <div class="wp2pr_nigeria_flag"></div>
                                        <p class="promo-failure recipient_mobile_error" style="display:none;"> Recipient
                                            mobile
                                            required, 11 digit, Bangladeshi
                                            number</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Recipient City<sup>*</sup></label>
                                        <div class="select-style">

                                            <select class=" select2 form-control"
                                                value="{{ old('recipient_pickupcity') }}" name="recipient_pickupcity"
                                                id="recipient_pickupcity" required="required">
                                                <option value="" selected="selected">Pickup City</option>
                                                @foreach ($wcities as $key => $value)
                                                    <option value="{{ $value->id }}"> {{ $value->title }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <p class="promo-failure recipient_pickupcity_error" style="display:none;"> Sender
                                            City Required</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Recipient Town<sup>*</sup></label>
                                        <div class="select-style">

                                            <select type="text" class="select2 form-control"
                                                value="{{ old('recipient_pickuptown') }}" name="recipient_pickuptown"
                                                id="recipient_pickuptown" required="required">
                                                <option value="" selected="selected">Pickup Town</option>
                                            </select>
                                        </div>
                                        <p class="promo-failure recipient_pickuptown_error" style="display:none;"> Sender
                                            Town Required </p>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Recipient Address <sup>*</sup></label>
                                        <textarea maxlength="200" placeholder="Enter Recipient address" class="form-control" name="recivier_address"
                                            id="recivier_address" rows="3"></textarea>
                                        <p class="promo-failure recipient_address_error" style="display:none;"> Recipient
                                            Address
                                            required</p>
                                    </div>


                                    <div class="btn-wrap">
                                        <button class="common-btn prev" type="button"
                                            onclick="slideItem('step-01');">PREVIOUS</button>
                                        <button class="common-btn" type="button"
                                            onclick="recivierInfoVerifaction();">CONTINUE</button>
                                    </div>
                                </div>
                                <!--step-03 -->
                                <div class="slide-content" id="step-03" style="display: none;">
                                    <h1 class="booking-page-heading">Parcel Details</h1>
                                    <div class="step-nav">
                                        <ul>
                                            <li class="active done" onclick="slideItem('step-01');">Sender</li>
                                            <li class="active done" onclick="slideItem('step-02');">Recipient</li>
                                            <li class="active" onclick="slideItem('step-03');">Parcel Details</li>
                                            <li onclick="parcelDetailVerifiaction();">Review</li>
                                        </ul>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">What do you want to send?<sup>*</sup></label>
                                        <div class="select-style">
                                            <select name="parcel_type" id="parcel_type" required>
                                                <option value="">Select Parcel Type</option>
                                                <option value="1" data-title="Regular">Regular</option>
                                                <option value="2" data-title="Liquid">Liquid</option>
                                                <option value="3" data-title="Fragile">Fragile</option>
                                            </select>
                                        </div>
                                        <p class="promo-failure parcel_type_error" style="display:none;"> Parcel type
                                            required</p>
                                    </div>
                                    <p id="parcel_type_hint" style="display:none"></p>
                                    <div class="form-group" id="parcel_weight_inner">
                                        <label class="form-label">Package weight (KG) <sup>*</sup></label>
                                        <input type="number" class="calculate parcel_weight form-control"
                                            name="parcel_weight" id="parcel_weight" placeholder="Weight in KG"
                                            required="required">

                                        <p class="promo-failure weight_error" style="display:none;"> Weight required</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Number of Item<sup>*</sup></label>
                                        <input class="form-control" type="number" name="number_of_item" value="1"
                                            id="number_of_item" required>
                                        <input class="form-control" type="hidden" name="campaign_discount">

                                    </div>
                                    <div class="form-group" id="parcel_contain_inner">
                                        <label class="form-label">Declared Value <sup>*</sup></label>
                                        <input class="form-control CommaSeperateValueSet" type="text" name="product_value"
                                            id="product_value" required>
                                        <p class="promo-failure product_value_error" style="display:none;"> Declared Value
                                            required
                                        </p>
                                    </div>
                                    <div class="form-group" id="parcel_contain_inner">
                                        <label class="form-label">Item Name<sup>*</sup></label>
                                        <input class="form-control" type="text" name="item_name" id="item_name"
                                            required>
                                        <p class="promo-failure item_name_error" style="display:none;"> Item Name required
                                        </p>
                                    </div>
                                    <div class="form-group" id="parcel_contain_inner">
                                        <label class="form-label">Color<sup>*</sup></label>
                                        <input class="form-control"type="text" name="color" id="color" required>
                                        <p class="promo-failure color_error" style="display:none;"> Color required
                                        </p>
                                    </div>
                                    <div class="form-group" id="parcel_contain_inner">
                                        <label class="form-label">What does this parcel contain?<sup>*</sup></label>
                                        <input class="form-control" name="parcel_contain" id="parcel_contain" required>
                                        <p class="promo-failure parcel_contain_error" style="display:none;"> Parcel
                                            contain required
                                        </p>
                                    </div>
                                    <div class="btn-wrap">
                                        <button class="common-btn prev" type="button"
                                            onclick="slideItem('step-02');">PREVIOUS</button>
                                        <button class="common-btn" type="button"
                                            onclick="parcelDetailVerifiaction();">CONTINUE</button>
                                    </div>
                                </div>
                                <!--step-04 -->
                                <div class="slide-content" id="step-04" style="display: none;">
                                    <h1 class="booking-page-heading">Review &amp; Confirm</h1>
                                    <div class="step-nav">
                                        <ul>
                                            <li class="active done" onclick="slideItem('step-01');">Sender</li>
                                            <li class="active done" onclick="slideItem('step-02');">Recipient</li>
                                            <li class="active done" onclick="slideItem('step-03');">Parcel Details</li>
                                            <li class="active" onclick="slideItem('step-04');">Review</li>
                                        </ul>
                                    </div>
                                    <h2>How would you like it delivered?</h2>


                                    <h2 class="booking-info-heading">Sender Details <a href="#"
                                            onclick="slideItem('step-01');"><i class="icon-pencil"></i> Edit</a></h2>
                                    <div class="booking-info">
                                        <ul>

                                            <li>Name<h6 id="sender_name_t"></h6>
                                            </li>
                                            <li>Mobile Number<h6 id="sender_mobile_t"></h6>
                                            </li>
                                            <li>Email Number<h6 id="sender_email_t"></h6>
                                            </li>
                                            <li>Pickup City<h6 id="sender_pickupcity_t"></h6>
                                            </li>
                                            <li>Pickup Town<h6 id="sender_pickuptown_t"></h6>
                                            </li>
                                            <li>Address<h6 id="sender_address_t"></h6>
                                            </li>
                                        </ul>
                                    </div>
                                    <h2 class="booking-info-heading">Recipient Details <a href="#"
                                            onclick="slideItem('step-02');"><i class="icon-pencil"></i> Edit</a></h2>
                                    <div class="booking-info">
                                        <ul>
                                            <li>Name<h6 id="recivier_name_t"></h6>
                                            </li>
                                            <li>Mobile Number<h6 id="recivier_mobile_t"></h6>
                                            </li>
                                            <li>Reciver City<h6 id="recipient_pickupcity_t"></h6>
                                            </li>
                                            <li>Reciver Town<h6 id="recipient_pickuptown_t"></h6>
                                            </li>
                                            <li>Address<h6 id="recivier_address_t"></h6>
                                            </li>
                                        </ul>
                                    </div>
                                    <h2 class="booking-info-heading">Parcel Details <a href="#"
                                            onclick="slideItem('step-03');"><i class="icon-pencil"></i> Edit</a></h2>
                                    <div class="booking-info booking-parcel-info">
                                        <ul>
                                            <li>Courier Type<h6 id="parcel_type_t"></h6>
                                            </li>
                                            <li id="package_weight_title">Package weight <h6 id="parcel_weight_t"></h6>
                                            </li>
                                            <li id="number_of_item_title">Number of item <h6 id="number_of_item_t"></h6>
                                            </li>
                                            <li>Item Name <h6 id="item_name_t"></h6>
                                            </li>
                                            <li>Color <h6 id="color_t"></h6>
                                            </li>
                                            <li>Parcel Contain <h6 id="parcel_contain_t"></h6>
                                            </li>
                                            <li>Declared Value <h6 id="product_value_t"></h6>
                                            </li>

                                            <li>Delivery Charge<h6 id="delivery_charge_t"></h6>
                                            </li>
                                            {{-- <li>COD Charge<strong id="cod_charge_t"></strong></li> --}}
                                            <li>Tax<h6 id="tax_t"></h6>
                                            </li>
                                            <li>Insurance<h6 id="insurance_t"></h6>
                                            </li>
                                            <li>Total<h6 id="total_t"></h6>
                                            </li>
                                            {{-- <li>Declared Value<strong id="declared_value"> </strong></li>
                                            <li>Packaging Service Charge<strong id="packaging_service_charge_value">
                                            </strong></li> --}}
                                            {{-- <li>Product Type<strong id="product_type"></strong></li>
                                            <li class="discount_val" style="display:none"><span
                                                id="discount_static">Discount</span><strong style="color:red;"
                                                id="product_discount"></strong></li> --}}
                                        </ul>
                                        <input type="hidden" name="totalamount" id="totalamount">
                                    </div>
                                    <div class="form-group check-terms">
                                        <label class="checkbox">
                                            <input type="checkbox" value="0" name="terms_and_condition"
                                                id="termsCheckbox" required class="mt-1">
                                            <span class="checkmark"></span>
                                            I agree to the &nbsp;<a href="{{ url('termscondition') }}"
                                                target="_blank">Terms &amp;
                                                Conditions</a>
                                        </label>
                                        <p class="promo-failure terms_condition_error" style="display:none;"> T&amp;C
                                            required</p>
                                    </div>
                                    <a href="#booking-page" class="common-btn" onclick="payWithPaystack(event)">CONFIRM
                                        &amp;
                                        PAY</a>
                                </div>

                                <!-- otp -->

                                {{-- <section class="otp" id="otp_section_booking" style="display:none;">
                                    <div class="otp-content">
                                        <h2 class="booking-page-heading">Verify yourself</h2>
                                        <h6>We’ve sent you a 4 digit verification code to your mobile no.
                                            <a href="javascript:void(0)"><span
                                                    id="sender_mobile_number">+8801681844033</span>
                                            </a><a onclick="gotoSenderDetail();" href="javascript:void(0)"><i
                                                    class="icon-pencil"></i></a>
                                        </h6>
                                        <div class="otp-input">
                                            <div class="otp-box"><input type="tel" id="otp0"
                                                    class="form-control inputBox" maxlength="1" autocomplete="off"
                                                    autofocus="" data-next="1" placeholder="-"></div>
                                            <div class="otp-box"> <input type="tel" id="otp1"
                                                    class="form-control inputBox" maxlength="1" autocomplete="off"
                                                    data-next="2" placeholder="-"></div>
                                            <div class="otp-box"><input type="tel" id="otp2"
                                                    class="form-control inputBox" maxlength="1" autocomplete="off"
                                                    data-next="3" placeholder="-"></div>
                                            <div class="otp-box"><input type="tel" id="otp3"
                                                    class="form-control inputBox" maxlength="1" autocomplete="off"
                                                    data-next="4" placeholder="-"></div>
                                        </div>
                                        <div class="mobile_message" style="display:none; color:green;"></div>
                                        <div class="mobile_wrong" style="display:none; color:red;"></div>
                                        <button class="common-btn" type="button" onclick="bookingFormSubmit();">CONTINUE
                                            TO
                                            PAY</button>
                                        <p>Resend code in 00:<span id="timer"></span></p>
                                        <p>Didn’t recived a code? <a href="javascript:void(0);" id="resend_otp">Resend
                                                OTP</a></p>

                                        <p>Your Account will create under ecourier p2p.</p>
                                    </div>
                                </section> --}}

                                <!-- payment page  -->

                                {{-- <section class="payment-method" style="display:none;">
                                    <div class="boolean-check">
                                        <h2 class="booking-page-heading">Choose Payment Method</h2>
                                        <ul>
                                            <li>
                                                <label class="radio"><input type="radio" value="bkash"
                                                        name="payment"> <span class="radio-mark"></span> bKash
                                                    <img src="https://ecourier.com.bd/wp-content/themes/ecourier-2.0/images/bkash.svg"
                                                        alt=""></label>
                                            </li>

                                            <li>
                                                <label class="radio"><input type="radio" value="nagad"
                                                        name="payment"> <span class="radio-mark"></span> Nagad
                                                    <img src="https://ecourier.com.bd/wp-content/themes/ecourier-2.0/images/nagad-logo-vertical.wine.svg"
                                                        alt=""></label>
                                            </li>
                                            <li>
                                                <label class="radio"><input type="radio" value="upay"
                                                        name="payment"> <span class="radio-mark"></span> Upay
                                                    <img src="https://ecourier.com.bd/wp-content/themes/ecourier-2.0/images/upay_logo.svg"
                                                        alt=""></label>
                                            </li>
                                            <li>
                                                <label class="radio"><input type="radio" value="sslcomm"
                                                        name="payment"> <span class="radio-mark"></span> Card/Others
                                                    <img src="https://ecourier.com.bd/wp-content/themes/ecourier-2.0/images/visa-master-amex.svg"
                                                        alt=""></label>
                                            </li>

                                        </ul>
                                        <p class="promo-failure payment_method_error" style="display:none;"> Payment
                                            Method Required
                                        </p>
                                        <a class="common-btn" onclick="processPayment();" name="">CONFIRM PAY</a>
                                    </div>
                                </section> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </section>
@endsection
@section('custom_js_script')
    <script>
        $(document).ready(function() {
            document.getElementById('termsCheckbox').addEventListener('change', function() {
                this.value = this.checked ? 1 : 0;
            });
            // Function to load dropdown options from an AJAX call
            function loadDropdown(url, targetSelect, defaultOptionText) {
                var selectElement = $(targetSelect);
                selectElement.empty().append('<option selected disabled>Loading...</option>');
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        selectElement.empty().append('<option value="" selected disabled>' +
                            defaultOptionText + '</option>');
                        $.each(data, function(key, value) {
                            selectElement.append('<option value="' + value.id +
                                '" data-title="' + value.title + '">' + value.title +
                                '</option>');
                        });
                    },
                    error: function() {
                        alert('Failed to load data. Please try again.');
                    }
                });
            }
            // Handler for sender city change
            $('#sender_pickupcity').on('change', function() {
                var city_id = $(this).val();
                if (city_id) {
                    // Load towns for sender city
                    loadDropdown("{{ url('/web/get-town') }}/" + city_id,
                        'select[name="sender_pickuptown"]', 'Pickup Town');

                    // Load delivery cities and tarrif information
                    $.ajax({
                        url: "{{ url('/web/get-tarrif/') }}/" + city_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var deliveryCitySelect = $('select[name="recipient_pickupcity"]');
                            deliveryCitySelect.empty().append(
                                '<option value="" selected="selected" disabled>Delivery City</option>'
                            );
                            $.each(data, function(key, value) {
                                deliveryCitySelect.append('<option value="' + value
                                    .delivery_cities_id +
                                    '" data-deliverycharge="' + value
                                    .deliverycharge +
                                    '" data-codcharge="' + value.codcharge +
                                    '" data-tax="' + value.tax +
                                    '" data-title="' + value.deliverycity.title +
                                    '" data-insurance="' + value.insurance +
                                    '" data-cityid="' + value.delivery_cities_id +
                                    '" data-extradeliverycharge="' + value
                                    .extradeliverycharge + '">' +
                                    value.deliverycity.title + '</option>');
                            });
                        },
                        error: function() {
                            alert('Failed to load tarrif data. Please try again.');
                        }
                    });
                } else {
                    alert('Please select a valid city.');
                }
            });
            // Handler for recipient city change
            $('#recipient_pickupcity').on('change', function() {
                var city_id = $(this).val();

                if (city_id) {
                    $.ajax({
                        url: "{{ url('/web/get-town') }}/" +
                        city_id, // Make sure the URL is correct
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var deliveryTownSelect = $('select[name="recipient_pickuptown"]');
                            deliveryTownSelect.empty().append(
                                '<option value="" selected="selected" disabled>Delivery Town</option>'
                            );
                            $.each(data, function(key, value) {
                                deliveryTownSelect.append('<option value="' + value.id + '" data-towncharge="' + value.towncharge + '" data-title="' + value.title + '">' +
                                    value.title + '</option>');
                            });
                        },
                        error: function() {
                            alert('Failed to load town data. Please try again.');
                        }
                    });
                } else {
                    alert('Please select a valid city.');
                }
            });


        });

        // Data validation 
        function senderInfoVerification() {
            var firstErrorOccurance = '';
            var sender_name = $('#shipping-form [name="sender_name"]').val();
            var sender_mobile = $('#shipping-form [name="sender_mobile"]').val();
            var sender_email = $('#shipping-form [name="sender_email"]').val();
            var sender_pickuptown = $('#shipping-form [name="sender_pickuptown"]').val();
            var sender_pickupcity = $('#shipping-form [name="sender_pickupcity"]').val();
            var sender_address = $('#shipping-form [name="sender_address"]').val();

            if (
                sender_name != "" && sender_name != undefined && sender_name != null &&
                sender_mobile != "" && sender_mobile != undefined && sender_mobile != null &&
                sender_email != "" && sender_email != undefined && sender_email != null &&
                sender_pickuptown != "" && sender_pickuptown != undefined && sender_pickuptown != null &&
                sender_pickupcity != "" && sender_pickupcity != undefined && sender_pickupcity != null &&
                sender_address != "" && sender_address != undefined && sender_address != null
            ) {
                // fbq('track', 'P2PSenderInfoReceived');
                $('.sender_name_error').hide();
                $('.sender_mobile_error').hide();
                $('.sender_email_error').hide();
                $('.sender_pickupcity_error').hide();
                $('.sender_pickuptown_error').hide();
                $('.sender_address_error').hide();
                //document.body.scrollTop = 0;
                //document.documentElement.scrollTop = 0;
                firstErrorOccurance = '';
                slideItem('step-02');
                // Scroll to top with animation
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast'); // Smooth scroll to top when moving to the next step

            } else {
                if (sender_name == '' || sender_name == undefined || sender_name == null) {
                    $('.sender_name_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'sender_name';
                } else {
                    $('.sender_name_error').hide();
                }
                if (sender_mobile == '' || sender_mobile == undefined || sender_mobile == null) {
                    $('.sender_mobile_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'sender_mobile';
                } else {
                    $('.sender_mobile_error').hide();
                }

                if (sender_email == '' || sender_email == undefined || sender_email == null) {
                    $('.sender_email_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'sender_email';
                } else {
                    $('.sender_email_error').hide();
                }

                if (sender_pickuptown == '' || sender_pickuptown == undefined || sender_pickuptown == null) {
                    $('.sender_district_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'sender_district';
                } else {
                    $('.sender_district_error').hide();
                }

                if (sender_pickupcity == '' || sender_pickupcity == undefined || sender_pickupcity == null) {
                    $('.sender_area_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'sender_area';
                } else {
                    $('.sender_area_error').hide();
                }

                if (sender_address == '' || sender_address == undefined || sender_address == null) {
                    $('.sender_address_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'sender_address';
                } else {
                    $('.sender_address_error').hide();
                }
                //console.log(sender_name);
            }

            if (firstErrorOccurance) {
                $('[name="' + firstErrorOccurance + '"]').focus();
                $('html, body').animate({
                    scrollTop: $('[name="' + firstErrorOccurance + '"]').offset().top - 100 + 'px'
                }, 'fast');

            }
        }

        function recivierInfoVerifaction() {
            var firstErrorOccurance = '';
            var recivier_name = $('#shipping-form [name="recivier_name"]').val();
            var recivier_mobile = $('#shipping-form [name="recivier_mobile"]').val();
            var recipient_pickupcity = $('#shipping-form [name="recipient_pickupcity"]').val();
            var recipient_pickuptown = $('#shipping-form [name="recipient_pickuptown"]').val();
            var recivier_address = $("#shipping-form [name='recivier_address']").val();
            if (
                recivier_name != "" && recivier_name != undefined && recivier_name != null &&
                recivier_mobile != "" && recivier_mobile != undefined && recivier_mobile != null &&
                recipient_pickupcity != "" && recipient_pickupcity != undefined && recipient_pickupcity != null &&
                recipient_pickuptown != "" && recipient_pickuptown != undefined && recipient_pickuptown != null &&
                recivier_address != "" && recivier_address != undefined && recivier_address != null
            ) {
                // fbq('track', 'P2PRecipentInfoReceived');
                $('.recipient_name_error').hide();
                $('.recipient_mobile_error').hide();
                $('.recipient_pickupcity_error').hide();
                $('.recipient_pickuptown_error').hide();
                $('.recipient_address_error').hide();
                slideItem('step-03');
                // Scroll to top with animation
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast'); // Smooth scroll to top when moving to the next step
            } else {
                if (recivier_name == '' || recivier_name == undefined || recivier_name == null) {
                    $('.recipient_name_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'recivier_name';
                } else {
                    $('.recipient_name_error').hide();
                }
                if (recivier_mobile == '' || recivier_mobile == undefined || recivier_mobile == null) {
                    $('.recipient_mobile_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'recivier_mobile';
                } else {
                    $('.recipient_mobile_error').hide();
                }

                if (recipient_pickupcity == '' || recipient_pickupcity == undefined || recipient_pickupcity == null) {
                    $('.recipient_pickupcity_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'recipient_pickupcity';
                } else {
                    $('.recipient_district_error').hide();
                }
                if (recipient_pickuptown == '' || recipient_pickuptown == undefined || recipient_pickuptown == null) {
                    $('.recipient_area_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'recipient_pickuptown';
                } else {
                    $('.recipient_area_error').hide();
                }
                if (recivier_address == '' || recivier_address == undefined || recivier_address == null) {
                    $('.recipient_address_error').show();
                    firstErrorOccurance = (firstErrorOccurance) ? firstErrorOccurance : 'recivier_address';
                } else {
                    $('.recipient_address_error').hide();
                }

            }
            if (firstErrorOccurance) {
                $('[name="' + firstErrorOccurance + '"]').focus();
                $('html, body').animate({
                    scrollTop: $('[name="' + firstErrorOccurance + '"]').offset().top - 100 + 'px'
                }, 'fast');

            }
        }
        // Parcel Data validation 
        function parcelDetailVerifiaction() {
            var firstErrorOccurance = '';
            // Parcel Details
            var parcel_type = $('#shipping-form [name="parcel_type"] option:selected').data('title');
            var weight = $('#shipping-form [name="parcel_weight"]').val();
            var number_of_item = $('#shipping-form [name="number_of_item"]').val();
            var item_name = $('#shipping-form [name="item_name"]').val();
            var color = $('#shipping-form [name="color"]').val();
            var parcel_contain = $('#shipping-form [name="parcel_contain"]').val();
            var product_value = convertCommaSeparatedToNumber($('#shipping-form [name="product_value"]').val());

            // Default values for weight and number_of_item
            if (!number_of_item) number_of_item = 1;
            if (!weight) weight = 1;

            // Receiver Details
            var recivier_name = $('#shipping-form [name="recivier_name"]').val();
            var recivier_mobile = $('#shipping-form [name="recivier_mobile"]').val();
            var recipient_pickupcity = $('#shipping-form [name="recipient_pickupcity"] option:selected').data('title');
            var recipient_pickuptown = $('#shipping-form [name="recipient_pickuptown"] option:selected').data('title');
            var recivier_address = $("#shipping-form [name='recivier_address']").val();

            // Sender Details
            var sender_name = $('#shipping-form [name="sender_name"]').val();
            var sender_mobile = $('#shipping-form [name="sender_mobile"]').val();
            var sender_email = $('#shipping-form [name="sender_email"]').val();
            var sender_pickuptown = $('#shipping-form [name="sender_pickuptown"] option:selected').data('title');
            var sender_pickupcity = $('#shipping-form [name="sender_pickupcity"] option:selected').data('title');
            var sender_address = $('#shipping-form [name="sender_address"]').val();

            // Validation
            if (
                parcel_type && weight && number_of_item &&
                product_value && item_name && color && parcel_contain
            ) {
                // Parcel Details
                $('#parcel_type_t').html(parcel_type);
                $('#parcel_weight_t').html(weight + ' (kg)'); // Fixed concatenation issue
                $('#number_of_item_t').html(number_of_item);
                $('#item_name_t').html(item_name);
                $('#color_t').html(color);
                $('#parcel_contain_t').html(parcel_contain);
                $('#product_value_t').html('₦ ' + checkNan(CurrencyFormatted(product_value)));

                // Receiver Details
                $('#recivier_name_t').html(recivier_name);
                $('#recivier_mobile_t').html(recivier_mobile);
                $('#recipient_pickupcity_t').html(recipient_pickupcity);
                $('#recipient_pickuptown_t').html(recipient_pickuptown);
                $('#recivier_address_t').html(recivier_address);

                // Sender Details
                $('#sender_name_t').html(sender_name);
                $('#sender_mobile_t').html(sender_mobile);
                $('#sender_email_t').html(sender_email);
                $('#sender_pickupcity_t').html(sender_pickupcity);
                $('#sender_pickuptown_t').html(sender_pickuptown);
                $('#sender_address_t').html(sender_address);

                // Calculation
                var stateCharge = parseInt($('select[name="recipient_pickupcity"] option:selected').attr(
                    'data-deliverycharge')) || 0;
                var extraCharge = parseInt($('select[name="recipient_pickupcity"] option:selected').attr(
                    'data-extradeliverycharge')) || 0;
                var cod = parseInt($('select[name="recipient_pickupcity"] option:selected').attr('data-codcharge')) || 0;
                var zoneCharge = parseInt($('select[name="recipient_pickuptown"] option:selected').attr(
                    'data-towncharge')) || 0;
                console.log(zoneCharge);
                var taxPercent = parseFloat($('select[name="recipient_pickupcity"] option:selected').attr('data-tax')) || 0;
                var insurancePercent = parseFloat($('select[name="recipient_pickupcity"] option:selected').attr(
                    'data-insurance')) || 0;
                var cash = convertCommaSeparatedToNumber($("input[name='product_value']").val()) || 0;

                extraCharge = parseInt(weight) > 1 ? (parseInt(weight) * extraCharge) - extraCharge : 0;
                charge = stateCharge + extraCharge + zoneCharge;

                var formatCharge = CurrencyFormatted(charge);
                $("#delivery_charge_t").text('₦ ' + checkNan(formatCharge));

                // Tax Calculation
                var tax = charge * (taxPercent / 100);
                $("#tax_t").text('₦ ' + checkNan(CurrencyFormatted(tax)));

                // Insurance Calculation
                var insurance = cash * (insurancePercent / 100);
                $("#insurance_t").text('₦ ' + checkNan(CurrencyFormatted(insurance)));

                // Total Calculation
                var total = charge + tax + insurance;
                $("#totalamount").val(charge + tax + insurance);
                $("#total_t").text('₦ ' + checkNan(CurrencyFormatted(total)));

                // Proceed to next step
                slideItem('step-04');

                // Scroll to top with animation
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast'); // Smooth scroll to top when moving to the next step
            } else {
                // Error Handling inline
                if (!parcel_type) {
                    $('.parcel_type_error').show();
                    firstErrorOccurance = firstErrorOccurance || 'parcel_type';
                } else {
                    $('.parcel_type_error').hide();
                }

                if (!weight) {
                    $('.weight_error').show();
                    firstErrorOccurance = firstErrorOccurance || 'weight';
                } else {
                    $('.weight_error').hide();
                }

                if (!number_of_item) {
                    $('.number_of_item_error').show();
                    firstErrorOccurance = firstErrorOccurance || 'number_of_item';
                } else {
                    $('.number_of_item_error').hide();
                }

                if (!product_value) {
                    $('.product_value_error').show();
                    firstErrorOccurance = firstErrorOccurance || 'product_value';
                } else {
                    $('.product_value_error').hide();
                }

                if (!item_name) {
                    $('.item_name_error').show();
                    firstErrorOccurance = firstErrorOccurance || 'item_name';
                } else {
                    $('.item_name_error').hide();
                }

                if (!color) {
                    $('.color_error').show();
                    firstErrorOccurance = firstErrorOccurance || 'color';
                } else {
                    $('.color_error').hide();
                }

                if (!parcel_contain) {
                    $('.parcel_contain_error').show();
                    firstErrorOccurance = firstErrorOccurance || 'parcel_contain';
                } else {
                    $('.parcel_contain_error').hide();
                }

                if (firstErrorOccurance) {
                    $('[name="' + firstErrorOccurance + '"]').focus();
                    $('html, body').animate({
                        scrollTop: $('[name="' + firstErrorOccurance + '"]').offset().top - 100 + 'px'
                    }, 'fast');
                }
            }
        }

        // all needed
        function slideItem(step) {
            // Hide all steps
            $('.slide-content').hide();

            // Show the selected step
            $('#' + step).show();

            // Update navigation indicators (if applicable)
            // $('.step-nav ul li').removeClass('active');
            // $('.step-nav ul li').each(function() {
            //     if ($(this).text().trim() === step) {
            //         $(this).addClass('active');
            //     }
            // });
        }

        function checkMobile(mobile) {
            // Updated regex pattern to match numbers starting with 08 and followed by 10 digits
            // var mobile_pattern = /^08[0-9]{9}$/;

            // if (mobile.match(mobile_pattern) == null) {
            //     $('.sender_mobile_error').css('display', 'block');
            // } else {
            //     $('.sender_mobile_error').css('display', 'none');
            // }
            return true;
        }

        function checkEmailType(mobile) {

            var email_pattern = /^[^_.-][\w-._]+@[a-zA-Z_-]+?(\.[a-zA-Z]{2,26}){1,3}$/;
            if (mobile.match(email_pattern) == null) {
                $('.sender_email_error').show();
            } else {
                $('.sender_email_error').hide();
            }
        }

        function CurrencyFormatted(number) {
            var decimalplaces = 2;
            var decimalcharacter = ".";
            var thousandseparater = ",";
            number = parseFloat(number);
            var sign = number < 0 ? "-" : "";
            var formatted = new String(number.toFixed(decimalplaces));
            if (decimalcharacter.length && decimalcharacter != ".") {
                formatted = formatted.replace(/\./, decimalcharacter);
            }
            var integer = "";
            var fraction = "";
            var strnumber = new String(formatted);
            var dotpos = decimalcharacter.length ? strnumber.indexOf(decimalcharacter) : -1;
            if (dotpos > -1) {
                if (dotpos) {
                    integer = strnumber.substr(0, dotpos);
                }
                fraction = strnumber.substr(dotpos + 1);
            } else {
                integer = strnumber;
            }
            if (integer) {
                integer = String(Math.abs(integer));
            }
            while (fraction.length < decimalplaces) {
                fraction += "0";
            }
            temparray = new Array();
            while (integer.length > 3) {
                temparray.unshift(integer.substr(-3));
                integer = integer.substr(0, integer.length - 3);
            }
            temparray.unshift(integer);
            integer = temparray.join(thousandseparater);
            return sign + integer + decimalcharacter + fraction;
        }

        function checkNan(total) {
            var str = total.split(".");
            if (str[0] == 'NaN') {
                return '00';
            }
            return total;
        }
       
    </script>
    
    {{-- Payment --}}
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const paymentForm = document.getElementById('shipping-form');
        paymentForm.addEventListener("submit", payWithPaystack, false);

        function payWithPaystack(e) {
            e.preventDefault();

            let total = parseFloat(convertCommaSeparatedToNumber($("#totalamount").val()));
            if (isNaN(total) || total <= 0) {
                alert('Invalid total amount.');
                return;
            }

            // Fetch sender information
            let sender_name = document.getElementById("sender_name").value.trim();
            let sender_email = document.getElementById("sender_email").value.trim();
            let sender_mobile = document.getElementById("sender_mobile").value.trim();
            let sender_pickuptown = document.getElementById("sender_pickuptown").value.trim();
            let sender_pickupcity = document.getElementById("sender_pickupcity").value.trim();
            let sender_address = document.getElementById("sender_address").value.trim();

            // Fetch recipient information
            let receiver_name = document.getElementById("recivier_name").value.trim();
            let receiver_mobile = document.getElementById("recivier_mobile").value.trim();
            let recipient_pickupcity = document.getElementById("recipient_pickupcity").value.trim();
            let recipient_pickuptown = document.getElementById("recipient_pickuptown").value.trim();
            let receiver_address = document.getElementById("recivier_address").value.trim();

            // Fetch parcel information
            let parcel_type = document.getElementById("parcel_type").value.trim();
            let parcel_weight = parseFloat(document.getElementById("parcel_weight").value);
            let number_of_item = parseInt(document.getElementById("number_of_item").value);
            var product_value = document.getElementById("product_value").value;
                product_value = convertCommaSeparatedToNumber(product_value);
                console.log('product value: ' + product_value);
            let item_name = document.getElementById("item_name").value.trim();
            let color = document.getElementById("color").value.trim();
            let terms_and_condition = document.getElementById("termsCheckbox").value;
            if (!termsCheckbox.checked) {
                // alert("Please agree to the Terms & Conditions before proceeding.");
                toastr.error("Please agree to the Terms & Conditions before proceeding.");
                return false; // Prevent further action
            }
            let parcel_contain = document.getElementById("parcel_contain").value.trim();

            // Basic validation for required fields
            if (!sender_email || !sender_name || !receiver_name || !receiver_mobile) {
                alert('Please fill in all required fields.');
                return;
            }

            let handler = PaystackPop.setup({
               
                // key: '<?= $results->public ?>', 
                key: 'pk_test_9e185aac0936fd9313529f6471cdc37873adc730', 
                email: sender_email,
                phone: sender_mobile,
                amount: total * 100, // Convert amount to kobo
                ref: 'Zi_' + Math.floor(Math.random() * 9999) + '_' + Math.floor(Math.random() *
                    99999999), // Unique reference
                language: "en", // Optional language property

                onClose: function() {
                    alert('Payment window closed.');
                },

                callback: function(response) {
                    let reference = response.reference;

                    // Verify payment via the backend
                    $.ajax({
                        type: "GET",
                        url: "{{ URL::to('/web/get/ppverify-payment/') }}/" + reference,
                        success: function(paymentResponse) {
                            if (paymentResponse[0].status === true) {
                                // Store payment data
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    }
                                });
                                console.log(paymentResponse);

                                $.ajax({
                                    type: "GET",
                                    url: "{{ URL::to('/web/get/store-payment') }}",
                                    data: {
                                        reference: reference,
                                        paymentResponse: paymentResponse,
                                        total: total,
                                        sender_name: sender_name,
                                        sender_email: sender_email,
                                        sender_mobile: sender_mobile,
                                        sender_pickuptown: sender_pickuptown,
                                        sender_pickupcity: sender_pickupcity,
                                        sender_address: sender_address,
                                        recivier_name: receiver_name,
                                        recivier_mobile: receiver_mobile,
                                        recipient_pickupcity: recipient_pickupcity,
                                        recipient_pickuptown: recipient_pickuptown,
                                        recivier_address: receiver_address,
                                        parcel_type: parcel_type,
                                        parcel_weight: parcel_weight,
                                        number_of_item: number_of_item,
                                        product_value: product_value,
                                        item_name: item_name,
                                        color: color,
                                        parcel_contain: parcel_contain,
                                        terms_and_condition: terms_and_condition,
                                    },
                                    success: function(res) {
                                        //console.log(res.status);
                                        // Swal.fire({
                                        //     icon: res.status === true ?
                                        //         'success' : 'error',
                                        //     title: res.status === true ?
                                        //         'Transaction completed successfully!' :
                                        //         'Transaction failed!',
                                        //     toast: true,
                                        //     position: 'top-end',
                                        //     showConfirmButton: false,
                                        //     timer: 3000,
                                        //     timerProgressBar: true,

                                        // });
                                        // const Toast = Swal.mixin({
                                        //     toast: true,
                                        //     position: 'top-end',
                                        //     showConfirmButton: false,
                                        //     timer: 3000,
                                        //     timerProgressBar: true,
                                        //     didOpen: (toast) => {
                                        //         toast.addEventListener(
                                        //             'mouseenter', Swal
                                        //             .stopTimer
                                        //             ); // Pause on hover
                                        //         toast.addEventListener(
                                        //             'mouseleave', Swal
                                        //             .resumeTimer
                                        //             ); // Resume on mouse leave
                                        //     }
                                        // });

                                        // Call Toast.fire based on the transaction status
                                        // Toast.fire({
                                        //     icon: res.status === true ?
                                        //         'success' : 'error',
                                        //     title: res.status === true ?
                                        //         'Your transaction completed successfully!' :
                                        //         'Transaction failed!'
                                        // });
                                        // Toast.fire({
                                        //     icon: 'success',
                                        //     title: 'Your transaction completed successfully!!'
                                        // })
                                        toastr.success(
                                            "Your transaction completed successfully!"
                                            );

                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 2000); // 3000 milliseconds = 3 seconds
                                        $('html, body').animate({
                                            scrollTop: 0
                                        },
                                        'fast'); // Smooth scroll to top when moving to the next step

                                    },
                                    error: function(err) {
                                        //console.error('Error storing payment data:', err);
                                        alert(
                                            'Failed to store payment data. Please try again.'
                                        );
                                    }
                                });
                            } else {
                                alert('Payment verification failed. Please try again.');
                            }
                        },
                        error: function(err) {
                            //console.error('Payment verification error:', err);
                            alert('Error verifying payment. Please try again.');
                        }
                    });
                }
            });

            handler.openIframe();
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if (session()->has('open_url') && session()->get('open_url') != '')
                window.open('{{ session()->get('open_url') }}', '_blank');
            @endif
        });

        function convertCommaSeparatedToNumber(value) {
                if (!value) return 0; // Return 0 for empty or undefined values
                // Convert the value to a string, remove commas, and parse as a number
                return Number(String(value).replace(/,/g, '')) || 0;
            }
    </script>

@endsection
