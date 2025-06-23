<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Pickup Request</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .header {
            text-align: center;
            background-color: #f5f5f5;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: right;
        }
        th, td {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="{{ asset('logo.png') }}" alt="ZiDrop Logistics" style="width: 150px;">
</div>
<div class="mail-body" style="width: 90%;margin: 0 auto;max-width: 700px;">
    <h1 style="font-size: 20px;">
        Hi Onyekachi Umejiaku,
    </h1>

    <p>You have a new pickup request from {{ $merchant->companyName }} ({{ $merchant->emailAddress }})</p>
    <p style="line-height: 0.5;margin: 0;">&nbsp;</p>
    <table border="1">
        <tr>
            <th>
                Date
            </th>
            <th>
                :
            </th>
            <td>
                {{ $pickup->date }}
            </td>
        </tr>
        <tr>
            <th>
                Pickup Address
            </th>
            <th>
                :
            </th>
            <td>
                {{ $pickup->pickupAddress ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <th>
                Note
            </th>
            <th>
                :
            </th>
            <td>
                {{ $pickup->note ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <th>
                Estimated Parcel
            </th>
            <th>
                :
            </th>
            <td>
                {{ $pickup->estimedparcel ?? 'N/A' }}
            </td>
        </tr>

        <tr>
            <th>
                Merchant
            </th>
            <th>
                :
            </th>
            <td>
                <table border="1">
                    <tr>
                        <th>Company Name</th>
                        <th>:</th>
                        <td>
                            {{ $merchant->companyName }}
                        </td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>:</th>
                        <td>
                            {{ $merchant->firstName . ' ' . $merchant->lastName }}
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <th>:</th>
                        <td>
                            {{ $merchant->emailAddress }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>
                Requested At
            </th>
            <th>
                :
            </th>
            <td>
                {{ $pickup->created_at }}
            </td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center !important;">
                <a href="{{ route('editor.new.pickup') }}">View All New Pickups</a>
            </th>
        </tr>
    </table>
    <p style="line-height: 0.1;margin: 0;">&nbsp;</p>
    <p>If you have any questions about this, please
        <a href="{{ route('frontend.contact-us') }}">Contact With Us</a>
    </p>


    <p>Have a great day!</p>

    <p><strong>Zidrop Logistics</strong></p>
</div>
<hr>
<p class="footer-content" style="text-align: center;margin-top: 0;margin-bottom: 50px;">
    Zidrop Logistics. All rights reserved.
</p>



</body>
</html>