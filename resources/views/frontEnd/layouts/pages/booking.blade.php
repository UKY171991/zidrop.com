@extends('frontEnd.layouts.master')
@section('title','Booking')
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
                            <li><a href="/">Home</a></li>
                            <li><a href=""> Booking</a></li>
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


<section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row addpercel-inner">
                        <div class="col-sm-12">

                            <div class="accordion" id="accordionExample">
                                <div id="fx-appNav" class="clearfix ishp_app_nav"><h1>ZiDrop Ship Manager<sup>®</sup> Lite</h1></div>

                                <form action="{{ url('booking-parcel') }}" method="POST">
                                    @csrf
                                    <!-- Start step  1 -->
                                    <div class="card">
                                        <div class="card-header accordionHead" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Address Information
                                            </button>
                                        </h2>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <h3 class="fx-title" style="outline:0" tabindex="0">Enter your (From) address and the recipient's (To) address.</h3>
                                                    <div class="col-md-6">
                                                        <div id="fx-from-legend" class="fxlay-section-legend fxis-collapsible">From Address</div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <input type="text"
                                                                            class="form-control{{ $errors->has('sender_name') ? ' is-invalid' : '' }}"
                                                                            value="{{ old('sender_name') }}" name="sender_name" required
                                                                            placeholder="Customer Name" required="required">
                                                                        @if ($errors->has('sender_name'))
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $errors->first('sender_name') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
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
                                                                <div class="col-sm-12">
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
                                                                <div class="col-sm-12">
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
                                                               
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <input type="number"
                                                                            class="form-control{{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                                            value="{{ old('phonenumber') }}" name="phonenumber"
                                                                            placeholder="Customer Phone Number" required="required">
                                                                        @if ($errors->has('phonenumber'))
                                                                            <span class="invalid-feedback">
                                                                                <strong>{{ $errors->first('phonenumber') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-sm-12">
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
                                                                <div class="col-sm-12">
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
                                                            </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                    <div id="fx-to-legend" class="fxlay-section-legend fxis-expandable">To Address</div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                        value="{{ old('name') }}" name="r_name" required
                                                                        placeholder="Recipient Name" required="required">
                                                                    @if ($errors->has('name'))
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('name') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            
                                                    
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <select type="text"
                                                                        class="deliverycity select2 form-control{{ $errors->has('deliverycity') ? ' is-invalid' : '' }}"
                                                                        value="{{ old('deliverycity') }}" name="deliverycity" id="deliverycity"
                                                                        required="required">
                                                                        <option value="" selected="selected">Delivery City</option>

                                                                    </select>
                                                                    @if ($errors->has('deliverycity'))
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('deliverycity') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
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
                                                            
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <input type="number"
                                                                        class="form-control{{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                                        value="{{ old('phonenumber') }}" name="r_phonenumber"
                                                                        placeholder="Recipient Phone Number" required="required">
                                                                    @if ($errors->has('phonenumber'))
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('phonenumber') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <textarea type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                                        value="{{ old('address') }}" name="r_address" placeholder="Customer Full Address" required="required"></textarea>
                                                                    @if ($errors->has('address'))
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('address') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <textarea type="text" name="r_note" value="{{ old('note') }}" class="form-control" placeholder="Note"
                                                                        required="required"></textarea>
                                                                    @if ($errors->has('note'))
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('note') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start step  2 -->
                                    <div class="card">
                                        <div class="card-header accordionHead" id="headingThree">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Parcel Details
                                            </button>
                                        </h2>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <select type="text"
                                                                class="select2 form-control {{ $errors->has('percelType') ? ' is-invalid' : '' }}"
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
                                                                class="form-control{{ $errors->has('productName') ? ' is-invalid' : '' }}"
                                                                value="{{ old('productName') }}" name="productName"
                                                                placeholder="Recipient Product Name" required="required">
                                                            @if ($errors->has('productName'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('productName') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <input type="number"
                                                                class="calculate cod form-control{{ $errors->has('cod') ? ' is-invalid' : '' }}"
                                                                value="{{ old('cod') }}" name="cod" min="0"
                                                                placeholder="Cash Collection Amount" required="required">
                                                            @if ($errors->has('cod'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('cod') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6" id="package_valueSHow">
                                                        <div class="form-group">
                                                            <input type="number" class="form-control"
                                                                value="{{ old('package_value') }}" name="package_value"
                                                                value="0" min="0" placeholder="Declared Value"
                                                                id="package_value">

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
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

                                                    <div class="col-sm-6">
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

                                                    <div class="col-sm-6">
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
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start step 2 -->

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--
                  <div class="bulk-upload">
                   <a href="" data-toggle="modal" data-target="#exampleModal"> Bulk Upload</a>
                  </div>-->

                           
                        </div>

                        <?php /* ?>
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
                                <form action="{{ url('booking-parcel') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <!-- <div class="col-sm-6">
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
                                        </div> -->

                                        <!-- <div class="col-sm-6">
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
                                        </div> -->
                                        {{-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden" name="valstateCharge" id="valStateCharge">
                                                <input type="hidden" name="valzoneCharge" id="valZoneCharge">
                                                <input type="hidden" name="valcodCharge" id="valCodCharge">
                                                <input type="hidden" name="valtax" id="valTax">
                                                <input type="hidden" name="valinsurance" id="valInsurance">

                                                <select type="text"
                                                    class="package form-control{{ $errors->has('package') ? ' is-invalid' : '' }}"
                                                    value="{{ old('package') }}" name="package" required="required">
                                                    <option value="" selected="selected">State</option>
                                                    @foreach ($delivery as $key => $value)
                                                        <option value="{{ $value->id }}"> {{ $value->title }} </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('package'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('package') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <select type="text"
                                                class="reciveZone select2 form-control{{ $errors->has('reciveZone') ? ' is-invalid' : '' }}"
                                                value="{{ old('reciveZone') }}" name="reciveZone" id="reciveZone"
                                                placeholder="Delivery Area" required="required">
                                                <option value="" selected="selected">Delivery Area...</option>

                                            </select>
                                            @if ($errors->has('reciveZone'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('reciveZone') }}</strong>
                                                </span>
                                            @endif
                                        </div> --}}
                                        <!-- <div class="col-sm-6">
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
                                        </div> -->
                                        <!-- <div class="col-sm-6">
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
                                        </div> -->
                                        {{-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="pickupOrdropOff form-control{{ $errors->has('pickupOrdropOff') ? ' is-invalid' : '' }}"
                                                    value="{{ old('pickupOrdropOff') }}" name="pickupOrdropOff" id="pickupOrdropOff" required="required">
                                                    <option value="" selected="selected">Pickup Or Drop Off</option>
                                                    <option value="1">Pickup From My Address </option>
                                                    <option value="2">Drop Off In RSE Location</option>
                                                   
                                                </select>
                                                @if ($errors->has('pickupOrdropOff'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('pickupOrdropOff') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-sm-6">
                                            <div id="pickOrdropElement">
                                                <div class="form-group">
                                                    <select type="text"
                                                    class="addressofSender form-control{{ $errors->has('addressofSender') ? ' is-invalid' : '' }}"
                                                    value="{{ old('addressofSender') }}" name="addressofSender" id="addressofSender" required="required">
                                                    <option value="" selected="selected">Address Of Sender</option> 
                                                </select>
                                                    @if ($errors->has('addressofSender'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('addressofSender') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div> --}}
                                        <!-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <select type="text"
                                                    class="deliverycity select2 form-control{{ $errors->has('deliverycity') ? ' is-invalid' : '' }}"
                                                    value="{{ old('deliverycity') }}" name="deliverycity" id="deliverycity"
                                                    required="required">
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
                                                <input type="number"
                                                    class="form-control{{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                    value="{{ old('phonenumber') }}" name="phonenumber"
                                                    placeholder="Customer Phone Number" required="required">
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
                                                <input type="number"
                                                    class="calculate cod form-control{{ $errors->has('cod') ? ' is-invalid' : '' }}"
                                                    value="{{ old('cod') }}" name="cod" min="0"
                                                    placeholder="Cash Collection Amount" required="required">
                                                @if ($errors->has('cod'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('cod') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div> -->
                                        <style>
                                            #package_valueSHow {
                                                display: none;
                                            }
                                        </style>
                                        <!-- <div class="col-sm-6" id="package_valueSHow">
                                            <div class="form-group">
                                                <input type="number" class="form-control"
                                                    value="{{ old('package_value') }}" name="package_value"
                                                    value="0" min="0" placeholder="Declared Value"
                                                    id="package_value">

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
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

                                        <div class="col-sm-6">
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

                                        <div class="col-sm-6">
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
                                        </div> -->
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <button type="submit" class="form-control">Submit</button>
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
                        </div>
                        */ ?>

                        {{-- <div class="col-lg-1 col-md-1 col-sm-0"></div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

<!--Booking  start-->


<!--Booking end-->

@endsection

@section('custom_js_script')
    <script type="text/javascript">
       
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
                var cod = $("input[name='cod']").val();
                var formated = CurrencyFormatted(cod);
                var formated = checkNan(formated);
                $(".cashCollection").text(formated);
                codUpdate();
                devlieryCharge();
            })
            $("input[name='package_value']").on("keyup", function() {
                var cod = $("input[name='package_value']").val();
                var formated = CurrencyFormatted(cod);
                var formated = checkNan(formated);
                $(".cashCollection").text('00');
                codUpdate();
                devlieryCharge();
            })

            function codUpdate() {
                var cash = $("input[name='cod']").val();
                var charge = $('select[name="deliverycity"] option:selected').attr('data-codcharge');
                charge = charge ? parseFloat(cash) * (parseFloat(charge) / 100) : 0;
                var formated = CurrencyFormatted(charge);
                formated = checkNan(formated);
                $(".codCharge").text(formated);
            }

            function devlieryCharge() {
                var payment_option = $("#payment_option").val();

                
                
                var stateCharge = $('select[name="deliverycity"] option:selected').attr('data-deliverycharge') ?? 0;
                var extraCharge = $('select[name="deliverycity"] option:selected').attr('data-extradeliverycharge') ?? 0;
                var cod = $('select[name="deliverycity"] option:selected').attr('data-codcharge') ?? 0;
                var zoneCharge = $('select[name="deliverytown"] option:selected').attr("data-towncharge") ?? 0;
                var taxPercent = $('select[name="deliverycity"] option:selected').attr("data-tax");
            var insurancePercent = $('select[name="deliverycity"] option:selected').attr("data-insurance");
                if (payment_option == 1) {
                    var cash = $("input[name='package_value']").val();
                } else {

                    var cash = $("input[name='cod']").val();
                }
                var weight = $("input[name='weight']").val();


                extraCharge = parseInt(weight) > 1 ? (parseInt(weight) * parseInt(extraCharge)) - parseInt(
                    extraCharge) : 0;

                stateCharge = stateCharge ? stateCharge : 0;
                zoneCharge = zoneCharge ? zoneCharge : 0;
                charge = parseInt(stateCharge) + parseInt(extraCharge) + parseInt(zoneCharge);
                var formatCharge = CurrencyFormatted(charge);
                formatCharge = checkNan(formatCharge);
                $(".devlieryCharge").text(formatCharge);

                // console.log(parseInt(cod));
                var codcharge = parseFloat(cash) * (parseFloat(cod) / 100);
                // console.log(parseInt(cash));

                // 7.5% tax CALCULATION
                var tax = charge * (taxPercent / 100);
                var formated = CurrencyFormatted(tax);
                formated = checkNan(formated);
                $(".tax").text(formated);

                // 2% insurance CALCULATION from cash
                var insurance = cash ? parseInt(cash) * (insurancePercent / 100) : 0;
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