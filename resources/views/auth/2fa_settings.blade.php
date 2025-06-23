@extends('backEnd.layouts.master')
@section('title', 'Create Parcel')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h5 class="m-0 text-dark">Welcome !! {{ auth::user()->name }}</h5> --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">2FA</a></li>                     
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><strong>Two Factor Authentication</strong></div>
                        <div class="card-body">
                            <p>Two factor authentication (2FA) strengthens access security by requiring two methods (also
                                referred to as factors) to verify your identity. Two factor authentication protects against
                                phishing, social engineering and password brute force attacks and secures your logins from
                                attackers exploiting weak or stolen credentials.</p>

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($data['user']->loginSecurity == null)
                                <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            Generate Secret Key to Enable 2FA
                                        </button>
                                    </div>
                                </form>
                            @elseif(!$data['user']->loginSecurity->google2fa_enable)
                                1. Scan this QR code with your Google Authenticator App. Alternatively, you can use the
                                code: <code>{{ $data['secret'] }}</code><br />
                                <img src="{{ $data['google2fa_url'] }}" alt="">
                                <br /><br />
                                2. Enter the pin from Google Authenticator app:<br /><br />
                                <form class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                                        <label for="secret" class="control-label">Authenticator Code</label>
                                        <input id="secret" type="password" class="form-control col-md-4" name="secret"
                                            required>
                                        @if ($errors->has('verify-code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('verify-code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Enable 2FA
                                    </button>
                                </form>
                            @elseif($data['user']->loginSecurity->google2fa_enable)
                                <div class="alert alert-success">
                                    2FA is currently <strong>enabled</strong> on your account.
                                </div>
                                <p>If you are looking to disable Two Factor Authentication. Please confirm your password and
                                    Click Disable 2FA Button.</p>
                                <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                        <label for="change-password" class="control-label">Current Password</label>
                                        <input id="current-password" type="password" class="form-control col-md-4"
                                            name="current-password" required>
                                        @if ($errors->has('current-password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('current-password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary ">Disable 2FA</button>
                                </form>
                            @endif
                        </div>
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
