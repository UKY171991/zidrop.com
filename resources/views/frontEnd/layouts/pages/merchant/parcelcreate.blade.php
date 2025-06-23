@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title', 'Parcel Create')
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

                            <!--
                              <div class="bulk-upload">
                               <a href="" data-toggle="modal" data-target="#exampleModal"> Bulk Upload</a>
                              </div>-->

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <thead>
                                                <tr>
                                                    <td>Excel File Column Instruction <a
                                                            href="{{ asset('public/frontEnd/images/example.xlsx') }}"
                                                            download> (Sample file ) </a></td>
                                                </tr>
                                            </thead>
                                            <table class="table table-bordered table-striped mt-1">
                                                <tbody>
                                                    <tr>
                                                        <td>Customer Name</td>
                                                        <td>Product Type</td>
                                                        <td>Customer Phone</td>
                                                        <td>Cash Collection Amount</td>
                                                        <td>Customer Address</td>
                                                        <td>Delivery Zone</td>
                                                        <td>Weight</td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            <form action="{{ url('merchant/parcel/import') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="file">Upload Excel</label>
                                                    <input class="form-control" type="file" name="excel"
                                                        accept=".xlsx, .xls">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="fa fa-upload"></i> Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="addpercel-top">
                                <h3>Add New Parcel</h3>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-7 col-sm-12">
                            @if (session()->has('message'))
                                <div class="alert alert-danger">

                                    {{ session('message') }}

                                </div>
                            @endif
                            <div class="fraud-search">
                                <form action="{{ url('merchant/add/parcel') }}" method="POST" id="MerchantShipmentForm">
                                    @csrf
                                    <input type="hidden" id="ins_cal_permission" name="ins_cal_permission"
                                        value="{{ $merchantDetails->ins_cal_permission }}">
                                    <input type="hidden" id="cod_cal_permission" name="cod_cal_permission"
                                        value="{{ $merchantDetails->cod_cal_permission }}">
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

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="pickupcity select2 form-control{{ $errors->has('pickupcity') ? ' is-invalid' : '' }}"
                                                    value="{{ old('pickupcity') }}" name="pickupcity" id="pickupcity"
                                                    required="required">
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
                                                    value="{{ old('deliverycity') }}" name="deliverycity"
                                                    id="deliverycity" required="required">
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
                                                    value="{{ old('deliverytown') }}" name="deliverytown"
                                                    required="required">
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
                                                    class="select2 form-control{{ $errors->has('percelType') ? ' is-invalid' : '' }}"
                                                    value="{{ old('percelType') }}" name="percelType"
                                                    placeholder="Invoice or Memo Number" required="required">
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
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control{{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                    value="{{ old('phonenumber') }}" name="phonenumber" id="phonenumber"
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
                                        <div class="col-sm-6">
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
                                        <div class="col-sm-6" id="package_valueSHow">
                                            <div class="form-group">
                                                <input type="text" class="form-control CommaSeperateValueSet"
                                                    value="{{ old('package_value') }}" name="package_value"
                                                    value="0" min="0" placeholder="Declared Value"
                                                    id="package_value">

                                            </div>
                                        </div>

                                        <div class="col-sm-6" id="order_number">
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

                                        <div class="col-sm-6" id="productColor">
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

                                        <div class="col-sm-6" id="productQty">
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
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <button type="submit" id="submitBtn"
                                                    class="form-control">Submit</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
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

                        {{-- <div class="col-lg-1 col-md-1 col-sm-0"></div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- submenu dependency --}}
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
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("MerchantShipmentForm"); // Select form
            const submitBtn = document.getElementById("submitBtn");

            form.addEventListener("submit", function(event) {
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
                    // 1 for prepaid
                    $(".cod").val(0);
                    $(".cod").attr("readonly", true);
                    $("#package_value").attr("required", true);
                    $("#package_valueSHow").show();
                    // $("#order_number").removeClass('col-sm-4');
                    // $("#order_number").addClass('col-sm-6');
                    $("#productColor").removeClass('col-sm-6');
                    $("#productColor").addClass('col-sm-4');
                    $("#weight").removeClass('col-sm-6');
                    $("#weight").addClass('col-sm-4');
                    $("#productQty").removeClass('col-sm-6');
                    $("#productQty").addClass('col-sm-4');
                    $(".codCharge").text(CurrencyFormatted(0));
                } else {
                    $(".cod").val('');
                    $("input[name='package_value']").val('');
                    $(".cod").attr("readonly", false);
                    $("#package_value").attr("required", false);
                    $("#package_valueSHow").hide();
                    // $("#order_number").removeClass('col-sm-4');
                    // $("#order_number").addClass('col-sm-6');
                    $("#productColor").removeClass('col-sm-4');
                    $("#productColor").addClass('col-sm-6');
                    $("#weight").removeClass('col-sm-4');
                    $("#weight").addClass('col-sm-6');
                    $("#productQty").removeClass('col-sm-4');
                    $("#productQty").addClass('col-sm-6');
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
                    formated = CurrencyFormatted(0); // If permission is 0, the charge is 0
                } else {
                    var cash = convertCommaSeparatedToNumber($("input[name='cod']").val());
                    var charge = $('select[name="deliverycity"] option:selected').attr('data-codcharge');

                    // Calculate the charge based on selected city or default to 0
                    var chargeValue = charge ? parseFloat(cash) * (parseFloat(charge) / 100) : 0;
                    formated = CurrencyFormatted(chargeValue);
                }

                // Ensure the value is not NaN
                formated = checkNan(formated);

                // Get the COD permission from the backend, default to 1 (COD allowed) if no plan
                var codPermission = parseInt('{{ @$merchantSubsPlan->plan->cod_permission ?? 1 }}');
                if (codPermission == 0 && cod_cal_permission == 0) {
                    $(".codCharge").text(0); // COD allowed, no charge
                } else {
                    $(".codCharge").text(formated); // Discount or COD not allowed
                }

            }


            function devlieryCharge() {
                var formated;
                var payment_option = $("#payment_option").val();
                var ins_cal_permission = $("input[name='ins_cal_permission']").val();
                var cod_cal_permission = $("input[name='cod_cal_permission']").val();

                var stateCharge = parseInt($('select[name="deliverycity"] option:selected').attr(
                    'data-deliverycharge') || 0);
                var extraCharge = parseInt($('select[name="deliverycity"] option:selected').attr(
                    'data-extradeliverycharge') || 0);
                var cod = parseFloat($('select[name="deliverycity"] option:selected').attr('data-codcharge') || 0);
                var zoneCharge = parseInt($('select[name="deliverytown"] option:selected').attr(
                    "data-towncharge") || 0);
                var taxPercent = parseFloat($('select[name="deliverycity"] option:selected').attr("data-tax") || 0);
                var insurancePercent = parseFloat($('select[name="deliverycity"] option:selected').attr(
                    "data-insurance") || 0);

                // Get the cash value depending on payment option
                var cash = payment_option == 1 ? convertCommaSeparatedToNumber($("input[name='package_value']")
                    .val()) : convertCommaSeparatedToNumber($("input[name='cod']").val());
                cash = parseFloat(cash) || 0; // Handle NaN case by using 0 as default value

                // Weight handling for extra charge
                var weight = parseInt($("input[name='weight']").val()) || 1;
                extraCharge = weight > 1 ? (weight * extraCharge) - extraCharge : 0;

                // Calculate total delivery charge
                var charge = stateCharge + extraCharge + zoneCharge;
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

                // COD charge calculation
                // Get the COD permission from the backend, default to 1 (COD allowed) if no plan
                var codPermission = parseInt('{{ $merchantSubsPlan->plan->cod_permission ?? 1 }}');
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
                var formattedTax = CurrencyFormatted(tax);
                formattedTax = checkNan(formattedTax);
                $(".tax").text(formattedTax);

                // Get insurance permission from backend
                var InsPermission = parseInt('{{ @$merchantSubsPlan->plan->insurance_permission }}');
                if ((isNaN(InsPermission) || InsPermission == 0) && ins_cal_permission == 0) {
                    var insurance = 0;
                } else {
                    var insurance = parseFloat(cash) * (insurancePercent / 100);
                }
                var formattedInsurance = CurrencyFormatted(insurance);
                formattedInsurance = checkNan(formattedInsurance);
                // Update the UI
                $(".insurance").text(formattedInsurance);


                // Total calculation
                var total = payment_option == 1 ?
                    charge + tax + insurance :
                    charge - cash + codcharge + tax + insurance;

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
