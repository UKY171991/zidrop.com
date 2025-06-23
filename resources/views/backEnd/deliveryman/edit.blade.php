@extends('backEnd.layouts.master')
@section('title', 'Edit Deliveryman Info')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="box-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="manage-button">
                                    <div class="body-title">
                                        <h5>Edit Deliveryman Info</h5>
                                    </div>
                                    <div class="quick-button">
                                        <a href="{{ url('editor/deliveryman/manage') }}"
                                            class="btn btn-primary btn-actions btn-create">
                                            Manage Deliveryman Info
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Deliveryman Info</h3>
                                    </div>
                                    <div class="profile-edit mrt-30">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <nav class="custom-tab-menu">
                                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                        <a class="nav-item nav-link active" data-toggle="tab"
                                                            href="#deliverymaninformation">Deliveryman Information</a>
                                                        <a class="nav-item nav-link" data-toggle="tab"
                                                            href="#bankaccount">Bank Account</a>
                                                        {{-- <a class="nav-item nav-link" data-toggle="tab" href="#otheraccount">Other Account</a> --}}
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- form start -->
                                    <form action="{{ url('editor/deliveryman/update') }}" method="POST"
                                        enctype="multipart/form-data" name="editForm">
                                        @csrf
                                        <input type="hidden" value="{{ $edit_data->id }}" name="hidden_id">
                                        <div class="tab-content customt-tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="deliverymaninformation"
                                                role="tabpanel">
                                                <h3 class="title text-center">Deliveryman Information</h3>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="name">Name</label>
                                                            <input type="text" name="name" id="name"
                                                                class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                value="{{ $edit_data->name }}">
                                                            @if ($errors->has('name'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('name') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <!-- column end -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="email" name="email" id="email"
                                                                class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                value="{{ $edit_data->email }}">
                                                            @if ($errors->has('email'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('email') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- column end -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="phone">Phone</label>
                                                            <input type="text" name="phone" id="phone"
                                                                class="form-control pr-5 {{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                                value="{{ $edit_data->phone }}">
                                                            <div class="del_nigeria_flag"></div>
                                                            @if ($errors->has('phone'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('phone') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- column end -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="designation">Designation</label>
                                                            <input type="text" name="designation" id="designation"
                                                                class="form-control {{ $errors->has('designation') ? ' is-invalid' : '' }}"
                                                                value="{{ $edit_data->designation }}">
                                                            @if ($errors->has('designation'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('designation') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- column end -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="cities_id">City</label>
                                                            <select name="cities_id" id="cities_id"
                                                                class="select2 form-control {{ $errors->has('cities_id') ? ' is-invalid' : '' }}"
                                                                value="{{ old('cities_id') }}">
                                                                <option value="">Select...</option>
                                                                @foreach ($wcities as $key => $value)
                                                                    <option value="{{ $value->id }}"
                                                                        {{ $edit_data->cities_id == $value->id ? 'selected' : '' }}>
                                                                        {{ $value->title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('cities_id'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('cities_id') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- column end -->

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="commisiontype">Commision Type</label>
                                                            <select name="commisiontype" id="commisiontype"
                                                                class="form-control {{ $errors->has('commisiontype') ? ' is-invalid' : '' }} select2">
                                                                <option value="">Select...</option>
                                                                <option value="1"
                                                                    {{ old('commisiontype', $edit_data->commisiontype) == 2 ? 'selected' : '' }}>
                                                                    Flat Rate</option>
                                                                <option value="2"
                                                                    {{ old('commisiontype', $edit_data->commisiontype) == 2 ? 'selected' : '' }}>
                                                                    Percent</option>
                                                            </select>
                                                            @if ($errors->has('commisiontype'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('commisiontype') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- column end -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="commision">Commision</label>
                                                            <input type="number" name="commision" id="commision"
                                                                class="form-control {{ $errors->has('commision') ? ' is-invalid' : '' }}"
                                                                value="{{ $edit_data->commision }}" step="any">
                                                            @if ($errors->has('commision'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('commision') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- column end -->

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="image">Image</label>
                                                            <input type="file" name="image" id="image"
                                                                class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}"
                                                                value="{{ old('image') }}">
                                                            <img src="{{ asset($edit_data->image) }}"
                                                                class="backend_image" alt="">
                                                            @if ($errors->has('image'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('image') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- column end -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="custom-label">
                                                                <label>Publication Status</label>
                                                            </div>
                                                            <div class="box-body pub-stat display-inline">
                                                                <input
                                                                    class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}"
                                                                    type="radio" id="active" name="status"
                                                                    value="1"
                                                                    {{ $edit_data->status == 1 ? 'checked' : '' }}>
                                                                <label for="active">Active</label>
                                                                @if ($errors->has('status'))
                                                                    <span class="invalid-feedback">
                                                                        <strong>{{ $errors->first('status') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="box-body pub-stat display-inline">
                                                                <input
                                                                    class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}"
                                                                    type="radio" name="status" value="0"
                                                                    id="inactive"
                                                                    {{ $edit_data->status == 0 ? 'checked' : '' }}>
                                                                <label for="inactive">Inactive</label>
                                                                @if ($errors->has('status'))
                                                                    <span class="invalid-feedback">
                                                                        <strong>{{ $errors->first('status') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            {{-- Bank Tab --}}
                                            <div class="tab-pane fade " id="bankaccount" role="tabpanel">
                                                <h3 class="title text-center">Bank Account</h3>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Name Of Bank</label>
                                                            <input type="text" name="nameOfBank" value="{{ $edit_data->nameOfBank }}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Branch</label>
                                                            <input type="text" name="bankBranch" value="{{ $edit_data->bankBranch }}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>A/C Holder Name</label>
                                                            <input type="text" name="bankAcHolder" value="{{ $edit_data->bankAcHolder }}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Beneficiary Bank Code</label>
                                                            <input type="text" name="beneficiary_bank_code" value="{{ $edit_data->beneficiary_bank_code }}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Bank A/C No</label>
                                                            <input type="text" name="bankAcNo" value="{{ $edit_data->bankAcNo }}" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- col end -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        document.forms['editForm'].elements['state'].value = "{{ $edit_data->state }}"
        document.forms['editForm'].elements['commisiontype'].value = "{{ $edit_data->commisiontype }}"
        document.forms['editForm'].elements['status'].value = "{{ $edit_data->status }}"
    </script>
@endsection
