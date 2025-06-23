@extends('backEnd.layouts.master')
@section('title', 'Settings')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="box-content">
                <div class="row">
                    {{-- SHow all errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card custom-card">
                            <div class="col-sm-12">
                                <div class="manage-button">
                                    <div class="body-title">
                                        <h5>Manage Settings</h5>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{route('superadmin.settings.update')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="agent_create_parcel" name="agent_create_parcel" value="" {{ $settings->agent_create_parcel == 1 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="agent_create_parcel">Agent Parcel Create Option</label>
                                              </div>
                                           
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>





@endsection

@section('custom_js_scripts')
    <script>
        $(document.body).ready(function() {
            $('#agent_create_parcel').on('change', function() {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                } else {
                    $(this).val(0);
                }
                var agent_create_parcel = $(this).val();
                console.log(agent_create_parcel);
                // ajax 
                $.ajax({
                    url: "{{ route('superadmin.settings.update') }}",
                    type: "PUT",
                    data: {
                        agent_create_parcel: agent_create_parcel,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // alart message tost
                        toastr.success('Settings Updated Successfully');
                    }
                });
            });
        });

    </script>
@endsection
