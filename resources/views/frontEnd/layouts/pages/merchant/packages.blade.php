@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title', 'Packages')
@section('content')
    <style>
        :root {
            --font-family: "DM Sans", sans-serif;
            --primary-color: #2F1C6A;
            --accent-color: #673DE6;
            --badge-bg: #fafbff;
            --badge-text: #2F1C6A;
            --badge-text2: #2f1c6a;
            --theme-bg-red-dark: #AF251B;
            --theme-bg-red-light: #DB0022;
            --theme-btn-dark: #0E121D;
        }

        .quickTech-price {
            font-family: var(--font-family);
            text-align: center;
        }

        .pk_heding {
            color: var(--theme-btn-dark);
            font-family: var(--font-family);
            font-weight: 700;
        }

        .pk_text {
            font-family: var(--font-family);
            font-weight: 400;
            color: var(--primary-color);
            text-align: center !important;
            font-size: 16px;
        }

        .ml-6 {
            margin-left: 60px !important;
        }

        .mr-6 {
            margin-right: 60px !important;
        }

        .pk_card .card-body {
            color: var(--accent-color) !important;
        }

        .pk_crd_sh {
            display: inline-block;
            background-color: #cf0;
            color: #2f1c6a;
            font-weight: bold;
            font-size: 14px;
            line-height: 24px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;

        }

        .txt {
            color: #2f1c6a;
            font-size: 14px;
            line-height: 24px;
            font-weight: 400;
        }

        .pk_card h4 {
            color: var(--badge-text);
        }

        .pk_crd_uss {
            color: #2F1C6A;
            /* Dark navy text */
            font-weight: 400;
            font-size: 16px;
            text-decoration: line-through;
            font-family: "DM Sans", sans-serif;
        }

        .price-block {
            font-family: "DM Sans", sans-serif;
            color: #2F1C6A;
            /* Dark navy */
            text-align: center;
        }

        .price-block .currency {
            font-size: 20px;
            font-weight: 400;
            /* vertical-align: middle; */
        }

        .price-block .amount {
            font-size: 48px;
            font-weight: 700;
            margin: 0 4px;
            /* vertical-align: bottom; */
        }

        .price-block .duration {
            font-size: 20px;
            font-weight: 400;
            /* vertical-align: bottom; */
        }

        .card-text {
            color: var(--badge-text) !important;
        }

        .choose-plan-button {
            background-color: white;
            /* Or any other desired background color */
            font-weight: 400;
            color: var(--theme-btn-dark);
            /* Example purple color, adjust as needed */
            border: 2px solid var(--theme-btn-dark);
            /* Matching border */
            border-radius: 8px;
            /* Adjust for desired rounded corners */
            padding: 10px 60px;
            /* Adjust vertical and horizontal padding */
            font-size: 16px;
            /* Adjust font size */
            font-weight: bold;
            /* Optional: make the text bold */
            cursor: pointer;
            /* Change cursor to indicate it's clickable */
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Optional: smooth hover effect */
        }

        .choose-plan-button.btn_emp:hover {
            background-color: #e6def5 !important;
            /* Example hover background color */
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-icon.minus {
            color: #6d7081;
            /* Example grey color for the minus */
        }

        /* Combining the selectors as requested - be aware of specificity */
        .feature-icon.minus,
        .feature-icon.minus .feature-text {
            /* This targets .feature-text *inside* .feature-icon.minus, which is unlikely your intent */
            color: #6d7081;
            /* Example grey color for the minus */
        }

        .feature-text {
            /* Add any specific styling for the text if needed */
            font-size: 14px;
            line-height: 24px;
            font-weight: 400;
            color: #2f1c6a !important;
            font-family: "DM Sans", sans-serif;
        }

        .feature-text.dashed {
            text-decoration: underline dashed;
            text-underline-offset: 8px;
            /* Adjust spacing as needed */
            color: #6d7081;
        }

        .hr_line {
            color: #d5dfff;
        }

        /* Tooltip Styles (Bottom-Left Corner with 20px Left Margin) */
        .tooltip {
            visibility: hidden;
            width: 250px;
            /* Adjust width as needed */
            background-color: #5025d1;
            /* Example purple tooltip background */
            color: #fff;
            text-align: left;
            border-radius: 4px;
            padding: 10px;
            position: absolute;
            z-index: 1;
            top: calc(100% + 5px);
            /* Position below the text with a small gap */
            left: 30px;
            /* Align to the left of the feature item with 20px margin */
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 14px;
            line-height: 1.4;
        }

        .tooltip::after {
            content: "";
            position: absolute;
            top: -5px;
            /* Position the arrow at the top of the tooltip */
            left: 30px;
            /* Adjust arrow position to account for the tooltip's left margin */
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent #673AB7 transparent;
            /* Arrow pointing upwards */
        }

        .feature-item:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }


        /* all */
        .pricing-section {
            display: flex;
            gap: 20px;
            /* Space between the cards */
            padding: 20px;
            /* Adjust as needed */
            justify-content: center;
            /* Center the cards horizontally */
        }

        .pricing-card {
            border: 1px solid #676e83;
            border-radius: 12px;
            padding: 20px;
            width: 350px;
            /* Adjust card width */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            background-color: var(--badge-bg) !important;
            color: var(--accent-color) !important;
        }



        .pricing-card.most-popular {
            border: 2px solid #9298ab;
            border-radius: 12px;
            padding: 20px;
            width: 350px;
            box-shadow: 0 4px 8px rgba(158, 134, 255, 0.1);
            background-color: #fff;
            position: relative;
        }

        .popular-badge {
            background-color: #676e83;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            padding: 8px 20px;
            /* Adjust horizontal padding */
            border-radius: 8px 8px 0 0;
            /* Round only top corners */
            position: absolute;
            top: -15px;
            /* Adjust vertical overlap */
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
            width: calc(100% + 4px);
            /* Make it slightly wider than the card */
            margin-left: 0px;
            /* Adjust for the increased width */
            text-align: center;
            /* Center the text within the wider background */
        }

        .card-title {
            font-size: 20px;
            line-height: 32px;
            font-weight: 700;
            color: #2F1C6A;
            margin-bottom: 10px;
            text-align: center;
        }

        .price-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .original-price {
            font-size: 14px;
            color: #777;
            text-decoration: line-through;
            margin-right: 5px;
        }

        .discount {
            font-size: 14px;
            color: #27ae60;
            /* Example green color */
            font-weight: bold;
            margin-right: 10px;
        }

        .current-price {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            line-height: 1;
        }

        .current-price .currency {
            font-size: 16px;
            vertical-align: top;
            margin-right: 2px;
        }

        .current-price .period {
            font-size: 14px;
            color: #777;
            margin-left: 2px;
        }

        .price-details {
            font-size: 14px;
            color: #777;
            text-align: center;
            margin-bottom: 5px;
        }

        .term {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-bottom: 20px;
        }

        .choose-plan-button {
            display: block;
            width: 100%;
            padding: 12px 20px;
            background-color: #fff;
            color: var(--theme-btn-dark);
            /* Example purple */
            border: 2px solid var(--theme-btn-dark);
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-bottom: 15px;
        }

        .choose-plan-button.primary {
            background-color: var(--theme-btn-dark);
            color: #fff;
        }


        .choose-plan-button.primary:hover {
            background-color: #522aca;
            /* Darker purple on hover */
        }

        .renewal-info {
            font-size: 14px;
            line-height: 24px;
            font-weight: 400;
            color: #262831;
            text-align: center;
            margin-bottom: 20px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-item {
            font-size: 14px;
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            position: relative;
            line-height: 24px;
            font-weight: 400;
            font-family: "DM Sans", sans-serif;
            /* cursor: help; <--- REMOVE THIS LINE */
        }

        .simple_pera {
            font-family: "DM Sans", sans-serif;
            font-size: 14px;
            line-height: 24px;
            font-weight: 400;
            color: #6d7081;
        }

        .feature-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            color: #00b090;
            /* Example green */
            font-size: 1.2em;
            line-height: 1;
        }

        .feature-text.free-domain {
            color: #00b090;
            /* Highlight the free domain in the premium plan */
        }





        /* bg deep */
        .extra-benefits-section {
            background-color: #0E121D;
            /* Dark purple background */
            color: #fff;
            border-radius: 12px;
            padding: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1100px;
            /* Adjust as needed */
            margin: 20px auto;
            /* Center the section */
        }

        .benefits-text {
            font-family: "DM Sans", sans-serif;
            font-size: 24px;
            line-height: 32px;
            font-weight: 700;
            color: #ffffff;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(2, auto);
            /* Two columns */
            gap: 20px 30px;
            /* Row and column gap */
        }

        .benefit-item {
            display: flex;
            align-items: center;
        }

        .benefit-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .benefit-label {
            font-family: var(--font-family);
            font-size: 14px;
            margin-left: 8px;
            line-height: 24px;
            font-weight: 400;
            color: #ffffff;
        }

        .h-svgo-icon {
            color: #8c85ff;
            width: 25px;
        }

        /* Another */

        .included-section {
            padding: 60px 20px;
            text-align: center;
            background-color: #ffffff;
            /* Light grey background */
        }

        .included-title {
            font-family: var(--font-family);
            font-size: 36px;
            line-height: 40px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 40px;
        }

        .included-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto 50px;
            justify-items: center;
            /* Center items within their cells */
        }

        /* Target the last two items when they are on their own row (adjust based on your layout) */
        .included-grid>.included-item:nth-last-child(-n+2) {
            margin-left: auto;
            margin-right: auto;
        }

        .included-item {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); */
        }

        .included-grid .item-icon img {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80px !important;
            height: 80px !important;
            margin: 0 auto 20px;
            background-color: #f0f0fa;
            /* Light purple background for icons */
            border-radius: 8px;
            color: #673ab7;
            /* Purple icon color */
        }


        .item-title {
            font-family: var(--font-family);
            font-size: 24px;
            line-height: 32px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .item-description {
            font-family: var(--font-family);
            font-size: 16px;
            line-height: 24px;
            font-weight: 400;
            color: var(--primary-color);
            text-align: center;
        }

        .button-container {
            text-align: center;
            margin-top: 30px;
        }

        .get-started-button {
            background-color: var(--theme-bg-red-dark);
            /* Purple button color */
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .get-started-button:hover {
            background-color: #512da8;
            /* Darker purple on hover */
        }
    </style>
    <section class="section-padding">
        <div class="container-fluid">
            <div class="row addpercel-inner">
                <div class="col-md-12">

                    <h3 class="text-center">Packages</h3>
                </div>
            </div>
            <div class="row addpercel-inner">
                <div class="col-md-12">
                    <div class="">
                        <div class="pricing-section">
                            <div class="pricing-card">
                                <h3 class="card-title text-left">Business Starter</h3>
                                <div class="price-container text-left">
                                    <div class="mb-3">
                                        <span class="pk_crd_uss">₦ 2.99 </span> &nbsp; &nbsp;
                                        <span class="pk_crd_sh"> SAVE 80%</span>
                                    </div>
                                    <div class="price-block text-left">
                                        <span class="currency">₦</span>
                                        <span class="amount">0.59</span>
                                        <span class="duration">/mo</span>
                                    </div>
                                </div>
                                <div class="mb-4 text-left">
                                    <p class="card-text">Price per mailbox</p>
                                    <p class="card-text">For 48-month term</p>
                                </div>
                                <button class="choose-plan-button btn_emp">Choose plan</button>
                                <p class="renewal-info">Renews at ₦ 1.59/mo for 4 years. Cancel anytime.</p>
                                <hr class="hr_line">
                                <ul class="feature-list">
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text"><b>10
                                                GB</b> storage
                                            per mailbox</span>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text dashed">Option
                                            to get extra mailbox storage</span>
                                        <div class="tooltip">Extra mailbox storage is available as an add-on, starting
                                            from
                                            30 GB. You can buy it separately anytime and assign storage to one or
                                            multiple
                                            mailboxes as needed.</div>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span
                                            class="feature-text dashed"><b>50</b>
                                            email aliases</span>
                                        <div class="tooltip">Email aliases let you create 50 additional email addresses
                                            that
                                            link to your main email account. This means you can have different emails
                                            for
                                            your projects, subscribing to services that may send you spam, all without
                                            showing your primary email. Plus, you can send emails from these aliases
                                            too.
                                            Benefits include less spam, organised inboxes, and improved privacy. Use
                                            aliases
                                            to keep your main email safe and your communications sorted.</div>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Antivirus
                                            check</span>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Advanced
                                            anti-spam</span>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Cloud-based
                                            infrastructure</span>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon minus">&#8722;</span> <span class="feature-text">Free
                                            domain</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="pricing-card most-popular">
                                <div class="popular-badge">MOST POPULAR</div>
                                <h3 class="card-title text-left">Business Premium</h3>
                                <div class="price-container text-left">
                                    <div class="mb-3">
                                        <span class="pk_crd_uss">₦ 4.99 </span> &nbsp; &nbsp;
                                        <span class="pk_crd_sh"> SAVE 80%</span>
                                    </div>
                                    <div class="price-block text-left">
                                        <span class="currency">₦</span>
                                        <span class="amount">0.59</span>
                                        <span class="duration">/mo</span>
                                    </div>
                                </div>
                                <div class="mb-4 text-left">
                                    <p class="card-text">Price per mailbox</p>
                                    <p class="card-text">For 48-month term</p>
                                </div>
                                <button class="choose-plan-button primary">Choose plan</button>
                                <p class="renewal-info">Renews at ₦ 3.99/mo for 4 years. Cancel anytime.</p>
                                <hr class="hr_line">
                                <ul class="feature-list text-left">
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text"><b>10
                                                GB</b> storage
                                            per mailbox</span>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text dashed">Option
                                            to get extra mailbox storage</span>
                                        <div class="tooltip">Extra mailbox storage is available as an add-on, starting
                                            from
                                            30 GB. You can buy it separately anytime and assign storage to one or
                                            multiple
                                            mailboxes as needed.</div>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span
                                            class="feature-text dashed"><b>50</b>
                                            email aliases</span>
                                        <div class="tooltip">Email aliases let you create 50 additional email addresses
                                            that
                                            link to your main email account. This means you can have different emails
                                            for
                                            your projects, subscribing to services that may send you spam, all without
                                            showing your primary email. Plus, you can send emails from these aliases
                                            too.
                                            Benefits include less spam, organised inboxes, and improved privacy. Use
                                            aliases
                                            to keep your main email safe and your communications sorted.</div>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Antivirus
                                            check</span>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Advanced
                                            anti-spam</span>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span class="feature-text">Cloud-based
                                            infrastructure</span>
                                    </li>
                                    <li class="feature-item">
                                        <span class="feature-icon">&#10004;</span> <span
                                            class="feature-text dashed"><b>Free</b>
                                            domain</span>
                                        <div class="tooltip">Email aliases let you create 50 additional email addresses
                                            that
                                            link to your main email account. This means you can have different emails
                                            for
                                            your projects, subscribing to services that may send you spam, all without
                                            showing your primary email. Plus, you can send emails from these aliases
                                            too.
                                            Benefits include less spam, organised inboxes, and improved privacy. Use
                                            aliases
                                            to keep your main email safe and your communications sorted.</div>
                                    </li>

                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- submenu dependency --}}
    {{-- submenu dependency --}}


@endsection

@section('custom_js_scripts')
    <script></script>

@endsection
