<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Balance Subtract</title>
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
            /*width: 100%;*/
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
        Hi {{ $merchant->companyName }},
    </h1>

    <p>
        Your ZiDrop account has been charged(debited) with <span style="font-weight: bold;color: blue;">N{{ $topup->amount }}</span> by Admin.
    </p>
    <p style="line-height: 0.5;margin: 0;">&nbsp;</p>
    <p style="margin-top: 0;margin-bottom: 15px;">
        <strong>Reference: </strong>
        {{ $topup->reference ?? 'N/A' }}
    </p>

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