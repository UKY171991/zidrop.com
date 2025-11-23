@extends('backEnd.layouts.master')
@section('title', 'Edit Merchant Profile')
@section('extracss')
    <link rel="stylesheet" href="{{ asset('frontEnd/css/packages.css') }}">
@endsection
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
                                        <h5>Edit Merchant Profile</h5>
                                    </div>
                                    <div class="quick-button">
                                        <a href="{{ url('author/merchant/manage') }}"
                                            class="btn btn-primary btn-actions btn-create">
                                            Manage User
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Merchant Profile</h3>
                                    </div>
                                    <div class="profile-edit mrt-30">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <nav class="custom-tab-menu">
                                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                        <a class="nav-item nav-link" data-toggle="tab"
                                                            href="#companyinformation">Company Information</a>
                                                        <a class="nav-item nav-link active" data-toggle="tab"
                                                            href="#ownerinformation">Owner Information</a>
                                                        <a class="nav-item nav-link" data-toggle="tab"
                                                            href="#pickupmethod">Pickup Method</a>
                                                        <a class="nav-item nav-link" data-toggle="tab"
                                                            href="#paymentmethod">Payment Method</a>
                                                        <a class="nav-item nav-link" data-toggle="tab"
                                                            href="#bankaccount">Bank Account</a>
                                                        <a class="nav-item nav-link" data-toggle="tab"
                                                            href="#subscriptions">Subscriptions</a>

                                                        {{-- <a class="nav-item nav-link" data-toggle="tab" href="#otheraccount">Other Account</a> --}}
                                                    </div>
                                                </nav>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <form action="{{ url('author/merchant/profile/edit') }}" method="POST"
                                                    name="editForm" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" value="{{ $merchantInfo->id }}" name="hidden_id">
                                                    <div class="tab-content customt-tab-content" id="nav-tabContent">
                                                        <div class="tab-pane fade" id="companyinformation" role="tabpanel">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <p class="title">Business Information</p>
                                                                    <div class="row">
                                                                        <div class="col-sm-3">
                                                                            <p>Company Name</p>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text" name="companyName"
                                                                                value="{{ $merchantInfo->companyName }}"
                                                                                class="form-control">
                                                                            {{--                                                      <p><strong>{{$merchantInfo->companyName}}</strong></p> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade show active" id="ownerinformation"
                                                            role="tabpanel">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <p class="title">Owner Information</p>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-3">
                                                                            <p>Name</p>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <p><strong>{{ $merchantInfo->firstName }}
                                                                                    {{ $merchantInfo->lastName }}</strong>
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-sm-3">
                                                                            <p>Mobile Number</p>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text" name="phoneNumber"
                                                                                id="phoneNumber"
                                                                                value="{{ $merchantInfo->phoneNumber }}"
                                                                                class="form-control pr-5">
                                                                            <div class="mer_nigeria_flag"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-3">
                                                                            <p>Email</p>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="email" name="emailAddress"
                                                                                value="{{ $merchantInfo->emailAddress }}"
                                                                                class="form-control">
                                                                            {{--                                                      {{$merchantInfo->emailAddress}} --}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-lg-3 col-md-4 col-sm-4">
                                                                            <p>Image</p>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-8 col-sm-8">
                                                                            <input type="file" name="logo"
                                                                                class="form-control">
                                                                            <img src="{{ asset($merchantInfo->logo) }}"
                                                                                width="60 " alt="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="custom-control custom-switch">
                                                                            <input type="checkbox"
                                                                                class="custom-control-input"
                                                                                id="ins_cal_permission"
                                                                                name="ins_cal_permission"
                                                                                value="{{ $merchantInfo->ins_cal_permission }}"
                                                                                {{ $merchantInfo->ins_cal_permission == 1 ? 'checked' : '' }} disabled >
                                                                                <input type="hidden" name="ins_cal_permission" value="{{ $merchantInfo->ins_cal_permission }}">
                                                                            <label class="custom-control-label"
                                                                                for="ins_cal_permission">Insurance
                                                                                Calculation Permission</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="custom-control custom-switch">
                                                                            <input type="checkbox"
                                                                                class="custom-control-input"
                                                                                id="cod_cal_permission"
                                                                                name="cod_cal_permission"
                                                                                value="{{ $merchantInfo->cod_cal_permission }}"
                                                                                {{ $merchantInfo->cod_cal_permission == 1 ? 'checked' : '' }} disabled>
                                                                                        <input type="hidden" name="cod_cal_permission" value="{{ $merchantInfo->cod_cal_permission }}">
                                                                            <label class="custom-control-label"
                                                                                for="cod_cal_permission">COD Calculation
                                                                                Permission</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-3">
                                                                            <p></p>
                                                                        </div>
                                                                        <div class="col-sm-3"><input type="submit"
                                                                                value="Update"class="common-btn"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade " id="pickupmethod" role="tabpanel">
                                                            <p class="title">Pickup Method</p>
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Pickup Address</p>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <textarea name="pickLocation" class="form-control">{{ $merchantInfo->pickLocation }}</textarea>
                                                                </div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Nearest Zone</p>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select type="text" name="nearestZone"
                                                                        class="form-control">
                                                                        <option value=""></option>
                                                                        @foreach ($nearestzones as $key => $value)
                                                                            <option value="{{ $value->id }}">
                                                                                {{ $value->zonename }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Pickup Preference</p>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select type="text" name="pickupPreference"
                                                                        class="form-control">
                                                                        <option value="1">As Per Request</option>
                                                                        <option value="2">Daily</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p></p>
                                                                </div>
                                                                <div class="col-sm-3"><input type="submit"
                                                                        value="Update"class="common-btn"></div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="paymentmethod" role="tabpanel">
                                                            <p class="title">Payment Method</p>
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Default Payment</p>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select type="text" name="paymentMethod"
                                                                        class="form-control">
                                                                        <option value="1">Bank</option>
                                                                        <option value="2">Bkash</option>
                                                                        <option value="3">Roket</option>
                                                                        <option value="4">Nogod</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Withdrawal</p>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select type="text" name="withdrawal"
                                                                        class="form-control">
                                                                        <option value="1">As Per Request</option>
                                                                        <option value="2">Daily</option>
                                                                        <option value="3">Weekly</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p></p>
                                                                </div>
                                                                <div class="col-sm-3"><input type="submit"
                                                                        value="Update"class="common-btn"></div>
                                                            </div>
                                                            <!-- form group end -->
                                                        </div>
                                                        <div class="tab-pane fade " id="bankaccount" role="tabpanel">
                                                            <p class="title">Bank Account</p>
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Name Of Bank</p>
                                                                </div>
                                                                <div class="col-sm-3"><input type="text"
                                                                        name="nameOfBank"
                                                                        value="{{ $merchantInfo->nameOfBank }}"
                                                                        class="form-control"></div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Branch</p>
                                                                </div>
                                                                <div class="col-sm-3"><input type="text"
                                                                        name="bankBranch"
                                                                        value="{{ $merchantInfo->bankBranch }}"
                                                                        class="form-control"></div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>A/C Holder Name</p>
                                                                </div>
                                                                <div class="col-sm-3"><input type="text"
                                                                        name="bankAcHolder"
                                                                        value="{{ $merchantInfo->bankAcHolder }}"
                                                                        class="form-control"></div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Beneficiary Bank Code</p>
                                                                </div>
                                                                <div class="col-sm-3"><input type="text"
                                                                        name="beneficiary_bank_code"
                                                                        value="{{ $merchantInfo->beneficiary_bank_code }}"
                                                                        class="form-control"></div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p>Bank A/C No</p>
                                                                </div>
                                                                <div class="col-sm-3"><input type="text"
                                                                        name="bankAcNo"
                                                                        value="{{ $merchantInfo->bankAcNo }}"
                                                                        class="form-control"></div>
                                                            </div>
                                                            <!-- form-group end -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <p></p>
                                                                </div>
                                                                <div class="col-sm-3"><input type="submit"
                                                                        value="Update"class="common-btn"></div>
                                                            </div>
                                                            <!-- form-group end -->
                                                        </div>


                                                </form>
                                                <div class="tab-pane fade text-center" id="subscriptions"
                                                    role="tabpanel">
                                                    {{-- <h3 class="title mb-5">Subscription Plan</h3> --}}
                                                    <form
                                                        action="{{ url('author/merchant/subscription/activation', $merchantInfo->id) }}"
                                                        id="shipping-form" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="subs_plan_id" id="subs_plan_id">
                                                        <div class="pricing-section innerSubs">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="pricing-card">
                                                                        <h3 class="card-title text-left">Business Starter
                                                                        </h3>
                                                                        <div class="price-container text-left">
                                                                            <div class="mb-3">
                                                                            </div>
                                                                            <div class="price-block text-left">
                                                                                <span class="currency">₦</span>
                                                                                <span class="amount">20,000</span>
                                                                                <span class="duration">/mo</span>
                                                                                <span class="pk_crd_sh">Save 10%</span>

                                                                            </div>
                                                                        </div>
                                                                        @if (@$activeSubPlan->subs_pkg_id == 1)
                                                                            <a href="{{ url('author/merchant/subs/disable/' . $activeSubPlan->subs_pkg_id . '/' . $merchantInfo->id) }}"
                                                                                class="choose-plan-button btn_emp show_confirm2"
                                                                                id="MerchantSubscriptionDisable2"
                                                                                data-plan="1" data-amount="20000">Disable
                                                                                Plan</a>
                                                                        @else
                                                                            <button type="submit"
                                                                                class="choose-plan-button btn_emp show_confirm"
                                                                                id="MerchantSubscriptionSubmit"
                                                                                data-plan="1" data-amount="20000">Choose
                                                                                plan</button>
                                                                        @endif

                                                                        <p class="renewal-info">Renews at NGN 20,000/mo.
                                                                            Cancel anytime.</p>
                                                                        <hr class="hr_line">
                                                                        <ul class="feature-list">
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text "><b>10%</b>
                                                                                    discount on shipping charges</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">Priority
                                                                                    Shipping</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">No
                                                                                    Charges on COD (Cash on
                                                                                    Delivery)</span>
                                                                            </li>
                                                                            <li class="feature-item ">
                                                                                <span
                                                                                    class="feature-icon watermark">&#10006;</span>
                                                                                <span class="feature-text watermark">Free
                                                                                    Insurance cover</span>
                                                                            </li>
                                                                            <li class="feature-item ">
                                                                                <span
                                                                                    class="feature-icon watermark">&#10006;</span>
                                                                                <span class="feature-text watermark">A
                                                                                    dedicated account
                                                                                    officer</span>
                                                                            </li>
                                                                            <li class="feature-item ">
                                                                                <span
                                                                                    class="feature-icon watermark">&#10006;</span>
                                                                                <span class="feature-text watermark">Free
                                                                                    Bulk Pick Up for
                                                                                    Interstate delivery</span>
                                                                            </li>
                                                                            <li class="feature-item ">
                                                                                <span
                                                                                    class="feature-icon watermark">&#10006;</span>
                                                                                <span class="feature-text watermark">
                                                                                    E-commerce business growth kit</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span
                                                                                    class="feature-icon watermark">&#10006;</span>
                                                                                <span class="feature-text watermark">
                                                                                    Facebook
                                                                                    Ad Troubleshooting Help</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span
                                                                                    class="feature-icon watermark">&#10006;</span>
                                                                                <span class="feature-text watermark"> Media
                                                                                    Visibility and business growth</span>
                                                                            </li>
                                                                            <li class="feature-item text-left">
                                                                                <span
                                                                                    class="feature-icon watermark">&#10006;</span>
                                                                                <span class="feature-text watermark"> Free
                                                                                    Reverse logistics (handling returns and
                                                                                    exchanges)</span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="popular-badge">MOST POPULAR</div>
                                                                    <div class="pricing-card most-popular">
                                                                        <h3 class="card-title text-left pt-1">Business
                                                                            Premium</h3>
                                                                        <div class="price-container text-left">
                                                                            <div class="mb-3">
                                                                            </div>
                                                                            <div class="price-block text-left">
                                                                                <span class="currency">₦</span>
                                                                                <span class="amount">199,000</span>
                                                                                <span class="duration">/mo</span>
                                                                                <span class="pk_crd_sh">Save 20%</span>
                                                                            </div>
                                                                        </div>
                                                                        @if (@$activeSubPlan->subs_pkg_id == 2)
                                                                            <a href="{{ url('author/merchant/subs/disable/' . $activeSubPlan->subs_pkg_id . '/' . $merchantInfo->id) }}"
                                                                                class="choose-plan-button primary show_confirm2"
                                                                                id="MerchantSubscriptionDisable2"
                                                                                data-plan="2"
                                                                                data-amount="199000">Disable Plan</a>
                                                                        @else
                                                                            <button
                                                                                class="choose-plan-button primary show_confirm"
                                                                                id="MerchantSubscriptionSubmit2"
                                                                                data-plan="2" data-amount="199000">Choose
                                                                                plan</button>
                                                                        @endif


                                                                        <p class="renewal-info">Renews at NGN 199,000/mo.
                                                                            Cancel anytime.</p>
                                                                        <hr class="hr_line">
                                                                        <ul class="feature-list text-left">

                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text "><b>20%</b>
                                                                                    discount on shipping charges</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">Priority
                                                                                    Shipping</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">No
                                                                                    Charges on COD (Cash on
                                                                                    Delivery)</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">Free
                                                                                    Insurance cover</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">A
                                                                                    dedicated account
                                                                                    officer</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">Free
                                                                                    Bulk Pick Up for
                                                                                    Interstate delivery</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">
                                                                                    E-commerce business growth
                                                                                    kit</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">
                                                                                    Facebook Ad Troubleshooting
                                                                                    Help</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">
                                                                                    Media Visibility and
                                                                                    business growth</span>
                                                                            </li>
                                                                            <li class="feature-item">
                                                                                <span class="feature-icon">&#10004;</span>
                                                                                <span class="feature-text">
                                                                                    Free Reverse logistics
                                                                                    (handling returns and exchanges)</span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                      <div class="row">
                        <h3 class="title mb-2 mt-3">Subscription Plan Activation History</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" width="100">
                                <thead>
                                    <tr>
                                        <th scope="col">S/N</th>
                                        <th scope="col">Plan</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Subscription Date</th>
                                        <th scope="col">Active Time</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($SubsHistos as $key => $value)
                                        <tr>
                                            <th>{{ $key + 1 }}</th>
                                            <td>{{ $value->plan->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($value->plan->price ?? 0, 2) }}</td>
                                            <td>{{ $value->formatted_date }}</td>
                                            <td>{{ $value->formatted_time }}</td>
                                            <td>

                                                 @if ($value->is_active == 1)
                                                        <span class="subscription-badge active">
                                                            ACTIVE
                                                        </span>
                                                @else
                                                        <span class="subscription-badge disable">
                                                            DISABLED/Expired
                                                        </span>
                                                @endif



                                                {{-- @if ($value->auto_expired == 1)
                                                    <span class="subscription-badge expired">
                                                        EXPIRED
                                                    </span>
                                                @else --}}
                                                   
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- laravel links  --}}
                        {{-- {{ $SubsHistos->links('pagination::bootstrap-5') }} --}}
                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- row end -->
                                </div>
                            </div>
                        </div>
                        <!-- col end -->
                    </div>
                    {{-- <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Merchant charge manage</h3>
                                    </div>
                                    <div class="profile-edit mrt-30">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                @foreach ($allpackage as $package)
                                                    <h6>{{ $package->title }}</h6>
                                                    <form action="{{ url('author/merchant/charge-setup') }}"
                                                        method="POST" class="form-row py-2">
                                                        @csrf
                                                        <input type="hidden" value="{{ $package->id }}"
                                                            name="packageId">
                                                        <input type="hidden" value="{{ $merchantInfo->id }}"
                                                            name="merchantId">
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Delivery Charge" name="delivery"
                                                                    value="@if (!empty($package->merchantcharge)) {{ $package->merchantcharge->delivery }} @endif">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Extra Delivery Charge"
                                                                    name="extradelivery"
                                                                    value="@if ($package->merchantcharge) {{ $package->merchantcharge->extradelivery }} @endif">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Cod Charge" name="cod"
                                                                    value="@if ($package->merchantcharge) {{ $package->merchantcharge->cod }} @endif">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1 text-center">
                                                            <label for="codp{{ $package->id }}">cod %</label>
                                                            <div class="form-group">
                                                                <input type="checkbox" id="codp{{ $package->id }}"
                                                                    class="form-control" style="height: 15px;"
                                                                    placeholder="Cod Percente ?" name="codpercent"
                                                                    value="1"
                                                                    @if ($package->merchantcharge) {{ $package->merchantcharge->codpercent == 1 ? 'checked' : '' }} @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <button class="btn btn-success">Update</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!-- row end -->
                                    </div>
                                </div>
                            </div>
                            <!-- col end -->
                        </div> --}}
                </div>
            </div>
        </div>
        </div>
    </section>

    <script type="text/javascript">
        document.forms['editForm'].elements['paymentMethod'].value = "{{ $merchantInfo->paymentMethod }}"
        document.forms['editForm'].elements['withdrawal'].value = "{{ $merchantInfo->withdrawal }}"
        document.forms['editForm'].elements['nearestZone'].value = "{{ $merchantInfo->nearestZone }}"
        document.forms['editForm'].elements['pickupPreference'].value = "{{ $merchantInfo->pickupPreference }}"
    </script>
@endsection
@section('custom_js_scripts')
    <script>
        $(document).ready(function() {
            $('#MerchantSubscriptionSubmit2, #MerchantSubscriptionSubmit').on('click', function() {
                var plantID = $(this).data('plan');
                $('#subs_plan_id').val(plantID);
            });
        });
        $(document.body).ready(function() {
            $('#ins_cal_permission').on('change', function() {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                } else {
                    $(this).val(0);
                }
            });
            $('#cod_cal_permission').on('change', function() {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                } else {
                    $(this).val(0);
                }
            });
        });
    </script>


    {{-- Sweet Alert --}}
    <!-- SweetAlert Script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.show_confirm').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();

                    const form = this.closest('form');

                    swal({
                        title: "Are you sure?",
                        text: "You are about to activate this plan. This action cannot be undone.",
                        icon: "warning",
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                visible: true,
                                className: "btn-secondary"
                            },
                            confirm: {
                                text: "Yes, Activate it!",
                                visible: true,
                                className: "btn-danger"
                            }
                        },
                        dangerMode: true
                    }).then((willSubmit) => {
                        if (willSubmit) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <script type="text/javascript">
       document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.show_confirm2').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault(); // prevent direct link click

            const link = this.getAttribute('href');

            swal({
                title: "Are you sure?",
                text: "This will disable the subscription plan.",
                icon: "warning",
                buttons: ["Cancel", "Yes, disable it!"],
                dangerMode: true,
            }).then((willDisable) => {
                if (willDisable) {
                    window.location.href = link; // manually go to the URL
                }
            });
        });
    });
});
    </script>

@endsection
