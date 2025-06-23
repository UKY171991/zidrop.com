@extends('backEnd.layouts.master')
@section('title', 'Create Parcel')
@section('content')
    <div class="content-header">
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
    </div>


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
                            <div class="col-sm-2"></div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Add Parcel Infoo</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form role="form" action="{{ url('editor/parcel/store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="orderType">State</label>
                                                <select
                                                    class="form-control select2{{ $errors->has('orderType') ? ' is-invalid' : '' }}"
                                                    value="{{ old('orderType') }}" name="orderType" required='required'>
                                                    <option value="">Select...</option>
                                                    @foreach ($delivery as $deliveryopton)
                                                        <option value="{{ $deliveryopton->id }}">{{ $deliveryopton->title }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('orderType'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('orderType') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="reciveZone">Area</label>
                                                <select type="text"
                                                    class="select2 form-control{{ $errors->has('reciveZone') ? ' is-invalid' : '' }}"
                                                    value="{{ old('reciveZone') }}" name="reciveZone"
                                                    placeholder="Delivery Area" required="required">
                                                    <option value="">Delivery Area...</option>
                                                    
                                                </select>
                                                @if ($errors->has('reciveZone'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('reciveZone') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label for="">Parcel Type</label>
                                                    <select type="text"
                                                        class="form-control{{ $errors->has('parcelType') ? ' is-invalid' : '' }}"
                                                        value="{{ old('parcelType') }}" name="parcelType"
                                                        placeholder="Invoice or Memo Number" required="required">
                                                        <option value="">Select...</option>
                                                        <option value="1">Reguler</option>
                                                        <option value="2">Liquid</option>
                                                    </select>
                                                    @if ($errors->has('parcelType'))
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('parcelType') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>


                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="merchantId">Merchant</label>
                                                <select
                                                    class="form-control select2{{ $errors->has('merchantId') ? ' is-invalid' : '' }}"
                                                    value="{{ old('merchantId') }}" name="merchantId" required='required'>
                                                    <option value="">Select Merchant</option>

                                                    @foreach ($merchants as $value)
                                                        <option value="{{ $value->id }}">{{ $value->lastName }}
                                                            ({{ $value->phoneNumber }}) _ ({{ $value->companyName }}) _
                                                            ({{ $value->firstName }})</option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('merchantId'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('merchantId') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            <!-- form group -->

                                            <div class="form-group">
                                                <label for="cod">COD Amount</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('cod') ? ' is-invalid' : '' }}"
                                                    value="{{ old('cod') }}" name="cod" id="cod"
                                                    placeholder="Cash amount including delivery charge" required='required'>
                                                @if ($errors->has('cod'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('cod') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    value="{{ old('name') }}" name="name" id="name"
                                                    placeholder="Recipient Name" required='required'>
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="productName">Product Name</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('productName') ? ' is-invalid' : '' }}"
                                                    value="{{ old('productName') }}" name="productName" id="productName"
                                                    placeholder="Product Name" required>
                                                @if ($errors->has('productName'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('productName') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="weight">Weight</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }}"
                                                    value="{{ old('weight') }}" name="weight" id="weight"
                                                    placeholder="Product Weight" required='required'>
                                                @if ($errors->has('weight'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('weight') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                             <!-- form group -->
                                            <div class="form-group">
                                                <label for="productColor">Product Color</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('productColor') ? ' is-invalid' : '' }}"
                                                    value="{{ old('productColor') }}" name="productColor" id="productColor"
                                                    placeholder="Product Color" required='required'>
                                                @if ($errors->has('productColor'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('productColor') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="productQty">Product Quantity</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('productQty') ? ' is-invalid' : '' }}"
                                                    value="{{ old('productQty') }}" name="productQty" id="productQty"
                                                    placeholder="Product Quantity" required>
                                                @if ($errors->has('productQty'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('productQty') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="address">Address (maximum 500 characters)</label>
                                                <textarea type="text" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                    value="{{ old('address') }}" name="address" placeholder="Recipient Aderess" required='required'></textarea>


                                                @if ($errors->has('address'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="phonenumber">Phone Number</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                    value="{{ old('phonenumber') }}" name="phonenumber" id="phonenumber"
                                                    placeholder="Phone Number" required='required'>
                                                @if ($errors->has('phonenumber'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phonenumber') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="note">Note (maximum 300 characters)</label>
                                                <textarea type="text" class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}"
                                                    value="{{ old('note') }}" name="note" placeholder="Note Optional" required='required'></textarea>
                                                @if ($errors->has('note'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('note') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- submenu dependency --}}
    <script type="text/javascript">
      $(document).ready(function() {
          $('select[name="orderType"]').on('change', function() {
              var orderType = $(this).val();
              if (orderType) {
                  $.ajax({
                      url: "{{ url('/get-area/') }}/" + orderType,
                      type: "GET",
                      dataType: "json",
                      success: function(data) {
                          var d = $('select[name="reciveZone"]').empty();
                          $.each(data, function(key, value) {
                              $('select[name="reciveZone"]').append(
                                  '<option value="' + value.id + '">' + value
                                  .zonename + '</option>');
                          });
                      },
                  });
              } else {
                  alert('danger');
              }
          });
      });
  </script>
@endsection
