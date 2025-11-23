<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            font-size: 11px;
            font-weight: 400;
            box-sizing: border-box;
            color: black;
            font-family: 'dejavusans';
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

        .header-text {
            font-size: 15px !important;
        }

        .medium-text {
            font-size: 15px !important;
            padding: 1px;
        }
    </style>
    <style>
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table th {
            font-weight: 600;
            font-size: 12px;
            padding: 3px;
        }

        .text-center {
            text-align: center;
        }

        .text-7 {
            font-size: 7px;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .header {
            line-height: 0.2;
            font-size: 12px;
        }

        .row {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .col-md-2 {
            width: 16.6666666667%;
        }

        .col-md-3 {
            width: 25%;
        }

        .col-md-1 {
            width: 8.3333333333%;
        }

        .col-md-7 {
            width: 58.3333333333%;
        }

        .col-md-8 {
            width: 66.6666666667%;
        }

        h4 {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .input_field {
            width: 50px;
            margin-left: 20px;
            border: solid 1px rgb(4, 139, 207);
            padding: 3px 5px;

        }

        .input_field2 {
            width: 100px;
            margin-left: 20px;
            border: solid 0.5px rgb(61, 171, 226);
            padding: 3px 5px;

        }

        table,
        thead,
        tr,
        th,
        td {
            border: 1px solid rgb(0, 0, 0) !important;
            border-collapse: collapse;
        }

        #hearder table,
        #hearder thead,
        #hearder tr,
        #hearder th,
        #hearder td {
            border: 0px solid fff !important;
            border-collapse: collapse;
        }

        .logo img {
            width: 100px !important;
            text-align: center;
        }

        .no-border {
            border: 0px solid !important;
        }

        .bottomTR {
            margin-top: 20px;
            border-top: 2px solid rgb(79, 78, 78);
        }

        main {
            padding: 10px;
        }

        table tr {
            margin: -5px;
        }

        .top-border-none {
            border-top: 0px solid !important;
        }

        .bottom-border-none {
            border-bottom: 0px solid !important;
        }

        .left-border-none {
            border-left: none !important;
        }

        .right-border-none {
            border-right: none !important;
        }

        .page {
            width: 210mm;
            /* A4 width */
            height: 290mm;
            /* A4 height */
            margin: 0 auto;
        }
        .page:last-child {
            page-break-after: auto !important;
        }
                body {
            margin: 0;
            padding: 0;
        }
        table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    @foreach ($parcels as $index => $show_data)
    @if ($index !== count($parcels) - 1)
        <div class="page" style="page-break-after: always;">
    @else
        <div class="page">
    @endif
            @php $marchentAmount = (($show_data->deliveryCharge + $show_data->codCharge + $show_data->tax + $show_data->insurance) - $show_data->cod) * (-1);  @endphp

            <table class="no-border">
                <tr class="no-border" style="">
                    <td class="no-border">
                        <div class="">
                            <img src="https://zidrop.com/email/images/Logo-For-Zidrop-Logistics.png"
                                style=" display:block; margin-bottom: 10px; height: 40px;">
                        </div>

                    </td>
                    <td style=" border:none"></td>
                    <td class="no-border text-right">
                        <div class="qrcode">
                            <?php
                            $Qrcode2 = DNS1D::getBarcodeSVG($show_data->trackingCode, 'C128', 2, 50, 'black', false);
                            $Qrcode2 = str_replace('<?xml version="1.0" standalone="no"?>', '', $Qrcode2);
                            echo $Qrcode2; ?>
                        </div>
                    </td>
                </tr>
                <tr style="border:none">
                    <td colspan="2" style="border:none">
                        <h4 class=""
                            style="font-size:18px; font-family:dejavusans; font-weight: bold; text-decoration:underline;">
                            SHIPMENT
                            DELIVERY WAYBILL
                        </h4>
                    </td>

                </tr>
                <tr style="border: 2px solid rgb(122, 121, 121)">
                    <td style="margin: -5px; border-right:none; width:30%">
                        <p class="">
                        <h4 style="font-family:dejavusans; font-size:14px;">Waybill : <span class="color2 bold"
                                style="font-family:dejavusans; font-weight: bold">{{ $show_data->trackingCode }}</span>
                        </h4>

                        </p>
                    </td>
                    <td style=" border:none; text-align:center; width:40%">
                            <p class="">
                                <h4 style="font-family:dejavusans; font-size:14px;">Order Number : <span class="color2 bold"
                                    style="font-family:dejavusans; font-weight: bold">{{ $show_data->order_number }}</span>
                                </h4>
                            </p>
                    </td>
                    <td style="border-left:none; text-align: right; width:30%">
                        <p class="text-right ">
                        <h4 style="font-family:dejavusans; font-size:14px; text-align: right">Date : <span
                                class="color2 bold"
                                style="font-family:dejavusans; font-weight: bold; text-transform: uppercase;">{{ date('M-d-Y', strtotime($show_data->created_at)) }}</span>
                        </h4>

                        </p>
                    </td>
                </tr>
            </table>
            <table class="no-border" style=" width: 100%; margin-top:10px">
                <tr class="no-border">
                    <th class="no-border" style=" width: 50%">
                        <p class=" header-text" style="font-weight: bold">SENDER INFORMATION </p>
                    </th>
                    <th class="no-border" style=" width: 50%">
                        <p class="header-text" style="font-weight: bold; padding-left: 2px;">&nbsp; RECIPIENT
                            INFORMATION</p>
                    </th>
                </tr>
            </table>
            <table class="no-border"
                style="font-family:dejavusans; font-size:10px; font-weight:400; border-collapse: separate;  width: 100%; vertical-align: top; text-transform: uppercase;">
                <tr class="no-border"
                    style="font-family:dejavusans; font-size:10px; font-weight:400;  vertical-align: top; text-transform: uppercase;">
                    <td class=""
                        style="font-family:dejavusans; font-size:10px; font-weight:400; margin-right: 20px width: 49%; border: 2px solid rgb(122, 121, 121); vertical-align: top">
                        @if ($show_data->parcel_source == 'p2p')
                            <p class=" " style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                    class="w-name "
                                    style="font-family:dejavusans; font-size:10px; font-weight:400;">NAME :</span>
                                <span style="width: 10px"></span>
                                <span class="color2"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; text-transform: uppercase; padding-left: 10px;">&nbsp;&nbsp;&nbsp;
                                    &nbsp; &#8201;{{ $show_data->p2pParcel->sender_name }}</span>
                            </p>
                            <table class="no-border" style="border-collapse: collapse; width: 100%;">
                                <tr class="no-border">
                                    <td class="no-border"
                                        style="height: 35px; text-align: left; vertical-align: top; padding: 0; margin: 0;">
                                        <p class="uppercase"
                                            style="font-family:dejavusans; font-size:10px; font-weight:400; height: 35px; text-align: left; margin: 0; padding: 0;">
                                            <span class="w-name"
                                                style="color:black; font-family:dejavusans; font-size:10px; font-weight:400;">ADDRESS
                                                :</span>
                                            <span class="color2"
                                                style="font-family:dejavusans; font-size:10px; font-weight:400; margin: 0; padding: 0; text-transform: uppercase;">
                                                {{ $show_data->p2pParcel->sender_address }}
                                            </span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p class="uppercase " style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                    class="w-name" style="font-family:dejavusans; font-size:10px; font-weight:400;">PICK
                                    UP CITY/TOWN :</span> <span class="color2"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; padding-left: 5px; text-transform: uppercase;">{{ $show_data->pickupcity->title }}
                                    /
                                    {{ $show_data->pickuptown->title }}</span></p>

                            <p class=" " style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                    class="w-name"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; padding-bottom: 2px">PHONE
                                    :</span> <span class="color2"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; margin-left: 20px; text-transform: uppercase; padding-bottom: 2px; text-indent: 10%">&nbsp;&nbsp;&nbsp;&nbsp;{{ $show_data->p2pParcel->sender_mobile }}</span>
                            </p>

                            <p></p>
                        @else
                            <p class="uppercase" style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                    class="w-name"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400;">MERCHANT :</span>
                                <span class="color2"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; text-transform: uppercase; text-indent: 10%">
                                    {{ $show_data->merchant->companyName }}</span></p>
                            <p class=" " style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                    class="w-name "
                                    style="font-family:dejavusans; font-size:10px; font-weight:400;">NAME :</span> <span
                                    class="color2"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; margin-left: 20px; text-transform: uppercase; text-indent: 10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $show_data->merchant->firstName }}
                                    {{ $show_data->lastName }}</span>
                            </p>
                            <table class="no-border" style="border-collapse: collapse; width: 100%;">
                                <tr class="no-border">
                                    <td class="no-border"
                                        style="height: 25px; text-align: left; vertical-align: top; padding: 0; margin: 0;">
                                        <p class="uppercase"
                                            style="font-family:dejavusans; font-size:10px; font-weight:400; height: 25px; text-align: left; margin: 0; padding: 0;">
                                            <span class="w-name" style="color:black"></span>
                                            <span class="color2" style="margin: 0; padding: 0;">

                                            </span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p class="uppercase " style="font-family:dejavusans; font-size:10px; font-weight:400;">
                                <span class="w-name"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400;">PICK UP CITY/TOWN
                                    :</span> <span class="color2"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; padding-left: 5px; text-transform: uppercase;">{{ $show_data->pickupcity->title }}
                                    /
                                    {{ $show_data->pickuptown->title }}</span></p>
                            <p class=" " style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                    class="w-name"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; padding-bottom: 2px; ">PHONE
                                    :</span> <span class="color2"
                                    style="font-family:dejavusans; font-size:10px; font-weight:400; padding-left: 20px; text-transform: uppercase; padding-bottom: 2px; text-indent: 10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $show_data->merchant->phoneNumber }}</span>
                            </p>

                            <p></p>
                        @endif
                    </td>

                    <td class="no-border" style="width: 2%"></td>
                    <td class=""
                        style="margin-left: 20px;  width: 49%; border: 2px solid rgb(122, 121, 121); vertical-align: top">

                        <p class="" style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                class="w-name"
                                style="font-family:dejavusans; font-size:10px; font-weight:400; padding-right: 20px">NAME
                                :</span>
                            <span style="width: 10px"></span>
                            <span class="color2"
                                style="font-family:dejavusans; font-size:10px; font-weight:400; margin-left: 20px; text-transform: uppercase;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{ $show_data->recipientName }}</span>
                        </p>

                        <table class="no-border" style="border-collapse: collapse; width: 100%;">
                            <tr class="no-border">
                                <td class="no-border"
                                    style="height: 35px; text-align: left; vertical-align: top; padding: 0; margin: 0;">
                                    <p class="uppercase"
                                        style="font-family:dejavusans; font-size:10px; font-weight:400; height: 35px; text-align: left; margin: 0; padding: 0;">
                                        <span class="w-name"
                                            style="font-family:dejavusans; font-size:10px; font-weight:400; color:black">ADDRESS
                                            :</span>
                                        <span class="color2"
                                            style="font-family:dejavusans; font-size:10px; font-weight:400; margin: 0; padding: 0; text-transform: uppercase;">
                                            {{ $show_data->recipientAddress }}
                                        </span>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <p class=" " style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                class="w-name"
                                style="font-family:dejavusans; font-size:10px; font-weight:400;">DELIVERY CITY/TOWN
                                :</span> <span class="color2"
                                style="font-family:dejavusans; font-size:10px; font-weight:400; padding-left: 5px; text-transform: uppercase;">{{ $show_data->deliverycity->title }}
                                /
                                {{ $show_data->deliverytown->title }}</span></p>
                        <p class="" style="font-family:dejavusans; font-size:10px; font-weight:400;"><span
                                class="w-name"
                                style="font-family:dejavusans; font-size:10px; font-weight:400; padding-bottom: 2px">PHONE
                                :</span> <span class="color2"
                                style="font-family:dejavusans; font-size:10px; font-weight:400; padding-left: 20px; text-transform: uppercase; padding-bottom: 2px">&nbsp;&nbsp;&nbsp;
                                &#8201;{{ $show_data->recipientPhone }}</span></p>

                    </td>
                </tr>
            </table>
            <table class="" style="margin-top: 5px; border: 2px solid rgb(122, 121, 121); border-bottom:none">
                <tr
                    style="font-family:dejavusans; font-size:12px; font-weight:bold; border: 0.5px solid  rgb(122, 121, 121); ">
                    <th class="uppercase"
                        style="font-family:dejavusans; font-size:10px; font-weight:bold;  border: 0.5px solid  rgb(122, 121, 121); ">
                        PRODUCT NAME
                    </th>
                    <th class="uppercase"
                        style="font-family:dejavusans; font-size:10px; font-weight:bold;  border: 0.5px solid  rgb(122, 121, 121); ">
                        ORDER
                        DESCRIPTION</th>
                    <th class="uppercase" width="50"
                        style="font-family:dejavusans; font-size:10px; font-weight:bold;  border: 0.5px solid  rgb(122, 121, 121); ">
                        WEIGHT</th>
                    <th class="uppercase" width="150"
                        style="font-family:dejavusans; font-size:10px; font-weight:bold;  border: 0.5px solid  rgb(122, 121, 121); ">
                        COLOUR</th>
                    <th class="uppercase" width="40"
                        style="font-family:dejavusans; font-size:10px; font-weight:bold;  border: 0.5px solid  rgb(122, 121, 121); ">
                        QTY</th>
                </tr>
                <tr style="border:0.5px solid;  border: 0.5px solid  rgb(122, 121, 121); ">
                    <td style=" border: 0.5px solid  rgb(122, 121, 121); ">{{ @$show_data->productName }}</td>
                    <td style=" border: 0.5px solid  rgb(122, 121, 121); ">{{ $show_data->note }}</td>
                    <td style=" border: 0.5px solid  rgb(122, 121, 121); ">{{ $show_data->productWeight }}</td>
                    <td style=" border: 0.5px solid  rgb(122, 121, 121); ">{{ @$show_data->productColor }}</td>
                    <td style=" border: 0.5px solid  rgb(122, 121, 121); ">{{ @$show_data->productQty }}</td>
                </tr>
            </table>
            @php $cod = $show_data->cod > 0 ? $show_data->cod : "00.00" @endphp
            @php $total = (float)$marchentAmount + (float) $show_data->deliveryCharge + (float)$show_data->codCharge + (float)$show_data->tax  + (float)$show_data->insurance   @endphp

            <table class="" style="margin-top: 0px; width:100%; ">
                <tr class=" top-border-none right-border-none"
                    style="border-top: none">
                    <td class="no-border" style="width:35%; padding: 8px;padding-left:10%;">
                        <span style="text-align:right;padding: 10px;margin-left:20%;">
                            {{-- @php echo DNS2D::getBarcodeSVG($show_data->trackingCode, 'QRCODE', 5, 5); @endphp --}}
                            @php
                                $svg = DNS2D::getBarcodeSVG($show_data->trackingCode, 'QRCODE', 5, 5);
                                // Remove the XML declaration
                                $svg = preg_replace('/<\?xml.*?\?>/', '', $svg);
                                echo $svg;
                            @endphp
                        </span>
                    </td>

                    <td class="no-border" style="width:65%; text-align: center">
                        @if ($show_data->parcel_source == 'p2p' && $show_data->p2p_payment_option == 'pay_later')
                            @php
                                $discountamount = $show_data->discounted_value;
                                if ($discountamount > 0) {
                                    $AmountDue = $show_data->discounted_value;
                                } else {
                                    $AmountDue = $show_data->deliveryCharge + $show_data->insurance + $show_data->tax;
                                }
                            @endphp
                            <span>
                                <h1 class="uppercase text-red"
                                    style="font-size:26px !important;font-family:dejavusans; margin-top:-50px; text-align: right; padding-right: 20px; font-weight:bold;">
                                    Amount Due: ₦{{ number_format($AmountDue, 2) }}
                                </h1>
                            </span>
                        @else
                            @php
                                $cod = $show_data->cod > 0 ? $show_data->cod : '00.00';
                            @endphp
                            <h1 class="uppercase text-red amontDue"
                                style="font-size:26px; font-family:dejavusans; margin-top:-50px; text-align: right; padding-right: 20px; font-weight:bold">
                                Amount Due: ₦{{ number_format($cod, 2) }}</h1>
                        @endif
                    </td>
                    {{-- <td class="no-border"
                        style="font-family:dejavusans; font-size:12px; font-weight: bold; padding: 0px; width:35%; border:none">
                        <table class="no-border" style="border-left: 2px solid rgb(122, 121, 121); border-top:none">
                            <tr class="top-border-none"
                                style="border: 0.5px solid  rgb(122, 121, 121); border-top:none">
                                <th class="no-border"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold; border-top:none">
                                    Merchant Amount</th>

                                <td class="no-border bold color2 text-right"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold; border-top:none">
                                    @php $marchentAmount = (($show_data->deliveryCharge + $show_data->codCharge + $show_data->tax + $show_data->insurance) - $show_data->cod) * (-1);  @endphp
                                    {{ number_format($marchentAmount, 2) }} N</td>
                            </tr>
                            <tr class="" style="border: 0.5px solid  rgb(122, 121, 121)">
                                <th class="no-border"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold">
                                    Delivery Charge</th>
                                <td class="no-border bold color2 text-right"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold">
                                    {{ number_format($show_data->deliveryCharge, 2) }} N</td>
                            </tr>
                            <tr class="" style="border: 0.5px solid  rgb(122, 121, 121)">
                                <th class="no-border"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold">COD
                                    Charge</th>
                                <td class="no-border bold color2 text-right"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold">
                                    {{ number_format($show_data->codCharge, 2) }} N
                                </td>
                            </tr>
                            <tr style="border: 0.5px solid  rgb(122, 121, 121)">
                                <th class="no-border"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold">
                                    Tax</th>
                                <td class="no-border bold color2 text-right"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold">
                                    {{ number_format($show_data->tax, 2) }} N
                                </td>
                            </tr>
                            <tr style="border: 0.5px solid  rgb(122, 121, 121)">
                                <th class="no-border"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold">
                                    Insurance</th>
                                <td class="no-border bold color2 text-right"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold">
                                    {{ number_format($show_data->insurance, 2) }} N
                                </td>
                            </tr>
                            @php $total = (float)$marchentAmount + (float) $show_data->deliveryCharge + (float)$show_data->codCharge + (float)$show_data->tax  + (float)$show_data->insurance   @endphp
                            <tr class="bottom-border-none"
                                style="border: 0.5px solid  rgb(122, 121, 121); border-bottom:none">
                                <th class="no-border"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold; border-bottom:none">
                                    Total Amount</th>
                                <td class="no-border bold color2 text-right"
                                    style="font-family:dejavusans; font-size:12px; font-weight:bold; border-bottom:none">
                                    {{ number_format($total, 2) }} N</td>
                            </tr>
                            @if ($show_data->parcel_source == 'p2p')
                                <tr class="bottom-border-none"
                                    style="border: 0.5px solid  rgb(122, 121, 121); border-bottom:none">
                                    <th class="no-border"
                                        style="font-family:dejavusans; font-size:12px; font-weight:bold; border-bottom:none">
                                        Custom Amount</th>
                                    <td class="no-border bold color2 text-right"
                                        style="font-family:dejavusans; font-size:12px; font-weight:bold; border-bottom:none">
                                        {{ number_format($show_data->discounted_value, 2) }} N</td>
                                </tr>
                            @endif
                        </table>
                    </td> --}}
                </tr>

            </table>

            <table style="font-family:dejavusans; margin-top: 10px; border: 2px solid rgb(122, 121, 121)">
                <tr class="no-border" style="text-align: center; margin:auto;">
                    <td class="no-border" style="text-align: center; margin:auto;">
                        <h4 style="text-align: center; margin:auto;">AWB Terms and condition</h4>
                    </td>
                </tr>
                <tr class="no-border" style=" margin:auto; ">
                    <td class="no-border" style="text-align: left; margin:auto; padding: 10px">
                        <ol class="terms" style="font-size: 10px">
                            <li>
                                Merchants shall maintain, at their own expense, a 1% Goods-In-Transit Insurance
                                for each Air
                                Waybill.
                            </li>
                            <li>
                                Where Zidrop Express receives sealed shipments contents of which are not
                                ascertainable on the
                                face of the package, Zidrop Express shall not be liable for any defects therein
                                especially where
                                the package is not visibly defaced.
                            </li>
                            <li>
                                Ensure that the value of each shipment is properly described on the Shipping
                                invoice.
                            </li>
                        </ol>
                    </td>
                </tr>
            </table>

            {{-- @if ($index < count($parcels) - 1)
                <br>
                <br>
            @endif --}}
        </div>
    @endforeach
</body>

</html>
