@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title', 'Parcel Edit')
@section('content')
<style>

.discount-tag {
            display: inline-block;
            background: linear-gradient(135deg, #ff7b7b, #ff4c4c);
            color: white;
            padding: 6px 14px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 14px;
            transform-origin: top center;
            animation: swing 2s ease-in-out infinite;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* Pendulum-like swing animation */
        @keyframes swing {
            0% {
                transform: rotate(-10deg);
            }

            50% {
                transform: rotate(10deg);
            }

            100% {
                transform: rotate(-10deg);
            }
        }

        .prcTag_section {
            display: flex;
            justify-content: space-between;
            /* Distribute the space between elements */
            align-items: center;
            width: 100%;
        }


        .actvPlan {
            font-size: 1.8rem;
            color: #333;
            display: flex;
    align-items: center;
    gap: 8px; /* Space between name and badge */
        }

        .price_Tag {
            display: flex;
            align-items: center;
        }
        .subscription-badge {
            display: inline-block;
    padding: 2px 12px;
    font-size: 12px;
    font-weight: 700;
    border-radius: 10px;
    text-transform: uppercase;
    border: 1px solid transparent;
    vertical-align: middle;
    position: relative;
    top: -2px; /* manually nudge it up */
    }

    .subscription-badge.active {
        background-color: #e6f4ea; /* Light green background */
        color: #237a3b; /* Darker green text */
        border-color: #a8d5b1; /* Light green border */
    }

</style>
    <section class="section-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row addpercel-inner">
                        <div class="col-sm-12">
                            <div class="addpercel-top">
                                <h3>Edit Parcel </h3>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <div class="fraud-search">
                                <form action="{{ url('merchant/update/parcel') }}" method="POST">
                                    @csrf
									
									<input type="hidden" value="{{ $edit_data->id }}" name="hidden_id">
                                    <input type="hidden" id="ins_cal_permission" name="ins_cal_permission" value="{{$merchantDetails->ins_cal_permission}}">
                                    <input type="hidden" id="cod_cal_permission" name="cod_cal_permission" value="{{$merchantDetails->cod_cal_permission}}">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    value="{{ $edit_data->recipientName }}" name="name" required
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
                                                    value="{{ $edit_data->productName }}" name="productName"
                                                    placeholder="Product Name" required="required">
                                                @if ($errors->has('productName'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('productName') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="pickupcity select2 form-control{{ $errors->has('pickupcity') ? ' is-invalid' : '' }}"
                                                    value="{{ old('pickupcity') }}" name="pickupcity" id="pickupcity" required="required">
                                                    <option value="" disable>Pickup City</option>
                                                    @foreach ($wcities as $key => $value)
                                                        <option value="{{ $value->id }}" @if($value->id == $edit_data->pickup_cities_id) selected @endif>{{ $value->title }}
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
													@foreach ($wtowns as $key => $value)
													<option value="{{ $value->id }}" @if($value->id == $edit_data->pickup_town_id) selected @endif>{{ $value->title }}
													</option>
												@endforeach
                                                   
                                                </select>
                                                @if ($errors->has('pickuptown'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('pickuptown') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
											<?php
												$DelCities = \App\ChargeTarif::where('pickup_cities_id', $edit_data->pickup_cities_id)->with('deliverycity')->get();
												
												?>
												
                                            <div class="form-group">
                                             
												<select type="text" class="deliverycity select2 form-control{{ $errors->has('deliverycity') ? ' is-invalid' : '' }}" value="{{ old('deliverycity') }}" name="deliverycity" id="deliverycity" required="required">
													@foreach ($DelCities as $value)
														<option value="{{ $value->delivery_cities_id }}" 
															data-deliverycharge="{{ $value->deliverycharge }}" 
															data-codcharge="{{ $value->codcharge }}" 
															data-tax="{{ $value->tax }}" 
															data-insurance="{{ $value->insurance }}" 
															data-cityid="{{ $value->delivery_cities_id }}" 
															data-extradeliverycharge="{{ $value->extradeliverycharge }}"
															@if($value->delivery_cities_id == $edit_data->delivery_cities_id) selected @endif>
															{{ $value->deliverycity->title }}
														</option>
													@endforeach
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
                                                    @foreach ($wtowns as $key => $value)
													<option value="{{ $value->id }}" @if($value->id == $edit_data->delivery_town_id) selected @endif>{{ $value->title }}
													</option>
												@endforeach
                                                   
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
                                                    value="{{ $edit_data->percelType }}" name="percelType"
                                                    placeholder="Invoice or Memo Number" required="required">
                                                    <option value="">Select Parcel Type</option>
                                                    <option value="1" {{ $edit_data->percelType == 1 ? 'selected' : '' }}>Regular</option>
                                                    <option value="2"  {{ $edit_data->percelType == 2 ? 'selected' : '' }}>Liquid</option>
                                                    <option value="3"  {{ $edit_data->percelType == 3 ? 'selected' : '' }}>Liquid</option>
                                                </select>
                                                @if ($errors->has('percelType'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('percelType') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                    value="{{ $edit_data->recipientPhone }}" name="phonenumber"
                                                    placeholder="0802 123 4567" required="required">
                                                    <div class="nigeria-flag"></div>
                                                @if ($errors->has('phonenumber'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('phonenumber') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <select type="text"
                                                class="select2 form-control{{ $errors->has('payment_option') ? ' is-invalid' : '' }}"
                                                value="{{ $edit_data->payment_option }}" name="payment_option"
                                                placeholder="Delivery Area" required="required" id="payment_option">
                                                <option value="">Payment Option</option>
                                                <option value="1" {{ $edit_data->payment_option == 1 ? 'selected' : '' }}>Prepaid Payment</option>
                                                <option value="2" {{ $edit_data->payment_option == 2 ? 'selected' : '' }}>Pay on Delivery</option>
                                            </select>
                                            @if ($errors->has('payment_option'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('payment_option') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="calculate cod CommaSeperateValueSet form-control{{ $errors->has('cod') ? ' is-invalid' : '' }}"
                                                    value="{{$edit_data->cod}}" name="cod" min="0"
                                                    placeholder="Cash Collection Amount" required="required" @if($edit_data->payment_option == 1) readonly @endif>
                                                @if ($errors->has('cod'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('cod') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($edit_data->payment_option != 1)
										<style>
                                            #package_valueSHow {
                                                display: none;
                                            }
                                        </style>
										@endif
                                        <div class="col-sm-6" id="package_valueSHow">
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                value="{{$edit_data->package_value}}" name="package_value CommaSeperateValueSet"
                                                    value="0" min="0" placeholder="Declared Value"
                                                    id="package_value" @if($edit_data->payment_option == 1) required @endif>

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="number"
                                                    class="calculate weight form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}"
                                                    value="{{ $edit_data->productWeight }}" name="weight"
                                                    placeholder="Weight in KG" required="required">
                                                @if ($errors->has('weight'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('weight') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="number"
                                                    class="calculate form-control{{ $errors->has('productQty') ? ' is-invalid' : '' }}"
                                                    min="0" value="{{ $edit_data->productQty }}" name="productQty"
                                                    placeholder="Product Quantity" required="required">
                                                @if ($errors->has('productQty'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('productQty') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('productColor') ? ' is-invalid' : '' }}"
                                                    value="{{ $edit_data->productColor }}" name="productColor"
                                                    placeholder="Product Color" required="required">
                                                @if ($errors->has('productColor'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('productColor') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <textarea type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                    value="" name="address" placeholder="Customer Full Address" required="required">{{ $edit_data->recipientAddress }}</textarea>
                                                @if ($errors->has('address'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
										
                                            <div class="form-group">
                                                <textarea type="text" name="note" value="" class="form-control" placeholder="Note"
                                                    required="required">{{ $edit_data->note }}</textarea>
                                                @if ($errors->has('note'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('note') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <button type="submit" class="form-control">Update</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="parcel-details-instance">
                                <h2>Delivery Charge Details</h2>
                                <div class="content calculate_result">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <p>Cash Collection</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <p><span class="cashCollection"> {{ number_format($edit_data->cod, 2) }}N </span></p>
                                        </div>
                                    </div>
                                    <!-- row end -->
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <p>Delivery Charge</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <p><span class="devlieryCharge"> {{ number_format($edit_data->deliveryCharge, 2) }}N </span></p>
                                        </div>
                                    </div>
                                    <!-- row end -->
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <p>Cod Charge</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <p><span class="codCharge"> {{ number_format($edit_data->codCharge, 2) }}N </span> </p>
                                        </div>
                                    </div>
                                    <!-- row end -->
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <p>Tax</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <p><span class="tax"> {{  number_format($edit_data->tax, 2) }}N </span> </p>
                                        </div>
                                    </div>
                                    <!-- row end -->
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <p>Insurance</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <p><span class="insurance"> {{  number_format($edit_data->insurance, 2) }}N </span> </p>
                                        </div>
                                    </div>
                                    
                                    <!-- row end -->
                                    <div class="row total-bar">
                                        <div class="col-sm-8">
                                            <p>Total Payable Amount</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <p><span class="total"> {{ number_format(($edit_data->deliveryCharge + $edit_data->codCharge + $edit_data->tax + $edit_data->insurance - $edit_data->cod) * (-1),2) }}N </span> </p>
                                        </div>
                                    </div>
                                    <!-- row end -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="text-center unbold">Note : <span class="">If you request for
                                                    pick up after 5pm, it will be collected on the next day</span></p>
                                        </div>
                                    </div>
                                    <!-- row end -->
                                </div>
                            </div>
                            <br>

                            <div class="prcTag_section flex justify-content-between align-items-center">
                                @if($merchantSubsPlan )
                                <div class="actvPlan">
                                    {{ @$merchantSubsPlan->plan->name }}  <span class="subscription-badge active text-left">
                                    ACTIVE
                                </span></div>
                                
                                <span class="price_Tag">
                                    <div class="discount-tag">
                                        {{ @$merchantSubsPlan->plan->id == 1 ? '10% OFF' : '20% OFF' }}</div>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- col end -->

                </div>
            </div>
        </div>
        </div>
    </section>

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
            font-size: 14px;
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
        $(document.body).ready(function() {
            $('#pickupcity').on('change', function() {
                var city_id = $(this).val();
                if (city_id) {
                    $.ajax({
                        url: "{{ url('/merchant/get-town/') }}/" + city_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var d = $('select[name="pickuptown"]').empty();
                            $('select[name="pickuptown"]').append(
                                '<option value="" selected="selected" disabled>Pickup Town</option>'
                                );
                            $.each(data, function(key, value) {
                                $('select[name="pickuptown"]').append(
                                    '<option value="' + value.id + '">' +
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
                        url: "{{ url('/merchant/get-tarrif/') }}/" + city_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var d = $('select[name="deliverycity"]').empty();
                            $('select[name="deliverycity"]').append(
                                '<option value="" selected="selected" disabled>Delivery City</option>'
                                );
                            $.each(data, function(key, value) {
                                $('select[name="deliverycity"]').append(
                                    '<option value="' + value.delivery_cities_id +
                                    '" data-deliverycharge="' + value
                                    .deliverycharge + '" data-codcharge="' + value
                                    .codcharge + '" data-tax="' + value.tax +
                                    '" data-insurance="' + value.insurance +
                                    '" data-cityid="' + value.delivery_cities_id +
                                    '" data-extradeliverycharge="' + value
                                    .extradeliverycharge + '">' +
                                    value.deliverycity.title + '</option>');
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
            $('#deliverycity').on('change', function() {
                var city_id = $(this).find(':selected').data('cityid');
                if (city_id) {
                    $.ajax({
                        url: "{{ url('/merchant/get-town/') }}/" + city_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var d = $('select[name="deliverytown"]').empty();
                            $('select[name="deliverytown"]').append(
                                '<option value="" selected="selected" disabled>Delivery Town</option>'
                                );
                            $.each(data, function(key, value) {
                                $('select[name="deliverytown"]').append(
                                    '<option value="' + value.id +
                                    '" data-towncharge="' + value.towncharge +
                                    '">' +
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
            //                 url: "{{ url('/merchant/get-merchant/') }}/" + merchantID,
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
            //                 url: "{{ url('/merchant/get-branch/') }}/" + city_id,
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
                } else {
                    $(".cod").val('');
                    $("input[name='package_value']").val('');
                    $(".cod").attr("readonly", false);
                    $("#package_value").attr("required", false);
                    $("#package_valueSHow").hide();
                }
                codUpdate();
                devlieryCharge();
            });

            $('select[name="deliverycity"]').on('change', function() {
            devlieryCharge();
                    codUpdate();
            });
            $('select[name="pickupcity"]').on('change', function() {
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
                var cod_cal_permission = $("input[name='cod_cal_permission']").val();
                var formated;

                if (cod_cal_permission == 0) {
                    formated = CurrencyFormatted(0);  // If permission is 0, the charge is 0
                } else {
                    var cash = convertCommaSeparatedToNumber($("input[name='cod']").val());
                    var charge = $('select[name="deliverycity"] option:selected').attr('data-codcharge');
                    
                    // Calculate the charge based on selected city or default to 0
                    var chargeValue = charge ? parseFloat(cash) * (parseFloat(charge) / 100) : 0;
                    formated = CurrencyFormatted(chargeValue);
                }

                // Ensure the value is not NaN
                formated = checkNan(formated);
                
                // Update the text in the codCharge element
                // Get the COD permission from the backend, default to 1 (COD allowed) if no plan
                var codPermission = parseInt('{{ @$merchantSubsPlan->plan->cod_permission ?? 1 }}');
                if (codPermission == 0) {
                    $(".codCharge").text(0); // COD allowed, no charge
                } else {
                    $(".codCharge").text(formated); // Discount or COD not allowed
                }
            }

            function devlieryCharge() {
                var formated;
                var ins_cal_permission = $("input[name='ins_cal_permission']").val();  
                var cod_cal_permission = $("input[name='cod_cal_permission']").val();  
                var payment_option = $("#payment_option").val();

                
                
                var stateCharge = $('select[name="deliverycity"] option:selected').attr('data-deliverycharge') ?? 0;
                var extraCharge = $('select[name="deliverycity"] option:selected').attr('data-extradeliverycharge') ?? 0;
                var cod = $('select[name="deliverycity"] option:selected').attr('data-codcharge') ?? 0;
                var zoneCharge = $('select[name="deliverytown"] option:selected').attr("data-towncharge") ?? 0;
                var taxPercent = $('select[name="deliverycity"] option:selected').attr("data-tax");
            var insurancePercent = $('select[name="deliverycity"] option:selected').attr("data-insurance");
                if (payment_option == 1) {
                    var cash = convertCommaSeparatedToNumber($("input[name='package_value']").val());
                } else {

                    var cash = convertCommaSeparatedToNumber($("input[name='cod']").val());
                }
                var weight = $("input[name='weight']").val();


                extraCharge = parseInt(weight) > 1 ? (parseInt(weight) * parseInt(extraCharge)) - parseInt(
                    extraCharge) : 0;

                stateCharge = stateCharge ? stateCharge : 0;
                zoneCharge = zoneCharge ? zoneCharge : 0;
                charge = parseInt(stateCharge) + parseInt(extraCharge) + parseInt(zoneCharge);

                // check Active Plan
                var delChargeCommission = parseFloat( '{{ @$merchantSubsPlan->plan->del_crg_discount_percentage ?? 0 }}');
                // Apply discount if any
                if (delChargeCommission > 0) {
                    var discountAmount = charge * (delChargeCommission / 100);
                    charge = charge - discountAmount;
                }
                var formatCharge = CurrencyFormatted(charge);
                formatCharge = checkNan(formatCharge);
                $(".devlieryCharge").text(formatCharge);

                // console.log(parseInt(cod));
                 // Get the COD permission from the backend, default to 1 (COD allowed) if no plan
                 var codPermission = parseInt('{{ @$merchantSubsPlan->plan->cod_permission ?? 1 }}');
                 var codcharge = 0;
                if (codPermission != 0 || cod_cal_permission != 0) {
                    codcharge = parseFloat(cash) * (cod / 100);
                }
                // console.log(parseInt(cash));

                // 7.5% tax CALCULATION
                var tax = charge * (taxPercent / 100);
                var formated = CurrencyFormatted(tax);
                formated = checkNan(formated);
                $(".tax").text(formated);

                // 2% insurance CALCULATION from cash
                // Get insurance permission from backend
                var InsPermission = parseInt('{{ $merchantSubsPlan->plan->insurance_permission ?? 1 }}');
                var insurance = 0;

                if ((!isNaN(InsPermission) && InsPermission != 0) || ins_cal_permission != 0) {
                    insurance = parseFloat(cash) * (insurancePercent / 100);
                }
                
                var formated = CurrencyFormatted(insurance);
                formated = checkNan(formated);
                $(".insurance").text(formated);

                if (payment_option == 1) {
                    var total = charge + tax + insurance;
                } else {
                    var total = charge - parseInt(cash) + codcharge + tax + insurance;
                }

                total = total * -1;
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
