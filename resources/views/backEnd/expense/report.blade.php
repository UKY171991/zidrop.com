@extends('backEnd.layouts.master')
@section('title', 'Sales Report')
@section('extracss')

    <style>
        .btn-primary,
        .trash_icon {
            padding: 0.2rem 0.5rem !important;
            border-radius: 0.25rem !important;
        }

        .inline {
            display: inline-block;
        }

        .form-label {
            text-transform: capitalize;
            font-weight: 500 !important;
        }

        .py-1 {
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }

        .text-denger {
            color: red;
        }

        .btn-group>.btn-group:not(:last-child)>.btn,
        .btn-group>.btn.dropdown-toggle-split:first-child,
        .btn-group>.btn:not(:last-child):not(.dropdown-toggle) {
            background-color: #1a7cbc;
            border-color: #1a7cbc;
            color: #fff;
            margin: 4px;
            border-radius: 5px !important;
        }

        .btn-group>.btn-group:not(:first-child)>.btn,
        .btn-group>.btn:nth-child(n+3),
        .btn-group>:not(.btn-check)+.btn {
            background-color: #1a7cbc;
            border-color: #1a7cbc;
            color: #fff;
            margin: 4px;
            border-radius: 5px !important;
        }

        .no-border {
            border: none !important;
            background-color: transparent !important;
        }

        .pad-zero {
            padding: 0 !important;
        }

        .dark_pera {
            color: #8392a5 !important;
        }

        .btn-primary {
            color: #fff;
            background-color: #1a7cbc !important;
            border-color: #1a7cbc !important;
        }

        /* modify toastr success css to default green */
        .toast-success {
            background-color: #2ECC71 !important;
        }

        .custom-table {
            table-layout: fixed;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            word-wrap: break-word;
        }

        .custom-table th,
        .custom-table td {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .input-group {
            width: 100%;
        }
    </style>
@endsection
@section('content')


    <section class="content">
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
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card">
                            <div class="col-md-12">
                                <div class="manage-button">
                                    <div class="body-title">
                                        <h5>Expense Report</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <form action="{{ route('report.expense.search') }}" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mt-2">
                                            <input type="date" name="start_date" id="start_date"
                                                class="flatDate form-control" placeholder="Updated Date Form" required>
                                        </div>
                                        <!-- col end -->
                                        <div class="col-md-4 mt-2">
                                            <input type="date" name="end_date" id="end_date"
                                                class="flatDate form-control" placeholder="Updated Date To" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-2">
                                            <button type="button" class="btn btn-success"
                                                id="datasearchSButton">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js_scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(function() {
            $(document.body).on('click', '#btnExport', function() {
                let table = document.getElementsByTagName("table");

                TableToExcel.convert(table[0], {
                    name: `business.xlsx`,
                    sheet: {
                        name: 'business'
                    }
                });
            });
            $(document.body).on('click', '#datasearchSButton', function(e) {
                e.preventDefault();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                if (start_date == '') {
                    toastr.warning('Please select start date', 'Oops!');
                    return false;
                }
                if (end_date == '') {
                    toastr.warning('Please select end date', 'Oops!');
                    return false;
                }
                $.ajax({
                    url: "{{ route('report.expense.search') }}",
                    type: "get",
                    data: {
                        start_date,
                        end_date
                    },
                    success: function(response) {
                        var newWindow = window.open();
                        newWindow.document.write(response);
                        newWindow.document.close();

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: "The Warrning?",
                            text: "Something Is wrong",
                            icon: 'question',
                            confirmButtonColor: '#556ee6'
                        });
                        return false;
                    }
                });
            });
        });
    </script>
@endsection
