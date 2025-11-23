@extends('backEnd.layouts.master')
@section('title', 'Create Parcel')
@section('extracss')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endsection
@section('content')
<style>
           .walletbalance{
        background-color: #A53D3D;
    padding: 10px 20px;
    border-radius: 5px;
    color: #ffffff;
    display: none;
    }
</style>
    {{-- <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0 text-dark">Welcome !! {{ auth::user()->name }}</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="#">Parcel</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div> --}}


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="manage-button">
                        <div class="body-title">
                            <h5>Create Parcel</h5>
                        </div>
                        <div class="quick-button">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="box-content">
                        <div class="row">
                            {{-- <div class="col-12">
                                <h3 class="card-title">Add Parcel Info</h3>
                            </div> --}}

                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <!-- /.card-header -->
                                @if (session()->has('message'))
                                    <div class="alert alert-danger">

                                        {{ session('message') }}

                                    </div>
                                @endif
                                <!-- form start -->
                                <form role="form" action="{{ url('editor/parcel/store') }}" method="POST"
                                    enctype="multipart/form-data" id="AdminShipmentForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    value="{{ old('name') }}" name="name" required
                                                    placeholder="Customer Name" required="required">
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('productName') ? ' is-invalid' : '' }}"
                                                    value="{{ old('productName') }}" name="productName"
                                                    placeholder="Product Name" required="required">
                                                @if ($errors->has('productName'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('productName') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- form group -->
                                        <div class="col-sm-6">
                                            <div class="form-group">

                                                <select
                                                    class="form-control select2 {{ $errors->has('merchantId') ? ' is-invalid' : '' }}"
                                                    value="{{ old('merchantId') }}" name="merchantId" required='required' id="merchantId">
                                                    <option value="" data-walletbalance = {{0}}>Select Merchant</option>

                                                    @foreach ($merchants as $value)
                                                        <option value="{{ $value->id }}" 
                                                            data-pickupLocation="{{ $value->pickLocation }}" 
                                                            data-ins_cal_permission="{{$value->ins_cal_permission}}" 
                                                            data-walletbalance="{{ $value->balance ?? 0 }}"
                                                            data-cod_cal_permission="{{$value->cod_cal_permission}}"
                                                            data-subs-cod_permission="{{ @$value->activeSubscription->plan->cod_permission  }}"
                                                            data-subs-insurance_permission="{{  @$value->activeSubscription->plan->insurance_permission  }}"
                                                            data-subs-del_crg_discount_percentage="{{ @$value->activeSubscription ? $value->activeSubscription->plan->del_crg_discount_percentage : 0 }}"
                                                            >
                                                            {{ $value->companyName }}</option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('merchantId'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('merchantId') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="pickupcity select2 form-control{{ $errors->has('pickupcity') ? ' is-invalid' : '' }}"
                                                    value="{{ old('pickupcity') }}" name="pickupcity" id="pickupcity" required="required">
                                                    <option value="" selected="selected">Pickup City</option>
                                                    @foreach ($wcities as $key => $value)
                                                        <option value="{{ $value->id }}"> {{ $value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('pickupcity'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('pickupcity') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="pickuptown select2 form-control{{ $errors->has('pickuptown') ? ' is-invalid' : '' }}"
                                                    value="{{ old('pickuptown') }}" name="pickuptown" required="required">
                                                    <option value="" selected="selected">Pickup Town</option>
                                                   
                                                </select>
                                                @if ($errors->has('pickuptown'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('pickuptown') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="deliverycity select2 form-control{{ $errors->has('deliverycity') ? ' is-invalid' : '' }}"
                                                    value="{{ old('deliverycity') }}" name="deliverycity" id="deliverycity" required="required">
                                                    <option value="" selected="selected">Delivery City</option>
                                                   
                                                </select>
                                                @if ($errors->has('deliverycity'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('deliverycity') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="deliverytown select2 form-control{{ $errors->has('deliverytown') ? ' is-invalid' : '' }}"
                                                    value="{{ old('deliverytown') }}" name="deliverytown" required="required">
                                                    <option value="" selected="selected">Delivery Town</option>
                                                   
                                                </select>
                                                @if ($errors->has('deliverytown'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('deliverytown') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="form-control{{ $errors->has('percelType') ? ' is-invalid' : '' }}"
                                                    value="{{ old('percelType') }}" name="percelType" required="required">
                                                    <option value="">Select Parcel Type</option>
                                                    <option value="1">Regular</option>
                                                    <option value="2">Liquid</option>
                                                    <option value="3">Fragile</option>
                                                </select>
                                                @if ($errors->has('percelType'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('percelType') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="tel"
                                                    class="form-control{{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                    value="{{ old('phonenumber') }}" name="phonenumber"
                                                    placeholder="0802 123 4567" required="required">
                                                @if ($errors->has('phonenumber'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('phonenumber') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-6 position-relative">
                                            <div class="form-group">
                                                <input type="tel"
                                                    class="form-control pr-5 {{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                    value="{{ old('phonenumber') }}" name="phonenumber" id="phonenumber"
                                                    placeholder="0802 123 4567" required>
                                                <div class="nigeria-flag"></div>
                                                @if ($errors->has('phonenumber'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('phonenumber') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        

                                        <div class="col-sm-6 ">
                                            <div class="form-group">
                                            <select type="text"
                                                class=" form-control{{ $errors->has('payment_option') ? ' is-invalid' : '' }}"
                                                value="{{ old('payment_option') }}" name="payment_option"
                                                placeholder="Delivery Area" required="required" id="payment_option">
                                                <option value="">Payment Option</option>
                                                <option value="1">Prepaid</option>
                                                <option value="2">Pay on Delivery</option>
                                            </select>
                                            @if ($errors->has('payment_option'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('payment_option') }}</strong>
                                                </span>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- <div class="col-sm-6 ">
                                            <div class="form-group">
                                            <select type="text"
                                                class=" form-control{{ $errors->has('vehicle_type') ? ' is-invalid' : '' }}"
                                                value="{{ old('vehicle_type') }}" name="vehicle_type"
                                                placeholder="Delivery Area" required="required" id="vehicle_type">
                                                <option value="">Vehicle Type</option>
                                                <option value="Van">Van</option>
                                                <option value="Bike">Bike</option>
                                            </select>
                                            @if ($errors->has('vehicle_type'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('vehicle_type') }}</strong>
                                                </span>
                                            @endif
                                            </div>
                                        </div> --}}
                                        <div class="col-sm-6" id="cod">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="calculate cod CommaSeperateValueSet form-control{{ $errors->has('cod') ? ' is-invalid' : '' }}"
                                                    value="{{ old('cod') }}" name="cod" min="0"
                                                    placeholder="Cash Collection Amount" required="required">
                                                @if ($errors->has('cod'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('cod') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <style>
                                            #package_valueSHow {
                                                display: none;
                                            }
                                        </style>
                                        <div class="col-sm-4" id="package_valueSHow">
                                            <div class="form-group">
                                                <input type="text" class="form-control CommaSeperateValueSet"
                                                    value="{{ old('package_value') }}" name="package_value"
                                                    value="0" min="0" placeholder="Declared Value"
                                                    id="package_value">

                                            </div>
                                        </div>

                                        <div class="col-sm-6" id="weight">
                                            <div class="form-group">
                                                <input type="number"
                                                    class="calculate weight form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}"
                                                    value="{{ old('weight') }}" name="weight"
                                                    placeholder="Weight in KG" required="required">
                                                @if ($errors->has('weight'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('weight') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="calculate form-control{{ $errors->has('order_number') ? ' is-invalid' : '' }}"
                                                    min="0" value="{{ old('order_number') }}" name="order_number"
                                                    placeholder="Order Number" required="required">
                                                @if ($errors->has('order_number'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('order_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="number"
                                                    class="calculate form-control{{ $errors->has('productQty') ? ' is-invalid' : '' }}"
                                                    min="0" value="{{ old('productQty') }}" name="productQty"
                                                    placeholder="Product Quantity" required="required">
                                                @if ($errors->has('productQty'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('productQty') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('productColor') ? ' is-invalid' : '' }}"
                                                    value="{{ old('productColor') }}" name="productColor"
                                                    placeholder="Product Color" required="required">
                                                @if ($errors->has('productColor'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('productColor') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <textarea type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                    value="{{ old('address') }}" name="address" placeholder="Customer Full Address" required="required"></textarea>
                                                @if ($errors->has('address'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <textarea type="text" name="note" value="{{ old('note') }}" class="form-control" placeholder="Note"
                                                    required="required"></textarea>
                                                @if ($errors->has('note'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('note') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="form-control btn btn-primary" id="submitBtn">Submit</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <!-- col end -->
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <div class="parcel-details-instance">
                                    <h2>Delivery Charge Details</h2>
                                    <div class="content calculate_result">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <p>Cash Collection</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <p><span class="cashCollection">00</span> N</p>
                                            </div>
                                        </div>
                                        <!-- row end -->
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <p>Delivery Charge</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <p><span class="devlieryCharge">00</span> N</p>
                                            </div>
                                        </div>
                                        <!-- row end -->
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <p>Cod Charge</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <p><span class="codCharge">00</span> N</p>
                                            </div>
                                        </div>
                                        <!-- row end -->
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <p>Tax</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <p><span class="tax">00</span> N</p>
                                            </div>
                                        </div>
                                        <!-- row end -->
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <p>Insurance</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <p><span class="insurance">00</span> N</p>
                                            </div>
                                        </div>
                                        {{-- <hr> --}}
                                        <!-- row end -->
                                        <div class="row total-bar">
                                            <div class="col-sm-8">
                                                <p>Total Payable Amount</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <p><span class="total">00</span> N</p>
                                            </div>
                                        </div>
                                        <!-- row end -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p class="text-end unbold">
                                                  <strong>Note:</strong> <span>Pickup requests made after <strong>9:00 AM</strong> will be scheduled for collection the next day.</span>
                                                </p>
                                            </div>
                                        </div>
                                        <!-- row end -->
                                    </div>
                                </div>
                                <br>
                                <div class="prcTag_section flex justify-content-between align-items-center">
                                    <h5 class="walletbalance"><strong>Merchant Wallet Balance: N<span class="walletbalanceAMnt"></span></strong> </h5>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- submenu dependency --}}
  

    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .parcel-details-instance {
            background: #ddd;
        }

        .parcel-details-instance h2 {
            border-bottom: 5px solid #A53D3D;
            font-size: 22px;
            text-align: center;
            padding: 20px 0;
            font-weight: 600;
        }

        .parcel-details-instance .content {
            padding: 25px 25px;
        }

        .parcel-details-instance p {
            font-size: 17px;
            font-weight: 600;
        }

        .hr {
            height: 2px;
            width: 100%;
            background: black;
            margin-bottom: 16px;
        }

        p.unbold {
            font-weight: 500;
        }
    </style>
@endsection

@section('custom_js_scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("AdminShipmentForm"); // Select form
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function (event) {
            // Check if the button is already disabled (to avoid multiple clicks)
            if (submitBtn.disabled) {
                event.preventDefault(); // Prevent duplicate submissions
                return;
            }

            // Disable the button and change text
            submitBtn.disabled = true;
            submitBtn.innerText = "Submitting...";

            // Allow the form to submit normally
        });
    });
</script>
    <script>
//      $('#merchantId').select2();

// // Apply uppercase to all dropdown options when the dropdown opens
// $('#merchantId').on('select2:open', function() {
//     setTimeout(() => {
//         $('.select2-results__option').css('text-transform', 'uppercase');

//         // Apply uppercase to the search box input text
//         $('.select2-search__field').css('text-transform', 'uppercase');
//     }, 50);
// });

// // Apply uppercase to the selected item
// $('#merchantId').on('select2:select', function() {
//     setTimeout(() => {
//         $('#merchantId').next('.select2-container').find('.select2-selection__rendered').css('text-transform', 'uppercase');
//     }, 50);
// });
        $(document.body).ready(function() {
           
            $('#pickupcity').on('change', function() {
                var city_id = $(this).val();
                if (city_id) {
                    $.ajax({
                        url: "{{ url('/admin/get-town/') }}/" + city_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var d = $('select[name="pickuptown"]').empty();
                            $('select[name="pickuptown"]').append(
                                '<option value="" selected="selected" disabled>Pickup Town</option>');
                            $.each(data, function(key, value) {
                                $('select[name="pickuptown"]').append('<option value="' + value.id + '">' +
                                    value.title + '</option>');
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
            $('#pickupcity').on('change', function() {
                var city_id = $(this).val();
                if (city_id) {
                    $.ajax({
                        url: "{{ url('/admin/get-tarrif/') }}/" + city_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var d = $('select[name="deliverycity"]').empty();
                            $('select[name="deliverycity"]').append(
                                '<option value="" selected="selected" disabled>Delivery City</option>');
                            $.each(data, function(key, value) {
                                $('select[name="deliverycity"]').append('<option value="' + value.delivery_cities_id + '" data-deliverycharge="' + value.deliverycharge + '" data-codcharge="' + value.codcharge + '" data-tax="' + value.tax + '" data-insurance="' + value.insurance + '" data-cityid="' + value.delivery_cities_id + '" data-extradeliverycharge="' + value.extradeliverycharge + '">' +
                                    value.deliverycity.title + '</option>');
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
            $('#deliverycity').on('change', function() {
                var city_id =  $(this).find(':selected').data('cityid');
                if (city_id) {
                    $.ajax({
                        url: "{{ url('/admin/get-town/') }}/" + city_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var d = $('select[name="deliverytown"]').empty();
                            $('select[name="deliverytown"]').append(
                                '<option value="" selected="selected" disabled>Delivery Town</option>');
                            $.each(data, function(key, value) {
                                $('select[name="deliverytown"]').append('<option value="' + value.id + '" data-towncharge="' + value.towncharge + '">' +
                                    value.title + '</option>');
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
            // $('#pickupOrdropOff, #merchantId').on('change', function() {
            //     var value =  $('#pickupOrdropOff').val() ?? 0;
            //     var merchantID = $('select[name="merchantId"]').val();
            //     var city_id = $('select[name="pickupcity"]').val();
            //     if (value == 1) {
            //         if (merchantID) {
            //             $.ajax({
            //                 url: "{{ url('/admin/get-merchant/') }}/" + merchantID,
            //                 type: "GET",
            //                 dataType: "json",
            //                 success: function(data) {
            //                     var d = $('select[name="addressofSender"]').empty();
            //                     $.each(data, function(key, value) {
            //                         $('select[name="addressofSender"]').append('<option value="' + value.pickLocation +  '" disable>' +
            //                             value.pickLocation + '</option>');
            //                     });
            //                 },
            //             });
                         
            //         } else {
            //             alert('PLease Select Merchant');
            //         }
                 
                                          
            //     } else if(value == 2) {
            //         if (city_id) {
            //             $.ajax({
            //                 url: "{{ url('/admin/get-branch/') }}/" + city_id,
            //                 type: "GET",
            //                 dataType: "json",
            //                 success: function(data) {
            //                     var d = $('select[name="addressofSender"]').empty();
            //                     $.each(data, function(key, value) {
            //                         $('select[name="addressofSender"]').append('<option value="' + value.address + '">' +
            //                             value.address + '</option>');
            //                     });
            //                 },
            //             });
            //         } else {
            //             alert('PLease Select City');
            //         }                
                
            //     } else {
            //         console.log('nothing');
            //     }
            // });
        });

     

    </script>

<script type="text/javascript">
    $(document).ready(function() {
        @if (session()->has('open_url') && session()->get('open_url') != '')
            window.open('{{ session()->get('open_url') }}', '_blank');
        @endif
        $('select[name="payment_option"]').on('change', function() {
            var payment_option = $("#payment_option").val();
            if (payment_option == 1) {
                $(".cod").val(0);
                $(".cod").attr("readonly", true);
                $("#package_value").attr("required", true);
                $("#package_valueSHow").show();
                $("#weight").removeClass('col-sm-6');
                $("#weight").addClass('col-sm-4');
                $("#cod").removeClass('col-sm-6');
                $("#cod").addClass('col-sm-4');
                var walletbalance =  $('select[name="merchantId"] option:selected').data('walletbalance');
                    $(".walletbalanceAMnt").text(CurrencyFormatted(walletbalance));
                    $(".walletbalance").show();
                    $(".codCharge").text(CurrencyFormatted(0));
            } else {
                $(".cod").val('');
                $("input[name='package_value']").val('');
                $(".cod").attr("readonly", false);
                $("#package_value").attr("required", false);
                $("#package_valueSHow").hide();
                $("#weight").removeClass('col-sm-4');
                $("#weight").addClass('col-sm-6');
                $("#cod").removeClass('col-sm-4');
                $("#cod").addClass('col-sm-6');
                $(".walletbalance").hide();
            }
            devlieryCharge();
            codUpdate();
        });
        $('select[name="deliverycity"]').on('change', function() {
            devlieryCharge();
            codUpdate();
        });
        $('select[name="pickupcity"]').on('change', function() {
            devlieryCharge();
                codUpdate();
        });
        $('select[name="merchantId"]').on('change', function() {
            var walletbalance = $(this).find('option:selected').data('walletbalance') ?? 0;
                var payment_option = $("#payment_option").val();
                if (payment_option == 1) {
                $(".walletbalanceAMnt").text(CurrencyFormatted(walletbalance));
                $(".walletbalance").show();

                }else{
                    $(".walletbalance").hide();
                }
            devlieryCharge();
                codUpdate();
        });
        $('select[name="deliverytown"]').on('change', function() {
            devlieryCharge();
                codUpdate();
        });

        $("input[name='weight']").on("keyup", function() {
            devlieryCharge();
        })

        $("input[name='cod']").on("keyup", function() {
            var cod = convertCommaSeparatedToNumber($("input[name='cod']").val());
            var formated = CurrencyFormatted(cod);
            var formated = checkNan(formated);
            $(".cashCollection").text(formated);
            codUpdate();
            devlieryCharge();
        })
        
        $("input[name='package_value']").on("keyup", function() {
            var cod = convertCommaSeparatedToNumber($("input[name='package_value']").val());
            var formated = CurrencyFormatted(cod);
            var formated = checkNan(formated);
            $(".cashCollection").text('00');
            codUpdate();
            devlieryCharge();
        })

        function codUpdate() {
            var cod_cal_permission = $('select[name="merchantId"] option:selected').data('cod_cal_permission');
            console.log(cod_cal_permission);
                var formated;

                if (cod_cal_permission == 0) {
                    formated = CurrencyFormatted(0);  // If permission is 0, the charge is 0
                } else {
                    var cash = convertCommaSeparatedToNumber($("input[name='cod']").val()) ?? 0;
                    var charge = $('select[name="deliverycity"] option:selected').attr('data-codcharge') ?? 0;
                    
                    // Calculate the charge based on selected city or default to 0
                    var chargeValue = charge ? parseFloat(cash) * (parseFloat(charge) / 100) : 0;
                    formated = CurrencyFormatted(chargeValue);
                }

                // Ensure the value is not NaN
                formated = checkNan(formated);
                // Update the text in the codCharge element
                var codPermission =  $('select[name="merchantId"] option:selected').data('subs-cod_permission');
                if (codPermission == 0 && cod_cal_permission == 0) {
                    $(".codCharge").text(0); // COD allowed, no charge
                } else {
                    $(".codCharge").text(formated); // Discount or COD not allowed
                }
            }

            function devlieryCharge() {
            var formated;
            var ins_cal_permission = $('select[name="merchantId"] option:selected').data('ins_cal_permission');
            var cod_cal_permission = $('select[name="merchantId"] option:selected').data('cod_cal_permission');

            var payment_option = $("#payment_option").val();
            console.log('payment_option ' + payment_option);

            var stateCharge = $('select[name="deliverycity"] option:selected').attr('data-deliverycharge') || 0;
            var extraCharge = $('select[name="deliverycity"] option:selected').attr('data-extradeliverycharge') || 0;
            var cod = $('select[name="deliverycity"] option:selected').attr('data-codcharge') || 0;
            var zoneCharge = $('select[name="deliverytown"] option:selected').attr("data-towncharge") || 0;

            // Cash calculation based on payment option
            var cash;
            if (payment_option == 1) {
                cash = convertCommaSeparatedToNumber($("input[name='package_value']").val()) || 0;
            } else {
                cash = convertCommaSeparatedToNumber($("input[name='cod']").val()) || 0;
            }
            console.log('cash ' + cash);

            var weight = $("input[name='weight']").val();
            var taxPercent = $('select[name="deliverycity"] option:selected').attr("data-tax");
            var insurancePercent = $('select[name="deliverycity"] option:selected').attr("data-insurance");

            // Extra charge calculation
            extraCharge = parseInt(weight) > 1 ? (parseInt(weight) * parseInt(extraCharge)) - parseInt(extraCharge) : 0;

            // Ensure charges are numeric
            stateCharge = stateCharge ? stateCharge : 0;
            zoneCharge = zoneCharge ? zoneCharge : 0;
            var charge = parseInt(stateCharge) + parseInt(extraCharge) + parseInt(zoneCharge);
            console.log('charge ' + charge);

            // check Active Plan
            var delChargeCommission = parseFloat($('select[name="merchantId"] option:selected').data('subs-del_crg_discount_percentage'));
                // Apply discount if any
                if (delChargeCommission > 0) {
                    var discountAmount = charge * (delChargeCommission / 100);
                    charge = charge - discountAmount;
                }

            var formatCharge = CurrencyFormatted(charge);
            formatCharge = checkNan(formatCharge);
            $(".devlieryCharge").text(formatCharge);

            // COD charge
            var codPermission =  $('select[name="merchantId"] option:selected').data('subs-cod_permission');;
                
                if (codPermission == 0 && cod_cal_permission == 0) {
                    var codcharge = 0;
                } else {
                    var codcharge = parseFloat(cash) * (cod / 100);
                    var codformated = CurrencyFormatted(codcharge);
                        codformated = checkNan(codformated);
                        if (payment_option == 1) {
                            $(".codCharge").text(0); // Discount or COD not allowed
                        }else{
                            $(".codCharge").text(codformated); // Discount or COD not allowed
                        }
                }

            // Tax calculation
            var tax = charge * (taxPercent / 100);
            var formated = CurrencyFormatted(tax);
            formated = checkNan(formated);
            $(".tax").text(formated);
            console.log('tax ' + tax);

            // Insurance calculation
            var InsPermission =  $('select[name="merchantId"] option:selected').data('subs-insurance_permission');            
                if ((isNaN(InsPermission) || InsPermission == 0) && ins_cal_permission == 0) {
                    var insurance = 0;
                } else {
                    var insurance = parseFloat(cash) * (insurancePercent / 100);
                }
                var formated = CurrencyFormatted(insurance);
                formated = checkNan(formated);
                console.log('insurance ' + insurance);
                $(".insurance").text(formated);
            // Total calculation based on payment option
            var total;
            if (payment_option == 1) {
                total = charge + tax + insurance;
            } else {                 
                total = (charge + codcharge + tax + insurance) - parseFloat(cash);
            }

            console.log('total ' + total);
            total = total * -1; // Invert total for display
            total = CurrencyFormatted(total);
            total = checkNan(total);
            $(".total").text(total);
        }

        function checkNan(total) {
            var str = total.split(".");
            if (str[0] == 'NaN') {
                return '00';
            }
            return total;
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
        

    });

</script>
@endsection
