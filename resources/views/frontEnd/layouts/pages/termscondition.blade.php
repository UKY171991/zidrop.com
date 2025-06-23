@extends('frontEnd.layouts.master')
@section('title', 'Terms & Conditions')
@section('content')

    <!--Quicktech Carrier Section Start -->
    <section id="quickTech-carrier" class="section-padding bg-gray">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Terms & Conditions</h2>
                <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <object data="{{ asset('frontEnd/ZIDROP-LOGISTICS-E-COMMERCE-SERVICE-AGREEMENT-FORM.pdf') }}"
                        style="width: 100%;height:100vh;">
                    </object>
                </div>
            </div>
    </section>
@endsection
