@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title', 'Dashboard')
@section('extracss')

    <style>
        .custom-switch .custom-control-input:checked~.custom-control-label::after {
            background-color: #dee2e6;
            -webkit-transform: translateX(.75rem);
            transform: translateX(.75rem);
        }

        .custom-switch .custom-control-label::after {
            top: calc(.25rem + 2px);
            /* left: calc(-3.25rem + 2px); */
            width: calc(1rem - 4px);
            height: calc(1rem - 4px);
            background-color: #adb5bd;
            border-radius: .5rem;
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-transform .15s ease-in-out;
            transition: transform .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            transition: transform .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-transform .15s ease-in-out;
        }

        .custom-control-input {
            position: absolute;
            z-index: -1;
            opacity: 0;
        }

        .custom-control-input:checked~.custom-control-label::before {
            color: #fff;
            border-color: #007bff;
            background-color: #007bff;
            box-shadow: none;
        }

        .custom-switch .custom-control-label::before {
            /* left: -3.25rem; */
            width: 1.75rem;
            pointer-events: all;
            border-radius: .5rem;
        }

        .custom-control-label::before {
            position: absolute;
            top: .25rem;
            /* left: -2.5rem; */
            display: block;
            width: 1rem;
            height: 1rem;
            pointer-events: none;
            content: "";
            background-color: #dee2e6;
            border: #adb5bd solid 1px;
            box-shadow: inset 0 .25rem .25rem rgba(0, 0, 0, .1);
        }

        .custom-switch .custom-control-input:checked~.custom-control-label::after {
            background-color: #dee2e6;
            -webkit-transform: translateX(.75rem);
            transform: translateX(.75rem);
        }

        .custom-switch .custom-control-label::after {
            top: calc(.25rem + 2px);
            /* left: calc(-3.25rem + 2px); */
            width: calc(1rem - 4px);
            height: calc(1rem - 4px);
            background-color: #adb5bd;
            border-radius: .5rem;
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-transform .15s ease-in-out;
            transition: transform .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            transition: transform .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-transform .15s ease-in-out;
        }

        .custom-control-label::after {
            position: absolute;
            top: .25rem;
            /* left: -2.5rem; */
            display: block;
            width: 1rem;
            height: 1rem;
            content: "";
            background: no-repeat 50% / 50% 50%;
        }

        .custom-control-label {

            margin-left: 15px !important;
        }

        .custom-control {

            margin-left: 10px !important;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('frontEnd/css/packages.css') }}">
