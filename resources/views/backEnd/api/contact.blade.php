@extends('backEnd.layouts.master')
@section('title', 'Create API Information')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <!-- <div class="col-sm-6">
                    <h5 class="m-0 text-dark">Welcome !! {{ auth::user()->name }}</h5>
                </div> -->
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="#">API Information</a></li>
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
                            <h5>Create Payment API Information</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="box-content">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card card-primary">
                                    <!-- <div class="card-header">
                                        <h3 class="card-title">Payment API Info</h3>
                                    </div> -->
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form role="form" action="{{ url('editor/api_info/store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="secret">Secret Key</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('secret') ? ' is-invalid' : '' }}"
                                                    value="{{ $api_info->secret ?? '' }}" name="secret" id="secret">
                                                @if ($errors->has('secret'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('secret') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="public">Public Key</label>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('public') ? ' is-invalid' : '' }}"
                                                    value="{{ $api_info->public ?? '' }}" name="public" id="public">
                                                @if ($errors->has('public'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('public') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group float-right">
                                                <button type="submit" style="background-color: #af251b; border:#af251b" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <!-- <div class="card-footer float-right">
                                            <button type="submit" style="background-color: #af251b; border:#af251b" class="btn btn-primary">Save Changes</button>
                                        </div> -->
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
