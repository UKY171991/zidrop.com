<style>
 
    @media print {
        @page {
            size: landscape;
        }
    }

    @page {
        size: A4 landscape;
        margin: 20mm;
    }

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

    table td {
        font-size: 9px;
        padding: 2px;
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
        border: 1px solid rgb(61, 171, 226) !important;
        border-collapse: collapse;
    }
    main{
        margin: 50px;
    }
    
</style>

<page size="A4" layout="landscape" id="printview">
    <header class="header">
        <div class="row">
            <!-- <div class="col-md-2 text-7" style="margin-top: 60px">
                <p>Print time: 25-06-2023 01:23:44 PM</p>
                <p>Printed by: INFORMAX</p>
            </div> -->
            <div class="col-md-1 text-center" style="margin-top: 10px">

            </div>


        </div>
    </header>

    <main>

        <table id="xl-download">
            
            <thead>
                @php
                    $i = 1;

                @endphp
                <tr>

                    <th colspan="5" class="text-center"
                        style="border: 0.5px solid #ffffff!important;font-size: 20px;vertical-align: top; color: #2E3191">
                        Zidrop Logistics
                    </th>

                </tr>
                <tr>
                    <th colspan="5" class="text-center"
                        style="border: 0.5px solid #ffffff!important;font-size: 10px;">
                       address
                    </th>

                </tr>
                <tr>
                    <th colspan="5" class="text-center"
                        style="border: 0.5px solid #ffffff!important;font-size: 14px;">
                        Document wise Sales Statement for the period of<br />
                        {{ date('d-m-Y', strtotime($start_date)) }} to {{ date('d-m-Y', strtotime($end_date)) }}
                        <div class="p-1">
                            <p class="uppercase"><span class="w-name bold">Account No. </span><span class="mr-1 bold">:</span>
                                <span>5600979340 </span></p>
                            <p class="uppercase mt-1"><span class="w-name bold">Account Name </span><span
                                    class="mr-1 bold">:</span> <span>Zidrop Logistics</span></p>
                            <p class="uppercase mt-1"><span class="w-name bold">Bank Name </span><span
                                    class="mr-1 bold">:</span> <span>Fidelity Bank Nigeria</span></p>
                            <p class="uppercase mt-1"><span class="w-name bold">Tax No </span><span class="mr-1 bold">:</span>
                                <span>xxxxxxxxxx</span></p>
                        </div>
                    </th>

                </tr>
                <tr>
                    <th colspan="5">
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="true" aria-haspopup="true">
                                Download <span class="fa fa-ellipsis-v"></span>
                            </button>
                            <ul class="dropdown-menu px-2 py-2" style="min-width: 3rem;"
                                aria-labelledby="dropdownMenuButton1">
                                <li class="m-2">
                                    <a href="#" class="pdf-download btn btn-info btn-sm">PDF<i
                                            class="pdf-download bx bx-download"></i></a>
                                </li>
                                <li class="m-2">
                                    <a href="#" id="btnExport" class="btn btn-info btn-sm">XL<i
                                            class="xl-download bx bx-download"></i></a>
                                </li>
                            </ul>
                        </div>
                    </th>
                </tr>
                <tr class="border-add-head">
                    <th class="uppercase text-center" width="20%">Tracking ID</th>
                    <th class="uppercase text-center" width="20%">Item</th>
                    <th class="uppercase text-center" width="20%">Date Created</th>
                    <th class="uppercase text-center" width="20%">Address/Area/State</th>
                    <th class="uppercase text-center" width="20%">Delivery Cost</th>

                </tr>
            </thead>
            <tbody>

                @foreach ($reports as $key2 => $parcel)
                    <tr class="border-add">
                        <td width="20%">{{ $parcel->trackingCode }}</td>
                        <td width="20%" class="text-center">{{ $parcel->productName }}
                            <small><strong>({{ @$parcel->productQty }})</strong></small>
                        </td>
                        <td width="20%" class="text-center">{{ $parcel->created_at }}</td>

                        <td>{{ $parcel->recipientAddress }} /
                            {{ App\Nearestzone::find($parcel->reciveZone)->zonename }} /
                            {{ App\Deliverycharge::find($parcel->orderType)->title }}</td>
                        <td width="20%" class="text-right">{{ $parcel->deliveryCharge }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <br />


    </main>
</page>
