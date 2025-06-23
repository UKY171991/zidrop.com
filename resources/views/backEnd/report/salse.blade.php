@extends('backEnd.layouts.master')
@section('title', 'Sales Report')
@section('content')
    <style>
        .display_hide_for_camera {
            display: none !important;
        }

        @media screen {
            #printSection {
                display: none !important;
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

            #example333_filter {
                display: none !important;
            }
        }

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
        }

        /* .btn-secondary{
        background-color: #4634FF !important;
        border-color: #6c757d !important;
    } */
        #example333 thead tr th {
            background-color: #4634FF !important;
            color: #fff !important;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <?php
        // $show_data = session()->get('show_data');
        ?>
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
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                   
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card">
                            <div class="col-sm-12">
                                <div class="manage-button">
                                    <div class="body-title">
                                        <h5>Income Report</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <form action="{{ route('admin.report.salse.search') }}" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mt-2">                                      
                                            <select name="report_type" id="report_type" class="form-control"
                                                required>
                                                <option value="">Select Report Type</option>
                                                <option value="del_charge">Delivery charge</option>
                                                <option value="cod_charge">COD Charge</option>
                                                <option value="tax">Tax </option>
                                                <option value="insurance">Insurance</option>
                                                <option value="walet_topup">Wallet Topup</option>
                                            </select> 
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <input type="date" name="start_date" id="start_date" class="flatDate form-control"
                                                placeholder="Updated Date Form" required>
                                        </div>
                                        <!-- col end -->
                                        <div class="col-md-4 mt-2">
                                            <input type="date" name="end_date" id="end_date" class="flatDate form-control" placeholder="Updated Date To" required>
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

    <!-- Modal Section  -->
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
            var report_type = $('#report_type').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (report_type == '') {
                    toastr.warning('Please select at-least one Report Type', 'Oops!');
                    return false;
            }
            if (start_date == '') {
                    toastr.warning('Please select start date', 'Oops!');
                    return false;
            }
            if (end_date == '') {
                    toastr.warning('Please select end date', 'Oops!');
                    return false;
            }
            $.ajax({
                url: "{{ route('admin.report.salse.search') }}",
                type: "get",
                data: {
                    report_type,
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

        $(document.body).on('click','.pdf-download',function(){
        var branch_id = $('#branch_id').val();
        var product_id = $('#product_id').val();
        var agent_id = $('#agent_id').val();
        var customer_id = $('#customer_id').val();
        var document_no = $('#document_no').val();
        var mr_no = $('#mr_no').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        $.ajax({
            url: "{{url('admin/searchReportsDetailsPrint')}}",
            type: "get",
            data: {branch_id, product_id, agent_id, customer_id, document_no, mr_no,start_date, end_date} ,
            success: function (response) {
                window.open(response);
            
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
