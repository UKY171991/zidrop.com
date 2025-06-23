<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Merchant Reset Password</title>
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
    </style>
</head>
<body>
<!--<div class="header">
    <p style="margin: 0;font-size: 22px;font-weight: 600;">Zidrop Logistics</p>
</div>-->
<div class="header">
    <img src="{{ asset('logo.png') }}" alt="ZiDrop Logistics" style="width: 150px;">
</div>
<div class="mail-body" style="width: 90%;margin: 0 auto;max-width: 700px;">
    <h1 style="font-size: 20px;">
        Hi{{ isset($merchant) && isset($merchant->companyName) ? ' ' . $merchant->companyName : '' }}{{ isset($merchant) && isset($merchant->emailAddress) ? ' (<a href=\"mailto:' . $merchant->emailAddress . '\">' . $merchant->emailAddress . '</a>)' : '' }},
    </h1>

    <p>You have requested to reset your password in Zidrop</p>
    <p>Your password reset token is <span style="background-color: #f2f2f2;display: inline-block;padding: 9px 20px;font-size: 20px;border-radius: 12px;font-weight: 600;">{{ isset($merchant) && isset($merchant->passwordReset) ? $merchant->passwordReset : (isset($otp) ? $otp : '') }}</span></p>
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