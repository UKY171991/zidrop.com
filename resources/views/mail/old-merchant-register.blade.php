<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Merchant Register</title>
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
    <div class="header">
      <img
        src="{{ asset('logo.png') }}"
        alt="ZiDrop Logistics"
        style="width: 150px"
      />
    </div>
    <div class="mail-body" style="width: 90%; margin: 0 auto; max-width: 700px">
      <h1 style="font-size: 20px">
        Dear {{ $merchant->companyName }} (<a
          href="mailto:{{ $merchant->emailAddress }}"
          >{{ $merchant->emailAddress }}</a
        >),
      </h1>

      <p>It's a delight to on-board you on our e-Commerce platform - ZIDROP.</p>
      <p>Below are your login details:</p>
      <p><a href="{{ route('frontend.merchant.login') }}">Visit ZiDrop</a></p>
      <p><strong>Email:</strong> {{ $merchant->emailAddress }}</p>
      <p>
        <strong>Password:</strong> Use the password that you entered during
        registration.
      </p>
      <br />
      <br />

      <p><strong>THINGS TO NOTE:</strong></p>
      <p>
        You are required to create your new orders on the platform and generate
        your invoices (waybills) prior to drop-off or pickup. These can be done
        via the 'Create New Order' and ‘Print Waybill’ tabs.
      </p>
      <p>
        For pay-on-delivery orders, the total amount that will be requested from
        the customer is a sum of the delivery fee and the item cost.
      </p>
      <p>
        Prepaid orders can only be processed with a wallet balance. This can be
        topped up by making payment to the account below, and then sending a
        copy of the payment advice via email.
      </p>

      <p><strong>Account Name: </strong>Zidrop Logistics</p>
      <p><strong>Account Number: </strong>5600979340</p>
      <p><strong>Bank: </strong>Fidelity Bank</p>
      <br />
      <p>
        Further to having invoice generated for an order, you can either
        drop-off at any of our drop-off centers, or request for pickup. To
        request for pickup, simply login to your account and use the pickup
        request button. Kindly note that pick-up is based on queued requests,
        will be done within 48 hours and is a service readily available only in
        Lagos.
      </p>
    </div>

    <hr />
    <p
      class="footer-content"
      style="text-align: center; margin-top: 0; margin-bottom: 50px"
    >
      Zidrop Logistics. All rights reserved.
    </p>
  </body>
</html>
