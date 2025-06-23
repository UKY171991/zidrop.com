@extends('frontEnd.layouts.pages.agent.agentmaster')

@section('title', 'Commission History')


@section('content')
    <style>
        @media screen and (max-width: 767px) {
            li.paginate_button.previous {
                display: inline !important;
            }

            li.paginate_button.next {
                display: inline !important;
            }

            li.paginate_button {
                display: none !important;
            }

            .custom-card {
                margin-top: 25px !important;
            }

            .main-body,
            .container-fluid {
                padding: 0px !important;
                margin: 0px !important;
            }

            .box-content {
                padding: 0px !important;
                margin: 0px !important;
                margin-top: 40px !important;
            }

            .col-sm-12 {
                padding: 10px !important;
                margin: 8px !important;
            }

        }

        @media screen {
            #printSection {
                display: none;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printSection,
            #printSection * {
                visibility: visible !important;
            }

            #printSection {
                position: absolute !important;
                left: 0;
                top: 0;
            }

        }

        .col-sm-2 {
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        .select2-container .select2-search--inline .select2-search__field {
            margin-top: -7px !important;
            padding: 5px 5px !important;
            padding-top: 10px !important;

        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ddd !important
        }
    </style>
    <div class="container-fluid">
        <div class="box-content">
            <div class="row">
                {{-- Show all errors with cross --}}
                @if ($errors->any())
                    <div class="col-sm-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Errors</strong>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <button type="button" class="close btn btn-danger" data-dismiss="alert" aria-label="Close">
                                <i class="fas fa-cross"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card">
                        <div class="row">
                            <div class="col-md-6 mt-2 mr-auto text-left">
                                <div class="body-title pl-2">
                                    <h5 class="pageStatusTitle"> Commission History</h5>

                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <table id="agentcompaymenttable" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Agent Name</th>
                                        <th>Total Commission</th>
                                        <th>Date And Time</th>                                           
                                        <th>Total Invoice</th>                                           
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>

    @endsection
    @section('custom_js_scripts')

        <script>
            $(document).ready(function(event) {

                var table33 = $('#agentcompaymenttable').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    sorting: false,
                   
                    ajax: {
                        url: "{{ route('agent.payment.get_commission_history', $agentId) }}",
                    },
                   
                });


            });
        </script>


    @endsection
