@extends('frontEnd.layouts.pages.agent.agentmaster')

@section('title', $title)


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
                                    <h5 class="pageStatusTitle"> {{ $title }}</h5>

                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="example333" class="table table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Batch No</th>
                                        <th>Seal No</th>
                                        <th>Tag No</th>
                                        <th>Station</th>
                                        <th>Destination</th>
                                        <th>View</th>

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
            // data table
            $(document).ready(function(event) {
                var table33 = $('#example333').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    sorting: false,
                    responsive: true,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        ['10 rows', '25 rows', '50 rows', 'Show all']
                    ],
                    ajax: {
                        url: "{{ url('agent/agent_get_trans_report_data') }}",
                        data: {

                        }
                    },
                    language: {
                        lengthMenu: "Show _MENU_ ", // Customize the length menu display text
                        infoEmpty: "No records available to display.",
                        emptyTable: "There are no records in the table.",
                        zeroRecords: "No records match your search criteria."
                    }
                });

                // Searching
                $('#SearchDtataButton').click(function() {
                    var filter_id = $('input[name="filter_id"]').val();
                    var trackId = $('input[name="trackId"]').val();
                    var phoneNumber = $('input[name="phoneNumber"]').val();
                    var startDate = $('input[name="startDate"]').val();
                    var endDate = $('input[name="endDate"]').val();
                    var merchantId = $('input[name="merchantId"]').val();
                    var cname = $('input[name="cname"]').val();
                    var UpStatusArray = $('#Upstatuss').val() ?? null;
                    console.log(UpStatusArray);
                    // destroly datatable
                    var table33 = $('#example333').DataTable();
                    table33.destroy();
                    $('#example333').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        sorting: false,
                        responsive: true,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            ['10 rows', '25 rows', '50 rows', 'Show all']
                        ],
                        ajax: {
                            url: "{{ url('agent/agent_get_trans_report_data') }}",
                            data: {
                                filter_id: filter_id,
                                trackId: trackId,
                                phoneNumber: phoneNumber,
                                cname: cname,
                                merchantId: merchantId,
                                startDate: startDate,
                                endDate: endDate,
                                UpStatusArray: UpStatusArray,
                            }
                        },

                    });
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#receiveParcel').click(function() {
                    console.log('clicked');
                    var parcels = [];

                    $(':checkbox:checked').each(function(i) {
                        parcels[i] = $(this).val();
                    });

                    console.log(parcels.length);

                    if (parcels.length === 0) {
                        alert('Alert:: Please select minimum 1 parcel');
                    } else {
                        $.ajax({
                            type: "POST",
                            url: '{{ route('agent.parcel.receive') }}',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "parcels": parcels
                            },
                            success: function(response) {
                                if (response.success == 'success') {
                                    window.location.reload();
                                } else {
                                    console.log(response);
                                }

                            }
                        });
                    }

                });
            });

            $(document).ready(function() {

                $(document.body).on('change', '.selectItemCheckbox', function(event) {
                    event.preventDefault();
                    var ischecked = $(this).is(':checked');
                    console.log(ischecked);
                    if (!ischecked) {
                        $(this).parent().parent().removeClass('selected');
                        $("#My-Buttonn").prop('checked', false);

                    } else {
                        $(this).parent().parent().addClass('selected');
                    }
                });

                jQuery("#check_all_check").click(function() {
                    jQuery(':checkbox').each(function() {
                        if (this.checked == true) {
                            this.checked = false;
                        } else {
                            this.checked = true;
                        }
                    });
                });
                $(document.body).on('change', '#My-Buttonn', function() {
                    event.preventDefault();
                    var ischecked = $(this).is(':checked');
                    console.log(ischecked);
                    if (!ischecked) {
                        $(".selectItemCheckbox").removeAttr('checked');
                        $("#example333 tbody tr").removeClass('selected');
                        $(".selectItemCheckbox").each(function() {
                            this.checked = false;
                        });

                    } else {
                        $(".selectItemCheckbox").attr('checked');
                        $("#example333 tbody tr").addClass('selected');
                        // checked all checkbox
                        $(".selectItemCheckbox").each(function() {
                            this.checked = true;
                        });
                    }
                });
            });
        </script>
        <script>
            // change select2 placeholder text 
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Select Status",
                    allowClear: true,
                    multiple: true
                });
            });
        </script>

    @endsection
