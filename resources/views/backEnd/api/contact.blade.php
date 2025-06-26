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
                                        <div class="card-body">                                            <div class="form-group">
                                                <label for="secret">Secret Key</label>
                                                <div class="input-group">
                                                    <input type="password"
                                                        class="form-control {{ $errors->has('secret') ? ' is-invalid' : '' }}"
                                                        value="{{ $api_info->secret ?? '' }}" name="secret" id="secret"
                                                        placeholder="{{ isset($api_info->secret_display) ? $api_info->secret_display : 'Enter secret key' }}">
                                                    <!-- <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('secret')">
                                                            <i class="fa fa-eye" id="secret-icon"></i>
                                                        </button>
                                                    </div> -->
                                                </div>
                                                @if ($errors->has('secret'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('secret') }}</strong>
                                                    </span>
                                                @endif
                                                @if(isset($api_info->secret_display))
                                                    <small class="text-muted">Current value: {{ $api_info->secret_display }}</small>
                                                @endif
                                            </div>                                            <!-- form group -->
                                            <div class="form-group">
                                                <label for="public">Public Key</label>
                                                <div class="input-group">
                                                    <input type="password"
                                                        class="form-control {{ $errors->has('public') ? ' is-invalid' : '' }}"
                                                        value="{{ $api_info->public ?? '' }}" name="public" id="public"
                                                        placeholder="{{ isset($api_info->public_display) ? $api_info->public_display : 'Enter public key' }}">
                                                    <!-- <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('public')">
                                                            <i class="fa fa-eye" id="public-icon"></i>
                                                        </button>
                                                    </div> -->
                                                </div>
                                                @if ($errors->has('public'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('public') }}</strong>
                                                    </span>
                                                @endif
                                                @if(isset($api_info->public_display))
                                                    <small class="text-muted">Current value: {{ $api_info->public_display }}</small>
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
            </div>        </div>
    </section>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Clear placeholder when user starts typing
        document.getElementById('secret').addEventListener('focus', function() {
            if (this.value === '') {
                this.placeholder = '';
            }
        });
        
        document.getElementById('public').addEventListener('focus', function() {
            if (this.value === '') {
                this.placeholder = '';
            }
        });
    </script>
@endsection