@endsection
@section('content')

    <div class="profile-edit mrt-30">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <nav class="custom-tab-menu">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" data-toggle="tab" href="#companyinformation">Company
                            Information</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#ownerinformation">Owner Information</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#pickupmethod">Pickup Method</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#paymentmethod">Payment Method</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#bankaccount">Bank Account</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#subscriptions">Subscriptions</a>
                        {{-- <a class="nav-item nav-link" data-toggle="tab" href="#otheraccount">Other Account</a> --}}
                    </div>
                </nav>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <form action="{{ url('merchant/profile/edit') }}" method="POST" name="editForm"
                    enctype="multipart/form-data" id="form1">
                    @csrf
                    <div class="tab-content customt-tab-content" id="nav-tabContent">

                        <div class="tab-pane fade" id="companyinformation" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="title">Business Information</p>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 col-sm-4">
                                            <p>Company Name</p>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-8">
                                            <p><strong>{{ $merchantInfo->companyName }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ownerinformation" role="tabpanel">
                            <div class="row">

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <p class="title">Owner Information</p>
                                    <div class="form-group row">
                                        <div class="col-lg-3 col-md-4 col-sm-4">
                                            <p>Name</p>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-8">
                                            <p><strong>{{ $merchantInfo->firstName }} {{ $merchantInfo->lastName }}</strong>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-3 col-md-4 col-sm-4">
                                            <p>Mobile Number</p>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-8"><input type="text" name="phoneNumber"
                                                id="phoneNumber" value="{{ $merchantInfo->phoneNumber }}"
                                                class="form-control">
                                            <div class="nigeria-flag"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3 col-md-4 col-sm-4">
                                            <p>Email</p>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-8">{{ $merchantInfo->emailAddress }}</div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-3 col-md-4 col-sm-4">
                                            <p>Image</p>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-8">
                                            <input type="file" name="logo" class="form-control">
                                            <img src="{{ asset($merchantInfo->logo) }}" width="60 " alt="">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="ins_cal_permission"
                                                name="ins_cal_permission" value="{{ $merchantInfo->ins_cal_permission }}"
                                                {{ $merchantInfo->ins_cal_permission == 1 ? 'checked' : '' }}
                                                @disabled(true)>
                                            <label class="custom-control-label" for="ins_cal_permission">Insurance
                                                Calculation Permission</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="cod_cal_permission"
                                                name="cod_cal_permission" value="{{ $merchantInfo->cod_cal_permission }}"
                                                {{ $merchantInfo->cod_cal_permission == 1 ? 'checked' : '' }}
                                                @disabled(true)>
                                            <label class="custom-control-label" for="cod_cal_permission">COD Calculation
                                                Permission</label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-3 col-md-4 col-sm-4">
                                            <p></p>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-8"><input type="submit"
                                                value="Update"class="common-btn"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="pickupmethod" role="tabpanel">
                            <p class="title">Pickup Method</p>
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Pickup Address</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <textarea name="pickLocation" class="form-control">{{ $merchantInfo->pickLocation }}</textarea>
                                </div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Nearest Zone</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <select type="text" name="nearestZone" class="form-control">
                                        <option value=""></option>
                                        @foreach ($nearestzones as $key => $value)
                                            <option value="{{ $value->id }}">{{ $value->zonename }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Pickup Preference</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <select type="text" name="pickupPreference" class="form-control">
                                        <option value="1">As Per Request</option>
                                        <option value="2">Daily</option>
                                    </select>
                                </div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p></p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8"><input type="submit"
                                        value="Update"class="common-btn"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paymentmethod" role="tabpanel">
                            <p class="title">Payment Method</p>
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Default Payment</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <select type="text" name="paymentMethod" class="form-control">
                                        <option value="1">Bank</option>
                                        <option value="2">Bkash</option>
                                        <!--<option value="3">Roket</option>-->
                                        <!--<option value="4">Nogod</option>-->
                                    </select>
                                </div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Withdrawal</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <select type="text" name="withdrawal" class="form-control">
                                        <option value="1">As Per Request</option>
                                        <option value="2">Daily</option>
                                        <option value="3">Weekly</option>
                                    </select>
                                </div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p></p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8"><input type="submit"
                                        value="Update"class="common-btn"></div>
                            </div>
                            <!-- form group end -->
                        </div>
                        <div class="tab-pane fade " id="bankaccount" role="tabpanel">
                            <p class="title">Bank Account</p>
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Name Of Bank</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8"><input type="text" name="nameOfBank"
                                        value="{{ $merchantInfo->nameOfBank }}" class="form-control" readonly></div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Branch</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8"><input type="text" name="bankBranch"
                                        value="{{ $merchantInfo->bankBranch }}" class="form-control" readonly></div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>A/C Holder Name</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8"><input type="text" name="bankAcHolder"
                                        value="{{ $merchantInfo->bankAcHolder }}" class="form-control" readonly></div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Beneficiary Bank Code</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8"><input type="text"
                                        name="beneficiary_bank_code" value="{{ $merchantInfo->beneficiary_bank_code }}"
                                        class="form-control" readonly></div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p>Bank A/C No</p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8"><input type="text" name="bankAcNo"
                                        value="{{ $merchantInfo->bankAcNo }}" class="form-control" readonly></div>
                            </div>
                            <!-- form-group end -->
                            <div class="form-group row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <p></p>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8"><input type="submit"
                                        value="Update"class="common-btn" readonly></div>
                            </div>
                            <!-- form-group end -->
                        </div>
                </form>
                <div class="tab-pane fade text-center" id="subscriptions" role="tabpanel">
                    {{-- <h3 class="title mb-5">Subscription Plan</h3> --}}
                    <form action="" id="shipping-form">
                        <input type="hidden" name="subs_plan_id" id="subs_plan_id">
                        <div class="pricing-section innerSubs">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="pricing-card">
                                        <h3 class="card-title text-left">Business Starter</h3>
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
                                        @if (@$merchantSubsPlan->subs_pkg_id == 1)
                                            <a href="{{ url('merchant/subs/disable/' . $merchantSubsPlan->subs_pkg_id . '/' . $merchantSubsPlan->merchant_id) }}"
                                                class="choose-plan-button btn_emp show_confirm"
                                                id="MerchantSubscriptionSubmit" data-plan="1"
                                                data-amount="20000">Disable Plan</a>
                                        @else
                                            <button type="submit" class="choose-plan-button btn_emp"
                                                id="MerchantSubscriptionSubmit" data-plan="1" data-amount="20000"
                                                onclick="payWithPaystack(event, '1', '20000')">Choose plan</button>
                                        @endif

                                        <p class="renewal-info">Renews at NGN 20,000/mo. Cancel anytime.</p>
                                        <hr class="hr_line">
                                        <ul class="feature-list">
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span
                                                    class="feature-text "><b>10%</b>
                                                    discount on shipping charges</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span
                                                    class="feature-text">Priority Shipping</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">No
                                                    Charges on COD (Cash on
                                                    Delivery)</span>
                                            </li>
                                            <li class="feature-item ">
                                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark">Free
                                                    Insurance cover</span>
                                            </li>
                                            <li class="feature-item ">
                                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark">A
                                                    dedicated account
                                                    officer</span>
                                            </li>
                                            <li class="feature-item ">
                                                <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark">Free
                                                    Bulk Pick Up for
                                                    Interstate delivery</span>
                                            </li>
                                            <li class="feature-item ">
                                                <span class="feature-icon watermark">&#10006;</span> <span
                                                    class="feature-text watermark">
                                                    E-commerce business growth kit</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon watermark">&#10006;</span> <span
                                                    class="feature-text watermark"> Facebook
                                                    Ad Troubleshooting Help</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon watermark">&#10006;</span> <span
                                                    class="feature-text watermark"> Media
                                                    Visibility and business growth</span>
                                            </li>
                                            <li class="feature-item text-left">
                                                <span class="feature-icon watermark">&#10006;</span> <span
                                                    class="feature-text watermark"> Free
                                                    Reverse logistics (handling returns and exchanges)</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="popular-badge">MOST POPULAR</div>
                                    <div class="pricing-card most-popular">
                                        <h3 class="card-title text-left pt-1">Business Premium</h3>
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
                                        @if (@$merchantSubsPlan->subs_pkg_id == 2)
                                            <a href="{{ url('merchant/subs/disable/' . $merchantSubsPlan->subs_pkg_id . '/' . $merchantSubsPlan->merchant_id) }}"
                                                class="choose-plan-button primary show_confirm"
                                                id="MerchantSubscriptionSubmit" data-plan="1"
                                                data-amount="199000">Disable Plan</a>
                                        @else
                                            <button class="choose-plan-button primary" id="MerchantSubscriptionSubmit2"
                                                data-plan="2" data-amount="199000"
                                                onclick="payWithPaystack(event, '2', '199000')">Choose plan</button>
                                        @endif


                                        <p class="renewal-info">Renews at NGN 199,000/mo. Cancel anytime.</p>
                                        <hr class="hr_line">
                                        <ul class="feature-list text-left">

                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span
                                                    class="feature-text "><b>20%</b>
                                                    discount on shipping charges</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span
                                                    class="feature-text">Priority Shipping</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">No
                                                    Charges on COD (Cash on
                                                    Delivery)</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">Free
                                                    Insurance cover</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">A
                                                    dedicated account
                                                    officer</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">Free
                                                    Bulk Pick Up for
                                                    Interstate delivery</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">
                                                    E-commerce business growth
                                                    kit</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">
                                                    Facebook Ad Troubleshooting
                                                    Help</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">
                                                    Media Visibility and
                                                    business growth</span>
                                            </li>
                                            <li class="feature-item">
                                                <span class="feature-icon">&#10004;</span> <span class="feature-text">
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
    <script type="text/javascript">
        document.forms['editForm'].elements['paymentMethod'].value = "{{ $merchantInfo->paymentMethod }}"
        document.forms['editForm'].elements['withdrawal'].value = "{{ $merchantInfo->withdrawal }}"
        document.forms['editForm'].elements['nearestZone'].value = "{{ $merchantInfo->nearestZone }}"
        document.forms['editForm'].elements['pickupPreference'].value = "{{ $merchantInfo->pickupPreference }}"
    </script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        const paymentForm = document.getElementById('shipping-form');
        paymentForm.addEventListener("submit", payWithPaystack, false);

        function payWithPaystack(e, subs_plan_id, totalAmount) {
            e.preventDefault();

            let emailAddress = '{{ $merchantInfo->emailAddress }}'
            let phoneNumber = '{{ $merchantInfo->phoneNumber }}'
            let merchant_id = '{{ $merchantInfo->id }}'
            let total = parseFloat(totalAmount);
            // Basic validation for required fields
            if (!subs_plan_id || !totalAmount) {
                alert('Please fill in all required fields.');
                return;
            }

            let handler = PaystackPop.setup({

                key: '<?= $results->public ?>', 
                // key: 'pk_test_9e185aac0936fd9313529f6471cdc37873adc730',
                email: emailAddress,
                phone: phoneNumber,
                amount: total * 100, // Convert amount to kobo
                language: "en", // Optional language property


                onClose: function() {
                    alert('Payment window closed.');
                },

                callback: function(response) {
                    let reference = response.reference;

                    // Verify payment via the backend
                    $.ajax({
                        type: "GET",
                        url: "{{ URL::to('/merchant/get/verify-payment-subs') }}/" + reference,
                        success: function(paymentResponse) {
                            console.log(paymentResponse[0].status);
                            if (paymentResponse[0].status === true) {
                                // Store payment data
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    }
                                });

                                console.log(paymentResponse);

                                $.ajax({
                                    type: "GET",
                                    url: "{{ URL::to('/merchant/subs/get/store-payment') }}",
                                    data: {
                                        reference: reference,
                                        paymentResponse: paymentResponse,
                                        total: total,
                                        merchant_id: merchant_id,
                                        subs_plan_id: subs_plan_id,
                                        total: total,

                                    },
                                    success: function(res) {

                                        toastr.success(
                                            "Your transaction completed successfully!"
                                        );

                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 2000); // 3000 milliseconds = 3 seconds
                                        $('html, body').animate({
                                                scrollTop: 0
                                            },
                                            'fast'
                                        ); // Smooth scroll to top when moving to the next step

                                    },
                                    error: function(err) {
                                        //console.error('Error storing payment data:', err);
                                        alert(
                                            'Failed to store payment data. Please try again.'
                                        );
                                    }
                                });
                            } else {
                                alert('Payment verification failed. Please try again.');
                            }
                        },
                        error: function(err) {
                            //console.error('Payment verification error:', err);
                            toastr.success("Error verifying payment. Please try again.");
                        }
                    });
                }
            });

            handler.openIframe();
        }
    </script>
    {{-- Sweet Alert --}}
    <!-- SweetAlert Script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Attach click event to elements with 'show_confirm' class
            document.querySelectorAll('.show_confirm').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();

                    const link = this.getAttribute('href');

                    swal({
                        title: "Are you sure?",
                        text: "You are about to disable this plan. This action cannot be undone.",
                        icon: "warning",
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                visible: true,
                                className: "btn-secondary"
                            },
                            confirm: {
                                text: "Yes, Disable it!",
                                visible: true,
                                className: "btn-danger"
                            }
                        },
                        dangerMode: true
                    }).then((willDelete) => {
                        if (willDelete) {
                            window.location.href = link;
                        }
                    });
                });
            });
        });
    </script>


@endsection
