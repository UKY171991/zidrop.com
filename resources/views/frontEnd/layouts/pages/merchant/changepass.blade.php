@extends('frontEnd.layouts.pages.merchant.merchantmaster') 
@section('title','Parcel') 
@section('content')

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 style="text-align:center; margin-bottom:20px; margin-top:20px;">Change Password</h2>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="custom-bg">
                    <form action="{{url('auth/merchant/password/change')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="old_password" class="col-form-label text-md-right">Old Password</label>
                                    <input id="old_password" type="password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password" />
                                    @if ($errors->has('old_password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!-- form group end -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="new_password" class="col-form-label text-md-right">New Password</label>
                                    <input id="new_password" type="password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" name="new_password" />

                                    @if ($errors->has('new_password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!-- form group end -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="password">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" />
                                    @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!-- form group end -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">submit</button>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>

                        <!-- /.col -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
