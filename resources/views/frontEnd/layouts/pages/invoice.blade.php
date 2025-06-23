<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $show_data->trackingCode }}</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            font-size: 11px;
            font-weight: 400;
            box-sizing: border-box;
            color: black;
            font-family: 'Noto Sans', sans-serif;
        }

        body {
            background: rgb(233, 233, 233);
        }

        .containerr {
            width: 800px;
            margin: 0 auto;
            min-height: 500px;
            background: #fff;
            padding: 15px 10px;
        }

        .roww {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .coll {
            flex: 1;
        }

        .coll-1 {
            width: calc(100%-60.6%)
        }

        .coll-3 {
            width: calc(100% - 40.3%)
        }

        .coll-8 {
            width: calc(100% - 30%)
        }

        .logo img {
            max-width: 200px;
        }

        .qrcode {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .borderr {
            border: 2px solid rgb(122, 121, 121);
        }

        .borderr1 {
            border: 1px solid rgb(122, 121, 121);
        }

        .p-1 {
            padding: 2px;
        }

        .pl-1 {
            padding-left: 10px;
        }

        .px-4 {
            padding-left: 40px;
            padding-right: 40px;
        }

        .pb-4 {
            padding-bottom: 40px;
        }

        .p-0 {
            padding: 0px;
        }

        .mt-1 {
            margin-top: 7px;
        }

        .mt-2 {
            margin-top: 12px;
        }

        .ml-1 {
            margin-left: 10px;
        }

        .mr-1 {
            margin-right: 10px;
        }

        .color2 {
            color: rgb(4, 4, 177);
        }

        .bold {
            font-weight: 600;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .w-name {
            min-width: 75px;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            font-weight: 600;
        }

        td,
        th {
            border: 1px solid rgb(79, 78, 78);
            padding: 4px 0;
            padding-left: 10px;
            text-align: left;
        }

        .content-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .w-50 {
            width: 50%;
        }

        .d-flex {
            display: flex;
        }

        .flex-roww {
            flex-direction: row;
        }

        .borderr-bottom {
            border-bottom: 2px solid rgb(79, 78, 78);
        }

        .borderr-left {
            border-left: 2px solid rgb(79, 78, 78);
        }

        .p-05 {
            padding: 5px;
        }

        .coll-4 {
            width: 100% !important;
        }

        h3 {
            font-size: 18px;
            text-decoration: underline;
        }

        .terms li {
            font-size: 10px !important;
        }

        .text-red {
            color: red;
        }

        h1 {
            font-size: 20px;
            font-weight: 600;
        }

        .pb-2 {
            padding-bottom: 12px;
        }

        .container2 {
            max-width: 600px;
            margin: 0 auto;
        }

        @page {
            size: auto;
            margin: 0mm;
        }

        @media print {

            header,
            footer,
            .heading-bar,
            .pickup-modal-area,
            .dash-sidebar,
            .container2,
            .modal-header {
                display: none !important;
            }
        }

        .logo img {
            width: 100px !important;
            text-align: center;
        }

        p {
            padding: 1px !important;
        }

        td,
        th {
            padding: 1px 5px !important;
        }

        .px-2 {
            padding: 0px 20px !important;
        }

        .py-2 {
            padding: 20px 0px !important;
        }

        .pt-2 {
            padding-top: 20px !important;
        }

        .border-right-none {
            border-right: none !important;
        }

        .border-left-none {
            border-left: none !important;
        }

        .header-text {
            font-size: 15px !important;
        }

        .medium-text {
            font-size: 15px !important;
            padding: 1px;
        }

        .amontDue {
            font-weight: 900 !important;
        }
    </style>
</head>

<body>
    <div class="container2">
        <div class="roww">
            <div class="text-center">
                <button onclick="myFunction2()"
                    style="color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px;background: green"><i
                        class="fa fa-print"></i>Print</button>
                {{-- <a href="{{ route('parcel.pdf', $show_data->id) }}"
                    style="color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px;background: rgb(249, 120, 0); text-decoration:none"
                    target="_blank">PDF</a> --}}
            </div>
        </div>
    </div>

    <div class="containerr px-2 pt-2 pb-4">

        <div class="roww">
            <div class="col">
                <div class="logo">
                    @foreach ($whitelogo as $key => $logo)
                        <img src="{{ asset($logo->image) }}">
                    @endforeach
                </div>
                <h4 class="header-text" style="font-weight: 700; text-decoration:underline">SHIPMENT DELIVERY WAYBILL
                </h4>
            </div>
            <div class="col">
                <div class="qrcode">
                    {{-- <?php echo DNS2D::getBarcodeSVG($show_data->trackingCode, 'QRCODE', 2, 2); ?> --}}
                    {{-- {!! DNS1D::getBarcodeSVG('897879', 'C39', 0.9, 50, 'black', true) !!} --}}
                    <?php echo DNS1D::getBarcodeSVG($show_data->trackingCode, 'C128', 2, 50, 'black', false); ?>
                </div>

            </div>
        </div>
        <div class="roww borderr">
            <div class="col">
                <p class="medium-text">
                    <strong class="medium-text">Waybill Number : </strong>
                    <span class="color2 bold medium-text">{{ $show_data->trackingCode }}</span>
                </p>
            </div>
            <div class="col" style="margin-left: -20px">
                <p class="medium-text">
                    <strong class="medium-text">Order Number : </strong>
                    <span class="color2 bold medium-text">{{ $show_data->order_number }}</span>
                </p>
            </div>
            <div class="col">
                <p class="text-right medium-text">
                    <strong class="medium-text">Date : </strong>
                    <span class="color2 bold medium-text">{{ date('M-d-Y', strtotime($show_data->created_at)) }}</span>
                </p>
            </div>
        </div>
        {{-- <div class="roww borderr" style="border-top: none">
            <p>
                <span class="bold">Order Description : </span>
                {{ $show_data->note }}
            </p>
        </div> --}}
        <?php
        $merchantDetails = $show_data->getMerchantOrSenderDetails();
        ?>
        <div class="roww mt-1">
            @if ($show_data->parcel_source == 'p2p')
                <div class="coll mr-1">
                    <p class="bold uppercase header-text">SENDER INFORMATION</p>
                    <div class="borderr p-1">
                        <p class="uppercase "><span class="w-name ">Name :</span> <span
                                class="color2">{{ $show_data->p2pParcel->sender_name }}
                            </span></p>
                        <p class="uppercase " style="min-height: 35px"><span class="w-name">Address :</span> <span
                                class="color2" style="text-indent: 10%">
                                {{ $show_data->p2pParcel->sender_address }}</span></p>
                        <p class="uppercase "><span class="w-name">PICK UP City/Town :</span> <span
                                class="color2">{{ $show_data->pickupcity->title }} /
                                {{ $show_data->pickuptown->title }}</span></p>
                        <p class="uppercase "><span class="w-name">Phone :</span> <span
                                class="color2">{{ $show_data->p2pParcel->sender_mobile }}</span></p>
                        {{-- <p class="uppercase "><span class="w-name">Email :</span> <span
                                class="color2">{{ @$merchantDetails->emailAddress }}</span></p> --}}
                        <p></p>
                    </div>
                </div>
            @else
                <div class="coll mr-1">
                    <p class="bold uppercase header-text">SENDER INFORMATION</p>
                    <div class="borderr p-1">
                        <p class="uppercase"><span class="w-name">Merchant :</span> <span
                                class="color2">{{ $merchantDetails->companyName }}</span></p>
                        <p class="uppercase " style="min-height: 35px"><span class="w-name ">Name :</span> <span
                                class="color2">{{ @$merchantDetails->firstName }}
                                {{ @$merchantDetails->lastName }}</span></p>
                        <p class="uppercase "><span class="w-name">PICK UP City/Town :</span> <span
                                class="color2">{{ $show_data->pickupcity->title }} /
                                {{ $show_data->pickuptown->title }}</span></p>
                        <p class="uppercase "><span class="w-name">Phone :</span> <span
                                class="color2">{{ $merchantDetails->phoneNumber }}</span></p>
                        {{-- <p class="uppercase "><span class="w-name">Email :</span> <span
                                class="color2">{{ @$merchantDetails->emailAddress }}</span></p> --}}
                        <p></p>
                    </div>
                </div>
            @endif

            <div class="coll ml-1">
                <p class="bold uppercase header-text">Recipient INFORMATION</p>
                <div class="borderr p-1">
                    <p class="uppercase"><span class="w-name">Name :</span> <span
                            class="color2">{{ $show_data->recipientName }}</span></p>
                    <p class="uppercase " style="min-height: 35px"><span class="w-name">Address :</span> <span
                            class="color2" style="text-indent: 10%"> {{ $show_data->recipientAddress }}</span></p>
                    <p class="uppercase "><span class="w-name">Delivery City/Town :</span> <span
                            class="color2">{{ $show_data->deliverycity->title }} /
                            {{ $show_data->deliverytown->title }}</span></p>
                    <p class="uppercase"><span class="w-name">Phone :</span> <span
                            class="color2">{{ $show_data->recipientPhone }}</span></p>
                    <!--<p class="uppercase mt-1"><span class="w-name">Email :</span> <span class="color2">{{ @$show_data->recipientEmail }}</span></p>-->
                </div>
            </div>
        </div>
        <div class="roww mt-1">
            <div class="coll borderr1">
                <table>
                    <tr>
                        <th class="uppercase">Product Name</th>
                        <th class="uppercase">Order Description</th>
                        <th class="uppercase" width="50">Weight</th>
                        <th class="uppercase" width="150">Colour</th>
                        <th class="uppercase" width="40">QTY</th>
                    </tr>
                    <tr>
                        <td>{{ @$show_data->productName }}</td>
                        <td>{{ $show_data->note }}</td>
                        <td>{{ $show_data->productWeight }}</td>
                        <td>{{ @$show_data->productColor }}</td>
                        <td>{{ @$show_data->productQty }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="roww borderr" style="margin-top: -2px;">
            <div class="coll-8 p-1 content-center">
                <div class="qrcode" style="margin: 10px; padding: 10px">

                    <?php echo DNS1D::getBarcodeSVG($show_data->trackingCode, 'C128', 1.4, 50, 'black', false); ?>

                </div>

                <div class="">

                    @if ($show_data->parcel_source == 'p2p' && $show_data->p2p_payment_option == 'pay_later')
                        @php
                            $discountamount = $show_data->discounted_value;
                            if ($discountamount > 0) {
                                $AmountDue = $show_data->discounted_value;
                            } else {
                                $AmountDue = $show_data->deliveryCharge + $show_data->insurance + $show_data->tax;
                            }
                        @endphp
                        <h1 class="uppercase text-red amontDue">Amount Due: ₦{{ number_format($AmountDue, 2) }}
                        </h1>
                    @else
                        @php $cod = $show_data->cod > 0 ? $show_data->cod : "00.00" @endphp
                        <h1 class="uppercase text-red amontDue">Amount Due: ₦{{ number_format($cod, 2) }}</h1>
                    @endif



                </div>
            </div>
            <div class=" borderr-left">
                <table>
                    <tr>
                        @php $marchentAmount = (($show_data->deliveryCharge + $show_data->codCharge + $show_data->tax + $show_data->insurance) - $show_data->cod) * (-1);  @endphp
                        <th class="border-right-none">Merchant Amount</th>
                        <td class="border-left-none text-right bold color2">{{ number_format($marchentAmount, 2) }} N
                        </td>
                    </tr>
                    <tr>
                        <th class="border-right-none">Delivery Charge</th>
                        <td class="border-left-none text-right bold color2">
                            {{ number_format($show_data->deliveryCharge, 2) }} N</td>
                    </tr>
                    <tr>
                        <th class="border-right-none">COD Charge</th>
                        <td class="border-left-none text-right bold color2">
                            {{ number_format($show_data->codCharge, 2) }} N</td>
                    </tr>
                    <tr>
                        <th class="border-right-none">Tax</th>
                        <td class="border-left-none text-right bold color2">{{ number_format($show_data->tax, 2) }} N
                        </td>
                    </tr>
                    <tr>
                        <th class="border-right-none">Insurance</th>
                        <td class="border-left-none text-right bold color2">
                            {{ number_format($show_data->insurance, 2) }} N</td>
                    </tr>
                    @php
                        $total = $marchentAmount + (float)$show_data->deliveryCharge
                            + (float)$show_data->codCharge
                            + (float)$show_data->tax
                            + (float)$show_data->insurance;
                    @endphp

                    {{-- @dd($marchentAmount, $show_data->deliveryCharge, $show_data->codCharge, $show_data->tax, $show_data->insurance, $total); --}}
                    <tr>
                        <th class="border-right-none">Total Amount</th>
                        <td class="border-left-none text-right bold color2">{{ number_format($total, 2) }} N</td>
                    </tr>
                    @if ($show_data->parcel_source == 'p2p')
                        <tr>
                            <th class="border-right-none">Declared Amount</th>
                            <td class="border-left-none text-right bold color2">
                                {{ number_format($show_data->package_value, 2) }} N</td>
                        </tr>
                    @endif
                </table>

            </div>
        </div>
        <div class="roww borderr mt-1 p-1 pb-2">
            <div class="coll-4">
                <h5 class="text-center">AWB Terms and condition</h5>
                <ol class="terms">



                    <li>
                        Merchants shall maintain, at their own expense, a 1% Goods-In-Transit Insurance for each Air
                        Waybill.
                    </li>
                    <li>
                        Where Zidrop Express receives sealed shipments contents of which are not ascertainable on the
                        face of the package, Zidrop Express shall not be liable for any defects therein especially where
                        the package is not visibly defaced.
                    </li>
                    <li>
                        Ensure that the value of each shipment is properly described on the Shipping invoice.
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <script>
        function myFunction2() {
            window.print();
        }
    </script>
</body>

</html>
