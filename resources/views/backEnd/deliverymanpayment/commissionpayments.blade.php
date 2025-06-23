@extends('backEnd.layouts.master')
@section('title', 'Deliveryman payment history')
@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
                /* Hide everything */
            }

            #agentcompaymenttable_wrapper,
            #agentcompaymenttable_wrapper * {
                visibility: visible !important;
                /* Show the table and its content */
            }

            #agentcompaymenttable {
                width: 100% !important;
                /* Force full width */
                display: table !important;
                /* Ensure table format */
            }
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
                                        <h5>Deliveryman Commission payment history</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">

                            </div>
                            <div class="card-body">


                                <table id="agentcompaymenttable" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Deliveryman Name</th>
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
            </div>
        </div>
    </section>

    <!-- Modal Section  -->
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
                    url: "{{ route('editor.deliveryman.deliveryman_get_com_payment', $deliverymanId) }}",
                },
                scrollX: true, // Keep scroll enabled for normal view
                
            });


        });
    </script>
@endsection
