
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $show_data->trackingCode }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            font-size: 13px;
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
            min-height: 600px;
            background: #fff;
            padding: 15px;
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
            padding: 5px;
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
        td,th {
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
    </style>
</head>
<body>
            <div class="container2">
                <div class="roww">
                    <div class="text-center">
                        <button onclick="myFunction2()"
                                style="color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px;background: green"><i
                                    class="fa fa-print"></i>Print</button>
                    </div>
                </div>
            </div>
                     
            <div class="containerr px-4 pb-4">

                    <div class="roww">
                        <div class="col">
                            <div class="logo">
                                @foreach ($whitelogo as $key => $logo)
                                    <img src="{{ asset($logo->image) }}">
                                @endforeach
                            </div>
                        </div>
                        <div class="col">
                            <div class="qrcode">
                                <?php echo DNS2D::getBarcodeSVG(url('/') . '/track/parcel/' . $show_data->trackingCode, 'QRCODE', 2, 2); ?>
                                <!--{!! DNS1D::getBarcodeSVG('897879', 'C39',0.9,50,'black',true) !!}-->
                            </div>
                        </div>
                    </div>
                    <div class="roww borderr p-1 mt-2">
                        <div class="col">
                            <p>
                                <strong>Waybill Number : </strong>
                                <span class="color2 bold">{{ $show_data->trackingCode }}</span>
                            </p>
                        </div>
                        <div class="col">
                            <p class="text-right">
                                <strong>Date : </strong>
                                <span class="color2 bold">{{ date('M-d-Y', strtotime($show_data->created_at)) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="roww borderr mt-1 p-1">
                        <p>
                            <span class="bold">Order Description : </span>
                            {{ $show_data->note }}
                        </p>
                    </div>
                    <div class="roww mt-2">
                        <div class="coll mr-1">
                            <p class="bold uppercase">SENDER INFORMATION</p>
                            <div class="borderr p-1">
                                <p class="uppercase"><span class="w-name bold">Merchant :</span> <span class="color2">{{ $show_data->companyName }}</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Name :</span> <span class="color2">{{ @$show_data->firstName }} {{ @$show_data->lastName }}</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Phone :</span> <span class="color2">{{ $show_data->phoneNumber }}</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Email :</span> <span class="color2">{{ @$show_data->emailAddress }}</span></p>
                                <p></p>
                            </div>
                        </div>
                        <div class="coll ml-1">
                            <p class="bold uppercase">Recipient INFORMATION</p>
                            <div class="borderr p-1">
                                <p class="uppercase"><span class="w-name bold">Name :</span> <span class="color2">{{ $show_data->recipientName }}</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Address :</span> <span class="color2">{{ $show_data->recipientAddress }}</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Area/State :</span> <span class="color2">{{ $show_data->zonename }} / {{ $show_data->title }}</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Phone :</span> <span class="color2">{{ $show_data->recipientPhone }}</span></p>
                                <!--<p class="uppercase mt-1"><span class="w-name">Email :</span> <span class="color2">{{ @$show_data->recipientEmail }}</span></p>-->
                            </div>
                        </div>
                    </div>
                    <div class="roww mt-2">
                        <div class="coll borderr1">
                            <table>
                                <tr>
                                    <th class="uppercase">Product Name</th>
                                    <th class="uppercase" width="50">Weight</th>
                                    <th class="uppercase" width="50">Colour</th>
                                    <th class="uppercase" width="40">QTY</th>
                                </tr>
                                <tr>
                                    <td>{{ @$show_data->productName }}</td>
                                    <td>{{ $show_data->productWeight }}</td>
                                    <td>{{ @$show_data->productColor }}</td>
                                    <td>{{ @$show_data->productQty }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="roww borderr" style="margin-top: -2px;">
                        <div class="coll-3 p-1 content-center">
                            <div class="">@php $cod = $show_data->cod > 0 ? $show_data->cod : "00.00" @endphp
                                <h1 class="uppercase text-red">Cash Collection {{ number_format($cod,2) }} N</h1>
                            </div>
                        </div>
                        <div class="coll borderr-left">
                            <p class="text-right uppercase bold d-flex p-05 flex-roww borderr-bottom"><span class=" w-50" style="font-size: 9px; font-weight: 700">Merchant Amount</span><span class="text-left pl-1 bold color2 w-50">{{ number_format($show_data->merchantAmount,2) }} N</span></p>
                            <p class="text-right uppercase bold d-flex p-05 flex-roww borderr-bottom"><span class=" w-50" style="font-size: 9px; font-weight: 700">Delivery Charge</span><span class="text-left pl-1 bold color2 w-50">{{ number_format(@$show_data->deliveryCharge,2) }} N</span></p>
                            <p class="text-right uppercase bold d-flex p-05 flex-roww borderr-bottom"><span class=" w-50" style="font-size: 9px; font-weight: 700">COD Charge</span><span class="text-left pl-1 bold color2 w-50">{{ number_format(@$show_data->codCharge,2) }} N</span></p>
                            @php $total = (int)$show_data->merchantAmount + (int) $show_data->deliveryCharge + (int)$show_data->codCharge @endphp
                            <p class="text-right uppercase bold d-flex p-05 flex-roww"><span class=" w-50" style="font-size: 9px; font-weight: 700">Total Merchant</span><span class="text-left pl-1 bold color2 w-50">{{ number_format($total, 2) }} N</span></p>
                        </div>
                    </div>
                    <div class="roww borderr mt-1 p-1 pb-2">
                        <div class="coll-4">
                            <h3 class="text-center">AWB Terms and condition</h3>
                            <ol class="mt-1 terms">
                                <li>
                                   Zidrop Logistics shall maintain at its expense , Free
                                    Goods-In-Transit Insurance for packages within its maximum
                                    liability cover of N20,000.00 per Air waybill. Where the value of
                                    clients package is above the aforementioned amount, 1% of the
                                    declared value, which serves as premium for insurance is to be
                                    paid by shipper (this is however optional, as client may have a prior
                                    insurance in place or decide against it).
                                </li>
                                <li>
                                Where Zidrop Logistics receives sealed shipments
                                contents of which are not ascertainable on the face of the
                                package, Zidrop Logistics shall not be liable for any
                                defects therein especially where the package is not visibly
                                defaced.
                                </li>
                                <li>
            Ensure that the value of each shipment is properly
            described on the Shipping invoice.
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
