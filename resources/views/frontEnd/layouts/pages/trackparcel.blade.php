@extends('frontEnd.layouts.master')
@section('title','Track Parcel')
@section('content')

<!-- <link href="{{ asset('frontEnd') }}/css/fontawesome-all.min.css" rel="stylesheet"> -->
<!--/ End Tracking -->
    <div class =" container" style="display: none;">
            <div class="col-sm-12 ">
                <div class="row addpercel-inner">
                    <div class="col-md-5 my-5">
                        <div class="track-left">
                            <!--<h4>Track Parcel</h4>-->
                            @foreach($trackInfos as $trackInfo)
							<div class="tracking-step">
								<div class="tracking-step-left">
									<strong>{{date('h:i A', strtotime($trackInfo->created_at))}}</strong>
									<p>{{date('M d, Y', strtotime($trackInfo->created_at))}}</p>
								</div>
								<div class="tracking-step-right"  style = "margin-top:19px;">
									<b>{{$trackInfo->note}}<b>
								</div>
							</div>
							@endforeach
                        </div>
                    </div>
                    <div class="col-md-7  my-2">
                        <div class="track-right">
                            <h4>Customer and Parcel Details</h4>
                            <div class="item">
                                <p>Parcel ID</p>
                                <h6><strong>{{$trackparcel->trackingCode}}</strong></h6>
                            </div>
                            <div class="item">
                                <p>Customer Name :</p>
                                <h6><strong>{{$trackparcel->recipientName}}</strong></h6>
                            </div>
                            <div class="item">
                                <p>Customer Address :</p>
                                <h6><strong>{{$trackparcel->recipientAddress}}</strong></h6>
                            </div>
                            <div class="item">
								<p>Pickup City/Town :</p>
								<h6><strong>{{$trackparcel->pickupcity->title}} / {{$trackparcel->pickuptown->title}}</strong></h6>
							</div>
                            <div class="item">
								<p>Delivery City/Town :</p>
								<h6><strong>{{$trackparcel->deliverycity->title}} / {{$trackparcel->deliverytown->title}}</strong></h6>
							</div>
                            @if(!empty($trackparcel->deliverymanId))
                            
                            @php
                                $deliverymanInfo = App\Deliveryman::find($trackparcel->deliverymanId);
                            @endphp
                            <div class="item">
                                <p>Rider Name :</p>
                                <h6><strong>{{$deliverymanInfo->name}}</strong></h6>
                            </div>
                            <div class="item">
                                <p>Rider Phone :</p>
                                <h6><strong>{{$deliverymanInfo->phone}}</strong></h6>
                            </div>
                            @endif
                            <div class="item">
                                <p>Last Update :</p>
                                <h6>{{$trackparcel->updated_at}}</h6>
                            </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
</section> 
@endsection





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>

$('.container').hide(); 

$(window).on('load', function () {
    $('.container').show('slow'); 
    let maxDepth = 20; // prevent infinite loops
    let count = 0;

    while (count < maxDepth) {
        let removedAny = false;

        $('.addpercel-inner b').each(function () {
            const $b = $(this);
            const children = $b.children();

            // 1. <b><b>...</b></b>
            if (children.length === 1 && children.prop('tagName') === 'B') {
                $b.contents().unwrap();
                removedAny = true;
                return;
            }

            // 2. <b><div></div></b> and no text
            const isOnlyBlocks = children.length > 0 && $b.text().trim() === '' &&
                [...children].every(el =>
                    ['DIV', 'SECTION', 'P', 'H4', 'UL', 'OL', 'LI', 'SPAN'].includes(el.tagName)
                );
            if (isOnlyBlocks) {
                $b.contents().unwrap();
                removedAny = true;
                return;
            }

            // 3. empty <b></b>
            if ($b.text().trim() === '' && children.length === 0) {
                $b.remove();
                removedAny = true;
                return;
            }

            // 4. fallback: unwrap <b> in general under .addpercel-inner
            $b.contents().unwrap();
            removedAny = true;
        });

        if (!removedAny) break;
        count++; 
    }

    // After cleaning, now get the cleaned HTML
    let cleanedHtml = $('.addpercel-inner').html();

    // Example: Now you can load or use this cleaned HTML
    console.log(cleanedHtml); // view it in console
    // or inject it somewhere
    // $('#some-container').html(cleanedHtml);
});
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const header = document.querySelector('.sticky-header');

    window.addEventListener('scroll', function () {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const documentHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = (scrollTop / documentHeight) * 100;

        if (scrollPercent > 10) {
            header.style.position = 'fixed';
            header.style.top = '0';
            header.style.width = '100%';
            header.style.zIndex = '100'; // Make sure stays on top
        } else {
            header.style.position = 'sticky';
            header.style.top = '0';
            header.style.width = '100%';
        }
    });
});
</script>



