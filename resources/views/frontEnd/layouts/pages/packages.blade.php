@extends('frontEnd.layouts.master')
@section('title', 'Packages')


@section('styles')
<link rel="stylesheet" href="{{ asset('frontEnd/') }}/css/packages.css">

@endsection
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumbs" style="background:#db0022;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <!-- Bread Menu -->
                        <div class="bread-menu">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="">Subscription</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / End Breadcrumb -->

    <!-- quickTech-price -->
    <section class="quickTech-price pt-5 pb-5 text-center">
        <div class="container text-center my-5">
            <div class="row text-center">
                <div class="col-md-12">
                    <h1 class="pk_heding">Get online fast – at an unbeatable price</h1>
                    <p class="pk_text">Choose from a wide variety of services and
                        plans to grow your idea online.</p>
                </div>
            </div>
        </div>

        <div class="pricing-section">
            <div class="pricing-card">
                <h3 class="card-title text-left">Business Starter Features</h3>
                <div class="price-container text-left">
                    <div class="mb-3">
                        <span class="pk_crd_uss">₦ 12,000 </span> &nbsp; &nbsp;
                        <span class="pk_crd_sh"> SAVE 10%</span>
                    </div>
                    <div class="price-block text-left">
                        <span class="currency">₦</span>
                        <span class="amount">10,000</span>
                        <span class="duration">/mo</span>
                    </div>
                </div>
                <div class="mb-4 text-left">
                    <p class="card-text">Price per mailbox</p>
                    <p class="card-text">For 48-month term</p>
                </div>
                <button class="choose-plan-button btn_emp">Choose plan</button>
                <p class="renewal-info">Renews at NGN 10,000/mo. Cancel anytime.</p>
                <hr class="hr_line">
                <ul class="feature-list">
                    <li class="feature-item">
                        <span class="feature-icon">&#10004;</span> <span class="feature-text dashed"><b>10%</b>
                             discount on shipping charges</span>
                        <div class="tooltip">Email aliases let you create 50 additional email addresses that
                            link to your main email account. This means you can have different emails for
                            your projects, subscribing to services that may send you spam, all without
                            showing your primary email. Plus, you can send emails from these aliases too.
                            Benefits include less spam, organised inboxes, and improved privacy. Use aliases
                            to keep your main email safe and your communications sorted.</div>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Priority Shipping</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">&#10004;</span> <span class="feature-text">No Charges on COD (Cash on Delivery)</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Free Insurance cover</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">&#10004;</span> <span class="feature-text">A dedicated account officer</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Free Bulk Pick Up for Interstate delivery</span>
                    </li>
                    <li class="feature-item ">
                        <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark"> E-commerce business growth kit</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark"> Facebook Ad Troubleshooting Help</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark"> Media Visibility and business growth</span>
                    </li>
                    <li class="feature-item text-left">
                        <span class="feature-icon watermark">&#10006;</span> <span class="feature-text watermark"> Free Reverse logistics (handling returns and exchanges)</span>
                    </li>
                </ul>
            </div>

            <div class="pricing-card most-popular">
                <div class="popular-badge">MOST POPULAR</div>
                <h3 class="card-title text-left">Business Premium Features</h3>
                <div class="price-container text-left">
                    <div class="mb-3">
                        <span class="pk_crd_uss">₦ 25,000 </span> &nbsp; &nbsp;
                        <span class="pk_crd_sh"> SAVE 20%</span>
                    </div>
                    <div class="price-block text-left">
                        <span class="currency">₦</span>
                        <span class="amount">20,000</span>
                        <span class="duration">/mo</span>
                    </div>
                </div>
                <div class="mb-4 text-left">
                    <p class="card-text">Price per mailbox</p>
                    <p class="card-text">For 48-month term</p>
                </div>
                <button class="choose-plan-button primary">Choose plan</button>
                <p class="renewal-info">Renews at NGN 20,000/mo. Cancel anytime.</p>
                <hr class="hr_line">
                <ul class="feature-list text-left">
                
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text dashed"><b>20%</b>
                                 discount on shipping charges</span>
                            <div class="tooltip">Email aliases let you create 50 additional email addresses that
                                link to your main email account. This means you can have different emails for
                                your projects, subscribing to services that may send you spam, all without
                                showing your primary email. Plus, you can send emails from these aliases too.
                                Benefits include less spam, organised inboxes, and improved privacy. Use aliases
                                to keep your main email safe and your communications sorted.</div>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text">Priority Shipping</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text">No Charges on COD (Cash on Delivery)</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text">Free Insurance cover</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text">A dedicated account officer</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text">Free Bulk Pick Up for Interstate delivery</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text"> E-commerce business growth kit</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text"> Facebook Ad Troubleshooting Help</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text"> Media Visibility and business growth</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">&#10004;</span> <span class="feature-text"> Free Reverse logistics (handling returns and exchanges)</span>
                        </li>
                    </ul>
            </div>
        </div>
        <div>
            <p class="simple_pera">
                All plans are paid upfront. The monthly rate reflects the total plan price divided by the number of months
                in your plan.
            </p>
        </div>
        <div class="extra-benefits-section">
            <div class="benefits-text">
                Enjoy all this. At no extra cost.
            </div>
            <ul class="benefits-list">
                <li class="benefit-item">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-svgo-icon"
                            style="" data-v-fe552693="" data-v-03dae1ca="">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M6.75 5.8a5.25 5.25 0 0 1 10.501 0v.7h.375a3.375 3.375 0 0 1 3.375 3.376v9.75a3.375 3.375 0 0 1-3.375 3.376H6.376A3.375 3.375 0 0 1 3 19.627V9.876A3.375 3.375 0 0 1 6.375 6.5h.375v-.7Zm8.501 0v.694h-6.5V5.8a3.25 3.25 0 1 1 6.5 0ZM6.375 8.5h11.251c.76 0 1.375.616 1.375 1.376v9.75c0 .76-.615 1.376-1.375 1.376H6.376c-.76 0-1.376-.616-1.376-1.375V9.876c0-.76.616-1.375 1.375-1.375Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>

                    <span class="benefit-label">Industry-leading security</span>
                </li>
                <li class="benefit-item">
                    <div>
                        <svg data-v-03dae1ca="" data-v-fe552693="" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" class="h-svgo-icon" style="">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M4.25 5.25c0-.855.398-1.549.944-2.068.532-.504 1.23-.874 1.956-1.145 1.455-.545 3.268-.787 4.85-.787 1.582 0 3.396.242 4.85.787.726.271 1.424.64 1.956 1.145.546.52.944 1.213.944 2.068V18c0 1.56-1.12 2.767-2.482 3.525-1.395.777-3.257 1.225-5.268 1.225-2.011 0-3.873-.448-5.268-1.225C5.37 20.767 4.25 19.561 4.25 18V5.25Zm2 0c0-.18.071-.38.321-.617.266-.252.693-.504 1.28-.723 1.17-.438 2.731-.66 4.149-.66 1.418 0 2.98.222 4.15.66.586.22 1.013.47 1.279.723.25.237.321.436.321.617 0 .18-.071.38-.321.617-.266.252-.693.504-1.28.723-1.17.438-2.731.66-4.149.66-1.418 0-2.98-.222-4.15-.66-.586-.22-1.013-.47-1.279-.723-.25-.237-.321-.436-.321-.617Zm0 7.311v1.689c0 .18.071.38.321.617.266.252.693.504 1.28.723 1.17.438 2.731.66 4.149.66 1.418 0 2.98-.222 4.15-.66.586-.22 1.013-.47 1.279-.723.25-.237.321-.436.321-.617v-1.689a7.477 7.477 0 0 1-.9.402c-1.455.545-3.268.787-4.85.787-1.582 0-3.395-.242-4.85-.787a7.474 7.474 0 0 1-.9-.402Zm11.5-2.811c0 .18-.071.38-.321.617-.266.252-.693.504-1.28.723-1.17.438-2.731.66-4.149.66-1.418 0-2.98-.222-4.15-.66-.586-.22-1.013-.47-1.279-.723-.25-.237-.321-.436-.321-.617V8.061c.288.154.591.287.9.402 1.455.545 3.268.787 4.85.787 1.582 0 3.396-.242 4.85-.787.309-.115.612-.248.9-.402V9.75Zm0 7.311a7.477 7.477 0 0 1-.9.402c-1.455.545-3.268.787-4.85.787-1.582 0-3.395-.242-4.85-.787a7.474 7.474 0 0 1-.9-.402V18c0 .51.38 1.18 1.455 1.778 1.043.58 2.556.972 4.295.972 1.739 0 3.252-.391 4.295-.972C17.37 19.179 17.75 18.51 17.75 18v-.939Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="benefit-label">Anti-spam and phishing tools</span>
                </li>

                <li class="benefit-item">
                    <div>
                        <svg data-v-03dae1ca="" data-v-fe552693="" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" class="h-svgo-icon" style="">
                            <path fill="currentColor"
                                d="M21.746 4.781a.6.6 0 0 1 .107.701l-.96 1.846a1.206 1.206 0 0 0-.028 1.022c.137.328.402.59.74.697l1.976.627a.6.6 0 0 1 .419.572v3.54a.6.6 0 0 1-.422.573l-1.988.616c-.339.105-.605.366-.741.694l-1.846-.772a3.2 3.2 0 0 1 1.995-1.833l1.002-.31V11.27l-1-.318a3.21 3.21 0 0 1-1.98-1.832 3.206 3.206 0 0 1 .1-2.716l.486-.935-1.058-1.059-.933.487a3.202 3.202 0 0 1-2.716.1 3.205 3.205 0 0 1-1.83-1.984L12.749 2h-1.485l-.317 1.002-1.907-.604.627-1.98A.6.6 0 0 1 10.24 0h3.537a.6.6 0 0 1 .572.42l.628 1.993c.107.338.368.603.695.74.33.137.703.136 1.02-.03l1.844-.96a.6.6 0 0 1 .702.108l2.509 2.51Z">
                            </path>
                            <path fill="currentColor"
                                d="M8.345 3.137c.327-.137.588-.401.695-.74l1.907.605a3.207 3.207 0 0 1-1.83 1.98c-.821.343-1.821.367-2.717-.1l-.936-.487-1.068 1.062.485.933c.466.896.442 1.895.1 2.716a3.21 3.21 0 0 1-1.982 1.832L2 11.255v1.49l1 .317a3.21 3.21 0 0 1 1.98 1.832c.343.82.367 1.82-.1 2.716l-.486.936 1.058 1.058.933-.486a3.204 3.204 0 0 1 2.716-.1 3.207 3.207 0 0 1 1.831 1.98L11.249 22h1.487l.317-1.002a3.207 3.207 0 0 1 1.83-1.98 3.204 3.204 0 0 1 2.717.1l.933.486 1.058-1.058-.489-.94a3.197 3.197 0 0 1-.099-2.709l1.846.772c-.137.327-.136.7.028 1.015l.961 1.849a.6.6 0 0 1-.108.7l-2.508 2.511a.6.6 0 0 1-.702.108l-1.843-.96a1.205 1.205 0 0 0-1.022-.029 1.207 1.207 0 0 0-.695.74l-.627 1.978a.6.6 0 0 1-.572.419h-3.537a.6.6 0 0 1-.572-.419l-.627-1.979a1.208 1.208 0 0 0-.695-.739 1.204 1.204 0 0 0-1.022.029l-1.843.96a.6.6 0 0 1-.702-.108l-2.509-2.51a.6.6 0 0 1-.107-.701l.96-1.846c.164-.317.166-.692.028-1.022a1.21 1.21 0 0 0-.74-.697L.419 14.34A.6.6 0 0 1 0 13.77v-3.538a.6.6 0 0 1 .419-.572l1.976-.627a1.21 1.21 0 0 0 .74-.697c.138-.33.136-.705-.029-1.022l-.96-1.846a.6.6 0 0 1 .11-.703l2.524-2.51a.6.6 0 0 1 .7-.106l1.843.96c.317.165.692.167 1.022.029Z">
                            </path>
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M12 7.25a4.75 4.75 0 1 0 0 9.5 4.75 4.75 0 0 0 0-9.5ZM14.75 12a2.75 2.75 0 1 1-5.5 0 2.75 2.75 0 0 1 5.5 0Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="benefit-label">10GB+ of storage</span>
                </li>
                <li class="benefit-item">
                    <div>
                        <svg data-v-03dae1ca="" data-v-fe552693="" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" class="h-svgo-icon" style="">
                            <path fill="currentColor"
                                d="M2.625 3.875a1 1 0 0 1 1 1v13.5a2 2 0 0 0 2 2h15.75a1 1 0 1 1 0 2H5.625a4 4 0 0 1-4-4v-13.5a1 1 0 0 1 1-1Z">
                            </path>
                            <path fill="currentColor"
                                d="M20.75 9.164v2.086a1 1 0 1 0 2 0v-4.5a1 1 0 0 0-1-1h-4.5a1 1 0 1 0 0 2h2.086L15 12.086l-2.513-2.513a1.75 1.75 0 0 0-2.474 0l-3.97 3.97a1 1 0 1 0 1.414 1.414l3.793-3.793 2.513 2.513a1.75 1.75 0 0 0 2.474 0l4.513-4.513Z">
                            </path>
                        </svg>
                    </div>
                    <span class="benefit-label">Multiple bespoke addresses</span>
                </li>
                <li class="benefit-item">
                    <div>
                        <svg data-v-03dae1ca="" data-v-fe552693="" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" class="h-svgo-icon" style="">
                            <path fill="currentColor"
                                d="M16.124 9.855a1 1 0 0 0-1.414-1.414l-3.497 3.496-1.594-1.594a1 1 0 0 0-1.414 1.414l2.297 2.298.004.004a1 1 0 0 0 1.414 0l4.204-4.204Z">
                            </path>
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M1.611 6.81a3 3 0 0 1 1.73-2.887l7.2-3.348a3 3 0 0 1 2.488-.02l7.584 3.383a3 3 0 0 1 1.774 2.906L22.378 7c-.338 6.093-3.06 12.216-8.56 14.856-.827.396-1.543.645-2.037.645-.502 0-1.213-.256-2.024-.663-5.267-2.64-7.788-8.594-8.115-14.476l-.03-.552Zm18.779-.077-.009.157c-.316 5.684-2.832 10.957-7.429 13.163a7.554 7.554 0 0 1-.896.372 2.09 2.09 0 0 1-.274.073 1.948 1.948 0 0 1-.26-.071 7.022 7.022 0 0 1-.869-.377C6.28 17.86 3.945 12.754 3.639 7.25l-.03-.551a1 1 0 0 1 .576-.962l7.2-3.349a1 1 0 0 1 .83-.006l7.584 3.383a1 1 0 0 1 .59.968Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="benefit-label">30-day money-back guarantee</span>
                </li>

                <li class="benefit-item">
                    <div>
                        <svg data-v-03dae1ca="" data-v-fe552693="" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" class="h-svgo-icon" style="">
                            <path fill="currentColor"
                                d="M15.877 13.385a1 1 0 0 1-1.004 1.73l-3.48-2.02a1 1 0 0 1-.497-.865v-4.5a1 1 0 0 1 2 0v3.924l2.981 1.731Z">
                            </path>
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M22.5 12c0 5.799-4.701 10.5-10.5 10.5S1.5 17.799 1.5 12 6.201 1.5 12 1.5 22.5 6.201 22.5 12Zm-2 0a8.5 8.5 0 1 1-17 0 8.5 8.5 0 0 1 17 0Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="benefit-label">24/7 customer support</span>
                </li>
            </ul>
        </div>
        <div class="included-section">
            <h2 class="included-title">Included with every plan</h2>
            <div class="included-grid ">
                <div class="included-item">
                    <div class="item-icon">
                        <img src="{{ asset('/frontEnd/img/pk_mail.png') }}" alt="">
                    </div>
                    <h3 class="item-title">User-friendly management</h3>
                    <p class="item-description">A user-friendly panel for managing accounts, devices, and DNS with webmail
                        for easy email and contact organization.</p>
                </div>
                <div class="included-item">
                    <div class="item-icon">
                        <img src="{{ asset('/frontEnd/img/pk_env.png') }}" alt="">
                    </div>
                    <h3 class="item-title">Total security</h3>
                    <p class="item-description">Relax, your websites and visitors are protected by the latest security
                        software.</p>
                </div>
                <div class="included-item">
                    <div class="item-icon">
                        <img src="{{ asset('/frontEnd/img/pk_meter.png') }}" alt="">
                    </div>
                    <h3 class="item-title">99.9% uptime. Guaranteed</h3>
                    <p class="item-description">Our 99.9% uptime guarantee means your website is always available.</p>
                </div>
               
                <div class="included-item included-item2">
                    <div class="item-icon">
                        <img src="{{ asset('/frontEnd/img/pk_cal.png') }}" alt="">
                    </div>
                    <h3 class="item-title">A single, simple dashboard</h3>
                    <p class="item-description">Designed to be easy-to-use for beginners and professionals alike, you can
                        see at a glance how your site is performing.</p>
                </div>
                <div class="included-item included-item2">
                    <div class="item-icon">
                        <img src="{{ asset('/frontEnd/img/pk_sms.png') }}" alt="">
                    </div>
                    <h3 class="item-title">24/7 customer support</h3>
                    <p class="item-description">Access expert support whenever you need it. We typically respond in under 2
                        minutes and our team speak 10+ languages.</p>
                </div>
            </div>
            <div class="button-container">
                <button class="get-started-button">Get started</button>
            </div>
        </div>

    </section>

@endsection
@section('custom_js_script')
    <script>
        $(document).ready(function() {
            // Event listener for BankPosterBTN click
            $(document.body).on('click', '.BankPosterBTN', function(e) {
                e.preventDefault();

                // Get the poster image path from data attribute
                var posterPath = $(this).data('poster');

                if (posterPath) {
                    // Set the href and src attributes for the anchor and image
                    $('#PosterImageA').attr('href', posterPath);
                    $('#PosterImage').attr('src', posterPath);

                    // Show the Bootstrap modal
                    $('#PaymentPosterModal').modal('show');
                } else {
                    alert('No poster available');
                }
            });
        });
    </script>
@endsection
