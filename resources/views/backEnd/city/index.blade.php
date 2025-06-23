@extends('backEnd.layouts.master')
@section('title', 'City')
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
                                        <h5>Manage City</h5>
                                    </div>
                                    <div class="quick-button">
                                        <a class="btn btn-primary btn-actions btn-create text-white" data-toggle="modal"
                                            data-target="#CityModal">
                                            <i class="fa fa-plus"></i> Add City
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>City</th>
                                            <th> Slug</th>
                                            <th>Status</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cities as $key => $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->title }}</td>
                                                <td>{{ $value->slug }}</td>
                                                <td>{{ $value->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-actions btn-edit editCity"
                                                        data-id="{{ $value->id }}" data-title="{{ $value->title }}"
                                                        data-slug="{{ $value->slug }}" data-status="{{ $value->status }}" >
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                   
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    <!-- Modal -->
    <div class="modal fade" id="CityModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add City</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-city.store') }}" method="post">
                       
                        @csrf
                        <div class="form-group">
                          <input type="hidden" name="id" id="id">
                            <label for="title">City <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="title" id="title"
                                aria-describedby="helpId" placeholder="Enter City" required>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




@endsection

@section('custom_js_scripts')
    <script>
        $(document.body).ready(function() {
            $('.editCity').click(function() {
             
                var id = $(this).data('id');
                var title = $(this).data('title');
                $('#CityModal #id').val(id);
                $('#CityModal #title').val(title);
                // change CityModal title
                $('#CityModal .modal-title').text('Edit City');
                // change form route  
                $('#CityModal form').append('@method('PUT')');
                $('#CityModal form').attr('action', '{{ route('admin-city.update', ':id') }}'.replace(':id', id));
                $('#CityModal').modal('show');
              });
        });

        // on hide modal
        $('#CityModal').on('hidden.bs.modal', function() {
            $('#CityModal #id').val('');
            $('#CityModal .modal-title').text('Add City');
                // change form route  
                $('#CityModal form').append('@method('POST')');
                $('#CityModal form').attr('action', '{{ route('admin-city.store') }}');
            $('#CityModal #title').val('');
        });

    </script>
@endsection
