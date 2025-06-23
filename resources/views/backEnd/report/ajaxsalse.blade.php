<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            font-size: 12px;
            font-weight: 500;
            box-sizing: border-box;
            color: black;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background: rgb(233, 233, 233);
        }

        .containerr {
            width: 800px;
            margin: 0 auto;
            min-height: 900px;
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

        .px-3 {
            padding-left: 30px;
            padding-right: 30px;
        }

        .pb-4 {
            padding-bottom: 40px;
        }

        .p-0 {
            padding: 0px;
        }

        .mt-1 {
            margin-top: 10px;
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
            min-width: 100px;
            display: inline-block;
            font-weight: 800;
            font-size: 11px;
            font-style: italic;
            text-transform: capitalize;
        }

        table:first-child {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid rgb(79, 78, 78);
            padding: 4px 0;
            padding-left: 10px;
            text-align: left;
            font-weight: 800;
            font-size: 10px;
        }

        table.table2 {
            display: table;
            border-collapse: collapse;
            border-spacing: 0px 10px;
        }

        table.table2 td {
            font-size: 11px;
            padding: 4px;
            font-weight: 500;
            border: 1px solid rgb(79, 78, 78);
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

        .company {
            font-size: 23px;
            border-bottom: 2px dotted #909090;
            margin-bottom: 10px;
        }

        .border-bottom {
            border-bottom: 2px dotted #898989;
        }

        .footer_amount {
            display: inline-block;
            width: 132px;
            border-bottom: 1px dotted #414141;
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
            .SearchSection {
                display: none !important;
            }
            body::before {
                content:  "{{ $head }}";
                display: block;
                text-align: center;
                margin-bottom: 10px;
                font-size: 28px;
                font-weight: bold;
            }
        }
        tr{
            border: none !important;
        }
        
    </style>
</head>

<body><div id="full_invoice_area">
    {{-- <div class="short_button">
        <button onclick="goBack()"
                style="color: #135d30;border: 0;padding: 6px 12px;margin-bottom: 8px !important;display: block;text-align: center;background: 0;border-radius: 5px;"><i
                    class="fa fa-arrow-circle-left"></i> Back</button>
    </div>  --}}
  {{-- <div style="padding-top: 50px"></div> --}}
  <button onclick="myFunction()" style="color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px !important;display: block;margin: 0 auto;margin-bottom: 0px;text-align: center;
background: #F32C01;
cursor: pointer;
border-radius: 5px;" class="SearchSection"><i class="fa fa-print"></i></button>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0" style="width:100%;">
            <tr class="top" style="border: none;">
                <td colspan="2" style="border: none;">
                    <table style="border: none; width:100%;">
                        <tr style="border: none;">
                            <td class="title">
                                @foreach ($whitelogo as $logo)
                                    <img src="{{ asset($logo->image) }}" style="width:200%; max-width:200px;">
                                @endforeach
                            </td>

                            <td>
                                <p class="uppercase"><span class="w-name bold">Account No. </span><span class="mr-1 bold">:</span>
                                    <span>5600979340 </span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Account Name </span><span
                                        class="mr-1 bold">:</span> <span>Zidrop Logistics</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Bank Name </span><span
                                        class="mr-1 bold">:</span> <span>Fidelity Bank Nigeria</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Tax No </span><span class="mr-1 bold">:</span>
                                    <span>xxxxxxxxxx</span></p>
                                <p class="uppercase mt-1"><span class="w-name bold">Date Range </span><span class="mr-1 bold">:</span>
                                    <span>{{ date('d-m-Y', strtotime($start_date)) }} to {{ date('d-m-Y', strtotime($end_date)) }}</span></p>
                                    
                                <p class="uppercase mt-1"><span class="w-name bold">Total </span><span class="mr-1 bold">:</span>
                                    <?php
                                    $total = 0;
                                    foreach ($reports as $parcel) {
                                        $total += $parcel->Charge;
                                    }
                                    ?>
                                    <span>{{ number_format($total, 2) }}</span></p>
                                <p></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table table-bordered parcel-invoice">
          <tbody>
            <tr class="heading">
                <td>Tracking ID</td>
                <td style="text-align:left"> Name</td>
                {{-- <td> Phone</td> --}}
                <td>Total</td>
                <td>Charge</td>
                <td>Cod Charge</td>
                <td>Tax</td>
                <td>Insurance</td>
                {{-- <td>Sub Total</td> --}}
                <td>Due to Merchant</td>
            </tr>
            @foreach($reports as $key=>$value)
           
            <tr>
                <td>{{$value->trackingCode}}</td>
                <td style="text-align:left">{{$value->recipientName}}</td>
                {{-- <td>{{$value->recipientPhone}}</td> --}}
                <td> {{ number_format($value->cod, 2) }}N</td>
                {{-- <td> {{ $value->cod - $value->merchantPaid }}N</td> --}}
                <td> {{ number_format($value->deliveryCharge, 2) }}N</td>
                <td> {{ number_format($value->codCharge, 2) }}N</td>
                <td> {{ number_format($value->tax, 2) }}N</td>
                <td> {{ number_format($value->insurance, 2) }}N</td>
                {{-- <td> {{ $value->merchantPaid }}N </td> --}}
                <td>{{ number_format($value->merchantPaid, 2) }}N</td>
            </tr>

           
            @endforeach

            <tr class="heading">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total</td>
                <td>{{number_format($total, 2)}} N</td>
            </tr>
          </tbody>
        </table>


        Total Amount: {{ number_format($total, 2) }} N
        <br>

        <!--Amount in Words----------->
        Total  Amount in Words:

        <form action="/action_page.php">
            <textarea id="w3review" name="w3review" rows="1" style="width: 100%;"></textarea>
        </form>


        <!--Singnature------------>
        <br>
        <br>
        <br>
        <table style="width:100%">
            <tr>
                <th><u>Accounts Officer Signature</u></th>
                <th></th>
                <th><u>Merchant Signature</u></th>
            </tr>
        </table>
        <br>
        <br>

    </div>
</div> 
   

   
<script>
    function myFunction() {
        window.print();
    }
    function goBack() {
        window.history.back();
    }
</script>
</body>

</html>
