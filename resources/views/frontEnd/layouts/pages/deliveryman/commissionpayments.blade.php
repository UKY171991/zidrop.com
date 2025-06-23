@extends('frontEnd.layouts.pages.deliveryman.master')

@section('title', 'Commission History')
@section('content')
    <div class="profile-edit mrt-30">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="body-title">

                    <h5 class="pageStatusTitle">Commission History</h5>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="tab-inner table-responsive">
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
                url: "{{ route('del.get_history', $deliverymanId) }}",
            },
           
        });


    });
</script>


@endsection
