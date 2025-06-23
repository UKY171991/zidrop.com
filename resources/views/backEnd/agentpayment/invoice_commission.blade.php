@extends('backEnd.layouts.master')
@section('title', 'Commission Invoice')
@section('extracss')
    <style>
        @page {
            size: auto;
            margin: 0mm;
        }

        @media print {

            header,
            footer {
                display: none !important;
            }
        }

        .invoice-box {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .table.table-bordered.parcel-invoice td {
            padding: 5px 5px;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

        p {
            margin: 0;
        }



        @media print {
            body * {
                visibility: hidden;
            }
            #full_invoice_area, #full_invoice_area * {
                visibility: visible;
            }
            #full_invoice_area {

            }
        }
    </style>
@endsection
@section('content')

    <div id="full_invoice_area">
        <div class="short_button">
            <button onclick="goBack()"
                style="color: #135d30;border: 0;padding: 6px 12px;margin-bottom: 8px !important;display: block;text-align: center;background: 0;border-radius: 5px;"><i
                    class="fa fa-arrow-circle-left"></i> Back</button>
        </div>


        <div style="padding-top: 50px"></div>
        <button onclick="myFunction()"
            style="color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px !important;display: block;margin: 0 auto;margin-bottom: 0px;text-align: center;
    background: #F32C01;
    border-radius: 5px;"><i
                class="fa fa-print"></i></button>
        <div class="invoice-box">
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td class="title">
                                    @foreach ($whitelogo as $logo)
                                        <img src="{{ asset($logo->image) }}" style="width:200%; max-width:200px;">
                                    @endforeach
                                </td>

                                <td>
                                    <p> Parcel-Serial #: {{$parcels->first()->id}}</p>

                                    <p> Time:  {{date('h:i:s a', strtotime($AgentCommission->first()->created_at))}}</p>

                                <p> Date : {{date('F d, Y', strtotime($AgentCommission->first()->created_at))}}</p>

                                <p>Agent Name : {{$AgentCommission->agent->name}}</p>
                                <p>Agent Phone : {{$AgentCommission->agent->phone}}</p>
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
                        <td>Tracking ID.</td>
                        <td> Name</td>
                        {{-- <td> Phone</td> --}}
                        <td>Total COD</td>
                        <td>Charge(N)</td>
                        <!--<td>Sub Total(BDT)</td>-->
                        <td>Agent Commission</td>
                    </tr>
                    @foreach ($parcels as $key => $value)
                        <tr class="item">
                            <td>{{ $value->trackingCode }}</td>
                            <td>{{ $value->recipientName }}</td>
                            {{-- <td>{{ $value->recipientPhone }}</td> --}}
                            <td>
                                <center>{{ number_format($value->cod, 2) }}</center>
                            </td>
                            <td>
                                <center>{{ number_format($value->deliveryCharge, 2) }}</center>
                            </td>
       
                            <td>
                                <center>{{number_format($value->agent_commission, 2)}} N</center>
                            </td>
                        </tr>

                    @endforeach
                    <tr class="heading">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total: {{ number_format($AgentCommission->agent_commission, 2) }} N</td>
                    </tr>
                </tbody>
            </table>

            <br>
            Total Agent Commission Payment Amount: {{ number_format($AgentCommission->agent_commission, 2) }} N
            <br>

            <!--Amount in Words----------->
            Total Agent Commission Payment Amount in Words:

                <form action="/action_page.php">
                    <textarea id="w3review" name="w3review" rows="1" style="width: 100%;"></textarea>
                </form>



            <!--Singnature------------>
            <br>
            <br>
            <br>
            <table style="width:138%">
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

@endsection
@section('custom_js_scripts')

    <script>
        function myFunction() {
            window.print();
        }
        function goBack() {
            window.history.back();
        }
    </script>
@endsection