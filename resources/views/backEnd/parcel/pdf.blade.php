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
            font-family: 'Noto Sans', sans-serif;
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
        .header-text {
            font-size: 15px !important;
        }

        .medium-text {
            font-size: 15px !important;
            padding: 1px;
        }
       
    </style>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: poppins;
        }

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

        page[size="A4"][layout="landscape"] {
            width: 29.7cm;
            height: 21cm;
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
        .top-border-none{
            border-top:  0px solid !important;
        }
        .bottom-border-none{
            border-bottom: 0px solid !important;
        }
        .left-border-none{
            border-left:  none !important; 
        }
        .right-border-none{
            border-right:  none !important;
        }
    </style>
</head>

<body>
    @php $marchentAmount = (($show_data->deliveryCharge + $show_data->codCharge + $show_data->tax + $show_data->insurance) - $show_data->cod) * (-1);  @endphp
    <table class="no-border">
        <tr class="no-border">
            <td class="no-border">
                <div class="">
                    @foreach ($whitelogo as $key => $logo)
                        <img src="{{ asset($logo->image) }}" style="width: 100px">
                    @endforeach
                    <h4 class="" style="font-size:18px; font-weight: 700; text-decoration:underline">SHIPMENT
                        DELIVERY WAYBILL
                    </h4>
                </div>

            </td>
            <td class="no-border text-right">
                <div class="qrcode">
                    <?php  echo $Qrcode2; ?>
                    <?php // echo DNS1D::getBarcodeSVG($show_data->trackingCode, 'C128', 2, 50, 'black', false); ?>
                </div>
            </td>
        </tr>
        <tr>
            <td style="margin: -5px">
                <p class="">
                    <h4 style="font-size:14px; font-weight: 500">Waybill Number :  <span class="color2 bold">{{ $show_data->trackingCode }}</span> </h4>
                   
                </p>
            </td>
            <td>
                <p class="text-right ">
                    <h4 style="font-size:14px; font-weight: 500">Date :  <span class="color2 bold">{{ date('M-d-Y', strtotime($show_data->created_at)) }}</span></h4>
                   
                </p>
            </td>
        </tr>
    </table>
    <table class="no-border" style=" width: 100%; margin-top:10px">
        <tr class="no-border">
            <th class="no-border" style=" width: 50%"> <p class=" header-text" style="font-weight: 700">SENDER INFORMATION </p></th>
            <th class="no-border" style=" width: 50%"><p class="header-text" style="font-weight: 700">Recipient INFORMATION</p></th>
        </tr>
    </table>
    <table class="no-border" style="border-collapse: separate;  width: 100%;">
        
        <tr class="no-border" style="font-size: 14px ;" >
            <td class="" style="margin-right: 20px width: 49%">
                    <p class="" style="font-size: 14px"><span class="w-name">Merchant :</span> <span
                            class="color2">{{ $show_data->merchant->companyName }}</span></p>
                    <p class=" " style="min-height: 35px"><span class="w-name ">Name :</span> <span
                            class="color2">{{ @$show_data->merchant->firstName }} {{ @$show_data->lastName }}</span>
                    </p>
                    <p class=" " style="font-size: 14px"><span class="w-name">Phone :</span> <span
                            class="color2">{{ $show_data->merchant->phoneNumber }}</span></p>
                    <p class=" " style="font-size: 14px"><span class="w-name">Email :</span> <span
                            class="color2">{{ @$show_data->merchant->emailAddress }}</span></p>
                    <p></p>
            </td>
            <td class="no-border" style="width: 2%"></td>
            <td class="" style="margin-left: 20px;  width: 49%">
            
                    <p class="" style="font-size: 14px"><span class="w-name">Name :</span> <span
                            class="color2">{{ $show_data->recipientName }}</span></p>
                    <p class=" " style="min-height: 35px"><span class="w-name">Address :</span>
                        <span class="color2" style="text-indent: 10%">
                            {{ $show_data->recipientAddress }}</span>
                    </p>
                    <p class=" " style="font-size: 14px"><span class="w-name">Delivery City/Town :</span> <span
                            class="color2">{{ $show_data->deliverycity->title }} / {{ $show_data->deliverytown->title }}</span></p>
                    <p class="" style="font-size: 14px"><span class="w-name">Phone :</span> <span
                            class="color2">{{ $show_data->recipientPhone }}</span></p>
                    <!--<p class="uppercase mt-1"><span class="w-name">Email :</span> <span class="color2">{{ @$show_data->recipientEmail }}</span></p>-->
            </td>
        </tr>
    </table>
    <table class="" style="margin-top: 5px">
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
    @php $cod = $show_data->cod > 0 ? $show_data->cod : "00.00" @endphp
    @php $total = (float)$marchentAmount + (float) $show_data->deliveryCharge + (float)$show_data->codCharge + (float)$show_data->tax  + (float)$show_data->insurance   @endphp
   
    <table class="no-border" style="margin-top: 0px; width:100%">
        <tr class=" top-border-none right-border-none" style="border-top: 0px solid">
            <td class="no-border" style="width:20%">
                <span style="margin-left:20px">
                     <?php  echo $Qrcode4; ?>
                     <?php // echo DNS1D::getBarcodeSVG($show_data->trackingCode, 'C128', 1.4, 50, 'black', false); ?>
                </span>
            </td>
            <td class="no-border" style="width:50%">
                <span>
                    <h1 class="uppercase text-red" style="margin-top:-50px">Cash Collection
                        {{ number_format($cod, 2) }} N
                    </h1>
                </span>
            </td>
            <td class="no-border"  style="padding: 0px; width:30%">
                <table class="no-border" >
                    <tr class="top-border-none">
                        <th class="no-border" style=""> Merchant Amount</th>
                        <td class="no-border bold color2 text-right" style=""> {{ number_format($marchentAmount, 2) }} N</td>
                    </tr>
                    <tr class="">
                        <th class="no-border">Delivery Charge</th>
                        <td class="no-border bold color2 text-right"> {{ number_format($show_data->deliveryCharge, 2) }} N</td>
                    </tr>
                    <tr class="">
                        <th class="no-border">COD Charge</th>
                        <td class="no-border bold color2 text-right"> {{ number_format($show_data->codCharge, 2) }} N</td>
                    </tr>
                    <tr>
                        <th class="no-border"> Tax</th>
                        <td class="no-border bold color2 text-right"> {{ number_format($show_data->tax, 2) }} N</td>
                    </tr>
                    <tr>
                        <th class="no-border"> Insurance</th>
                        <td class="no-border bold color2 text-right"> {{ number_format($show_data->insurance, 2) }} N</td>
                    </tr>
                    <tr class="bottom-border-none">
                        <th class="no-border"> Total Amount</th>
                        <td class="no-border bold color2 text-right"> {{ number_format($total, 2) }} N</td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>

    <table style="margin-top: 10px">
        <tr class="no-border" style="text-align: center; margin:auto;">
            <td class="no-border" style="text-align: center; margin:auto;">
                <h4 style="text-align: center; margin:auto;">AWB Terms and condition</h4>
            </td>
        </tr>
        <tr class="no-border" style=" margin:auto;">
            <td class="no-border" style="text-align: left; margin:auto;">
                <ol style="font-size: 12px; text-align: left;">
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
            </td>
        </tr>
    </table>
</body>

</html>
