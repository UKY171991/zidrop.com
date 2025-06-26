@extends('backEnd.layouts.master')
@section('title', 'SMTP Configuration')
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
                        <li class="breadcrumb-item active"><a href="#">SMTP Configuration</a></li>
                        <li class="breadcrumb-item active">Update</li>
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
                            <h5>Update SMTP Configuration</h5>
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
                                        <h3 class="card-title">SMTP Configuration</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form role="form" action="{{ route('superadmin.smtp.configuration.show') }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="mail_host">Mail Host</label>
                                                <input type="text"
                                                       class="form-control {{ $errors->has('mail_host') ? ' is-invalid' : '' }}"
                                                       value="{{ $smtp_configuration->mail_host ?? '' }}" name="mail_host" id="mail_host" required>
                                                @if ($errors->has('mail_host'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mail_host') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->

                                            <div class="form-group">
                                                <label for="mail_port">Mail Port</label>
                                                <input type="text"
                                                       class="form-control {{ $errors->has('mail_port') ? ' is-invalid' : '' }}"
                                                       value="{{ $smtp_configuration->mail_port ?? '' }}" name="mail_port" id="mail_port" required>
                                                @if ($errors->has('mail_port'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mail_port') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->

                                            <div class="form-group">
                                                <label for="mail_username">Mail Username</label>
                                                <input type="text"
                                                       class="form-control {{ $errors->has('mail_username') ? ' is-invalid' : '' }}"
                                                       value="{{ $smtp_configuration->mail_username ?? '' }}" name="mail_username" id="mail_username" required>
                                                @if ($errors->has('mail_username'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mail_username') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->                                            <div class="form-group">
                                                <label for="mail_password">Mail Password</label>
                                                <div class="input-group">
                                                    <input type="password"
                                                           class="form-control {{ $errors->has('mail_password') ? ' is-invalid' : '' }}"
                                                           value="{{ $smtp_configuration->mail_password ?? '' }}" 
                                                           name="mail_password" 
                                                           id="mail_password" 
                                                           placeholder="{{ isset($smtp_configuration->mail_password_display) ? $smtp_configuration->mail_password_display : 'Enter mail password' }}"
                                                           required>
                                                    <!-- <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('mail_password')">
                                                            <i class="fa fa-eye" id="mail_password-icon"></i>
                                                        </button>
                                                    </div> -->
                                                </div>
                                                @if ($errors->has('mail_password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mail_password') }}</strong>
                                                    </span>
                                                @endif
                                                @if(isset($smtp_configuration->mail_password_display))
                                                    <small class="text-muted">Current value: {{ $smtp_configuration->mail_password_display }}</small>
                                                @endif
                                            </div>
                                            <!-- form group -->

                                            <div class="form-group">
                                                <label for="mail_encryption">Mail Encryption</label>
                                                <select name="mail_encryption" id="mail_encryption"
                                                        class="form-control">
                                                    <option value="tls" {{ ($smtp_configuration->mail_encryption == 'tls')?'selected':'' }}>tls</option>
                                                    <option value="ssl" {{ ($smtp_configuration->mail_encryption == 'ssl')?'selected':'' }}>ssl</option>
                                                </select>
                                                @if ($errors->has('mail_encryption'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mail_encryption') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->

                                            <div class="form-group">
                                                <label for="mail_from_address">Mail From Address</label>
                                                <input type="text"
                                                       class="form-control {{ $errors->has('mail_from_address') ? ' is-invalid' : '' }}"
                                                       value="{{ $smtp_configuration->mail_from_address ?? '' }}" name="mail_from_address" id="mail_from_address" required>
                                                @if ($errors->has('mail_from_address'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mail_from_address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->

                                            <div class="form-group">
                                                <label for="mail_from_name">Mail From Name</label>
                                                <input type="text"
                                                       class="form-control {{ $errors->has('mail_from_name') ? ' is-invalid' : '' }}"
                                                       value="{{ $smtp_configuration->mail_from_name ?? '' }}" name="mail_from_name" id="mail_from_name" required>
                                                @if ($errors->has('mail_from_name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mail_from_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- form group -->

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
        document.getElementById('mail_password').addEventListener('focus', function() {
            if (this.value === '') {
                this.placeholder = '';
            }
        });
    </script>
@endsection
