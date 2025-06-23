@extends('backEnd.layouts.master')
@section('title', 'Subscription History')
@section('content')
<style>
    .subscription-badge {
    display: inline-block;
    padding: 2px 12px;
    font-size: 12px;
    font-weight: 700;
    border-radius: 10px;
    text-transform: uppercase;
    border: 1px solid transparent;
    }

    .subscription-badge.active {
        background-color: #e6f4ea; /* Light green background */
        color: #237a3b; /* Darker green text */
        border-color: #a8d5b1; /* Light green border */
    }
    .subscription-badge.disable {
        background-color: #fff8e1; /* Light yellow */
        color: #a17700; /* Warm yellow-brown text */
        border-color: #f3d28c;
    }
    .subscription-badge.expired {
        background-color: #ffe1e1; /* Light yellow */
        color: #a10000; /* Warm yellow-brown text */
        border-color: #f38c8c;
    }
</style>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card">
                            <div class="col-sm-12">
                                <div class="manage-button">
                                    <div class="body-title">
                                        <h4><b>
                                                <p style="color:green"> Merchant Subscription History</P>
                                            </b></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" width="100">
                                        <thead>
                                            <tr>
                                                <th scope="col">S/N</th>
                                                <th scope="col">Plan</th>
                                                <th scope="col" class="text-right">Amount</th>
                                                <th scope="col" class="text-center">Subscription Date</th>
                                                <th scope="col" class="text-center">Active Time</th>
                                                <th scope="col" class="text-center">Status</th>              
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($SubsHistos as $key => $value)
                                            <tr>
                                                <th>{{ $key + 1 }}</th>
                                                <td>{{ $value->plan->name ?? 'N/A' }}</td>
                                                <td class="text-right">{{ number_format($value->plan->price ?? 0, 2) }}</td>
                                                <td class="text-center">{{ $value->formatted_date }}</td>
                                                <td class="text-center">{{ $value->formatted_time }}</td>
                                                <td class="text-center">
                                                    @if($value->auto_expired == 1)
                                                    <span class="subscription-badge expired">
                                                        EXPIRED
                                                    </span>
                                                    @else
                                                        @if ($value->is_active == 1)
                                                        <a href="{{ url('editor/merchant/subscription/disable/' . $value->id) }}" class="show_confirm">
                                                            <span class="subscription-badge active">
                                                                ACTIVE
                                                                </span>
                                                        </a>
                                                        
                                                        @else
                                                        <span class="subscription-badge disable">
                                                            DISABLED
                                                        </span>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div> 
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal Section  -->
    <script>
        $(document).ready(function() {
            $('#merchantexample').DataTable({
                dom: 'Bfrtip',
                "lengthMenu": [
                    [200, 500, -1],
                    [200, 500, "All"]
                ],
                buttons: [{
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'Csv',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },

                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print all',
                        exportOptions: {
                            modifier: {
                                selected: null
                            }
                        }
                    },
                    {
                        extend: 'colvis',
                    },

                ],
                select: true
            });

            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
    <!-- SweetAlert Script -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Attach click event to elements with 'show_confirm' class
            document.querySelectorAll('.show_confirm').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();

                    const link = this.getAttribute('href');

                    swal({
                        title: "Are you sure?",
                        text: "You are about to disable this plan. This action cannot be undone.",
                        icon: "warning",
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                visible: true,
                                className: "btn-secondary"
                            },
                            confirm: {
                                text: "Yes, Disable it!",
                                visible: true,
                                className: "btn-danger"
                            }
                        },
                        dangerMode: true
                    }).then((willDelete) => {
                        if (willDelete) {
                            window.location.href = link;
                        }
                    });
                });
            });
        });
    </script>
@endsection
