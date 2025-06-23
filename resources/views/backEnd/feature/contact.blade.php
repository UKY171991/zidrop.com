@extends('backEnd.layouts.master')
@section('title', 'Create Contact Information')
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
                        <li class="breadcrumb-item active"><a href="#">Contact Information</a></li>
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
                            <h5>Create Contact Information</h5>
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
                                        <h3 class="card-title">Contact Info</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form role="form" action="{{ url('editor/contact_info/store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                    value="{{ $contact_info->email ?? '' }}" name="email" id="email">
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="phone1">First Phone</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('phone1') ? ' is-invalid' : '' }}"
                                                    value="{{ $contact_info->phone1 ?? '' }}" name="phone1" id="phone1">
                                                @if ($errors->has('phone1'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone1') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="phone2">Secomd Phone</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('phone2') ? ' is-invalid' : '' }}"
                                                    value="{{ $contact_info->phone2 ?? '' }}" name="phone2" id="phone2">
                                                @if ($errors->has('phone2'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone2') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <textarea type="text" class="summernote form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                    value="" name="address">{{ $contact_info->address ?? '' }}</textarea>
                                                @if ($errors->has('address'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
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
@endsection
