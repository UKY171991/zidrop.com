<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $head }}</title>
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
            border-collapse: separate;
            border-spacing: 0px 10px;
        }

        table.table2 td {
            font-size: 11px;
            padding: 4px;
            font-weight: 500;
            border: 1px solid rgb(79, 78, 78);
        }

        table.table2 td:not(:last-child) {
            border-right: unset;
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
            /* Set space for the footer */
            body::before {
                content: "";
                display: block;
                height: 50px; /* Adjust the height according to your preference */
            }
            body::before {
                content: "";
                display: block;
                text-align: center;
                margin-bottom: 10px;
                font-size: 28px;
                font-weight: bold;
            }
        }

        tr {
            border: none !important;
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

    <div class="containerr px-3 pb-4">
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
                </div>
            </div>
        </div>
        <div class="roww mt-2">
            <div class="coll mr-1 border-bottom">
                <p class="bold uppercase company underline_dotted">{{ $head }}</p>
                <div class="">

                    <p class="uppercase mt-1"><span class="w-name bold">Date Range </span> <span
                            class="bold mr-1">:</span>{{ date('d-m-Y', strtotime($start_date)) }} to
                        {{ date('d-m-Y', strtotime($end_date)) }}</p>
                    <p class="uppercase mt-1"><span class="w-name bold">Total Walet Amount </span> <span
                            class="bold mr-1">:</span>
                        <?php
                        $total = 0;
                        foreach ($topup as $value) {
                            $total += $value->amount;
                        }
                        ?>
                        <span>{{ number_format($total, 2) }} N</span>
                    </p>
                    <p></p>
                </div>
            </div>
            <div class="coll ml-1 border-bottom pb-2 pt-2">
                <p class="bold uppercase company" style="margin-top: 28px;"></p>
                <div class="p-1">
                    {{-- <p class="uppercase"><span class="w-name bold">Account No. </span><span class="mr-1 bold">:</span> <span>5600979340 </span></p>
                        <p class="uppercase mt-1"><span class="w-name bold">Account Name </span><span class="mr-1 bold">:</span> <span>Zidrop Logistics</span></p>
                        <p class="uppercase mt-1"><span class="w-name bold">Bank Name </span><span class="mr-1 bold">:</span> <span>Fidelity Bank Nigeria</span></p>
                        <p class="uppercase mt-1"><span class="w-name bold">Tax No </span><span class="mr-1 bold">:</span> <span>xxxxxxxxxx</span></p> --}}
                </div>
            </div>
        </div>
        <div class="roww mt-2" style="">
            <div class="coll">
                <table>
                  
                </table>
            </div>
        </div>
        <div class="row">
            <div class="coll">
                <table class="table2">
                    <tr>
                        <th class="uppercase text-center" width="">SN</th>
                        <th class="uppercase text-center" width="">Merchant Name</th>
                        <th class="uppercase text-center" width="">Date Created</th>
                        <th class="uppercase text-center" width="">Transaction Reference</th>
                        <th class="uppercase text-center" width="">Transaction Channel</th>
                        <th class="uppercase text-center" width="">Amount</th>
                    </tr>
                    @foreach ($topup as $key => $value)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td width="">{{ $value->merchant->companyName }}</td>
                            <td width="" class="text-center">{{ $value->created_at }}</td>
                            <td>{{ $value->reference }}</td>
                            <td>{{ $value->channel }}</td>
                            <td width="" class="text-right">{{ number_format($value->amount, 2) }} N</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="roww mt-2">
            <div class="coll mr-1">
            </div>
            <div class="coll">
            </div>
            <div class="coll ml-1 pb-2">
                <div class="p-1">
                    <p class="uppercase"><span class="w-name bold" style="min-width: 75px">Total Walet Amount </span><span
                            class="mr-1 bold">:</span> <span
                            class="text-right footer_amount bold">{{ number_format($total, 2) }} N</span></p>
                </div>
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
