@extends('frontEnd.layouts.master')
@section('title', 'Branches')
@section('content')
    <style>
        .single-portfolio-card {
            background-color: #F1F3F2;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 20px;
            position: relative;
            border: 1px solid #040404;
        }

        .single-portfolio-card img {
            border-radius: 50%;
            height: 200px;
            width: 200px;
        }

        .single-portfolio-card img:hover {
            /* zoom in */
            transform: scale(1.2);

        }

        .quickTech-price {
            background: #E6EDF3;
        }

        .card_row {
            /* padding-left: 40px;
                padding-right: 40px; */
        }

        .separator {
            border-top: 5px solid #50b9b9;
            margin: 20px 0;
        }

        .red {
            color: #db0022;

        }

        .portfolio-content h5 a {
            color: #000;
            font-size: 18px;
            font-weight: 400;
        }

        .portfolio-content h5 a:hover {
            color: #50b9b9;
        }

        .single-portfolio-card .portfolio-content h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .single-portfolio-card .portfolio-page-card:hover {
            /* zoom in the image */
            transform: scale(1.2);
        }

        .single-portfolio-card.portfolio-page-card:hover .portfolio-content {
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }

        .single-portfolio-card:hover .portfolio-img img {
            -webkit-transform: scale(1.2);
            transform: scale(1.2);
        }

        .bankButton {
            background-color: #09A5DB !important;
        }
        .modal-content {
            padding: 0px;
        }
    </style>
    <!-- Breadcrumb -->
    <div class="breadcrumbs" style="background:#db0022;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <!-- Bread Menu -->
                        <div class="bread-menu">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="">Hub</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / End Breadcrumb -->

    <!-- quickTech-price -->
    <section class="quickTech-price pt-5 pb-5" style="margin-bottom: -80px">
        <div class="container-fluid">
            {{-- <div class="row">
                    <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                        <div class="section-title default text-center">
                            <div class="section-top">
                                <h1>Branches</h1>
                            </div>
                        </div>
                    </div>
                </div> --}}
            <div class=" card_row row">
                @foreach ($branches as $value)
                    <div class="col-md-3">
                        <div class="single-portfolio-card portfolio-page-card" style="background-color: #fcfcfc">
                            <div class="portfolio-img text-center py-3">
                                @if ($value->key_person_image)
                                    <img src="{{ asset('/' . $value->key_person_image) }}" alt="portfolio1" width="50%">
                                @else
                                    <img src="{{ asset('/') }}branch.jpg" alt="portfolio1" width="50%">
                                @endif
                            </div>
                            <div class="portfolio-content">
                                <h4 class="text-center"><a href="#"> {{ $value->title }}</a></h4>
                                <h5 class="text-center"><a href="#"> {{ $value->key_person }}</a></h5>
                                <hr class="separator">
                                <div class="text-left line_space_decerese">
                                    <p><b>Address:</b> {{ $value->address }}</p>
                                    <p><b>City/Town:</b> {{ $value->city->title }} / {{ $value->town->title }}</p>
                                    <p><b>Phone:</b> {{ $value->phone }}</p>
                                    <p><b>E-mail:</b> <a href="mailto:{{ $value->email }}"> <span
                                                class="red">{{ $value->email }}</span></a></p>
                                </div>
                                <div class="text-center">
                                    <button id="" class="badge bg-success text-white BankPosterBTN bankButton"
                                        data-poster="{{ $value->bank_poster }}" style="">Payment Terminal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="pagination-area">
                    {{ $branches->links() }}
                </div>
            </div>
        </div>
       
       <!-- Modal -->
       <!-- Modal -->
<div class="modal fade" id="PaymentPosterModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="margin-top: 100px">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="" id="PosterImageA" download>
                    <img id="PosterImage" src="" alt="" style="max-width: 100%; height: auto;">
                </a>
            </div>
        </div>
    </div>
</div>




    </section>
@endsection
@section('custom_js_script')
    <script>
       $(document).ready(function() {
    // Event listener for BankPosterBTN click
    $(document.body).on('click', '.BankPosterBTN', function(e) {
        e.preventDefault();

        // Get the poster image path from data attribute
        var posterPath = $(this).data('poster');

        if (posterPath) {
            // Set the href and src attributes for the anchor and image
            $('#PosterImageA').attr('href', posterPath);
            $('#PosterImage').attr('src', posterPath);

            // Show the Bootstrap modal
            $('#PaymentPosterModal').modal('show');
        } else {
            alert('No poster available');
        }
    });
});

    </script>
@endsection
