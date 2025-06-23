<div class="row">
    <div class="col-sm-7">
        <h6>Cash Collection</h6>
    </div>
    <div class="col-sm-5">
        <h6>{{ number_format(Session::get('codpay'), 2) }} N</h6>
    </div>
</div>
<!-- row end -->
<div class="row">
    <div class="col-sm-7">
        <h6>Delivery Charge</h6>
    </div>
    <div class="col-sm-5">
        <h6>{{ number_format(Session::get('pdeliverycharge'), 2) }} N</h6>
    </div>
</div>
<!-- row end -->
<div class="row">
    <div class="col-sm-7">
        <h6>Cod Charge</h6>
    </div>
    <div class="col-sm-5">
        <h6>{{ number_format(Session::get('pcodecharge'), 2) }} N</h6>
    </div>
</div>
<!-- row end -->
<div class="row">
    <div class="col-sm-7">
        <h6>Tax</h6>
    </div>
    <div class="col-sm-5">
        <h6>{{ number_format(Session::get('mtax'), 2) }} N</h6>
    </div>
</div>
<!-- row end -->
<div class="row">
    <div class="col-sm-7">
        <h6>Insurance</h6>
    </div>
    <div class="col-sm-5">
        <h6>{{ number_format(Session::get('minsurance'), 2) }} N</h6>
    </div>
</div>
<!-- row end -->
<div class="row total-bar">
    <div class="col-sm-7">
        <h6>Total Payable Amount</h6>
    </div>
    <div class="col-sm-5">
        <h6>{{ number_format(Session::get('codpay') - (Session::get('pdeliverycharge') + Session::get('pcodecharge') + Session::get('mtax') + Session::get('minsurance')), 2) }}
            N</h6>
    </div>
</div>
<!-- row end -->
<div class="row">
    <div class="col-sm-12">
        <p class="text-center">Note : <span class="">If you request for pick up after 5pm , it will be picked up
                the next day</span></p>
    </div>
</div>
<!-- row end -->
