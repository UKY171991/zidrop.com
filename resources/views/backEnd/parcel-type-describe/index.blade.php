@extends('backEnd.layouts.master')
@section('title', 'Status Description')
@section('content')
    <style>
        .btn-primary,
        .trash_icon {
            padding: 0.2rem 0.5rem !important;
            border-radius: 0.25rem !important;
        }
    </style>
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
                                        <h5>Status Description</h5>
                                    </div>
                                    <div class="float-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#ParcelTypeModal"><i class="fa fa-plus"></i>
                                            Create
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <table id="example1" class="table table-bordered table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Describtion</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parcelTypeDescribes as $key => $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->parcelType->title }}</td>
                                                <td>{{ $value->description }}</td>
                                                <td>{{ $value->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <ul class="action_buttons">
                                                        <li>
                                                            <button type="button" class="btn btn-primary editButton "
                                                                data-id="{{ $value->id }}"
                                                                data-title="{{ $value->parcel_type_id }}"
                                                                data-description="{{ $value->description }}"
                                                                data-status="{{ $value->status }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('status-description.destroy', $value->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="hidden_id"
                                                                    value="{{ $value->id }}">
                                                                <button type="submit" class="trash_icon show_confirm"
                                                                    title="Delete"><i class="fa fa-trash"></i></button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Section  -->
        <!-- Button trigger modal -->
        <!-- Modal -->
        <div class="modal fade" id="ParcelTypeModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('status-description.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="ExpenseTypeModalLabel">Add Status Description</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="hidden_id" id="hidden_id">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Status Type <strong class="text-danger">*</strong></label>
                                    <select name="parcel_type_id" id="parcel_type_id" class="form-control" required>
                                        <option value="">Select Status</option>
                                        @foreach ($parceltypes as $parcelType)
                                            <option value="{{ $parcelType->id }}">{{ $parcelType->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="">Describtion<strong class="text-danger">*</strong></label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Description" rows="5" required></textarea>

                                </div>
                                <div class="col-md-12">
                                    <label for="">Status<strong class="text-danger">*</strong></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_js_scripts')

    <script>
        $(document.body).on('click', '.editButton', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var title = $(this).data('title');
            var description = $(this).data('description');
            var status = $(this).data('status');

            $('#ParcelTypeModal').modal('show');
            $('#ExpenseTypeModalLabel').text('Edit Status Description');

            // Set the form action dynamically
            // $('#ExpenseTypeModal form').attr('action', '{{ url('expense-type') }}/' + id);
            $('#ParcelTypeModal form').attr('action', '{{ route('status-description.update', ':id') }}'.replace(
                ':id', id));

            // Set the form method attribute directly
            $('#ParcelTypeModal form').append('@method('PUT')');

            $('#hidden_id').val(id);
            $('#parcel_type_id').val(title);
            $('#description').val(description);
            $('#status').val(status);
        }); 
        $(document.body).ready(function() {
            // after submit form data will be reset
            $('#ParcelTypeModal').on('hidden.bs.modal', function() {
                $('#ParcelTypeModal').attr('action', '{{ route('status-description.store') }}');
                $('#ExpenseTypeModalLabel').text('Add Status Description');
                $('#ParcelTypeModal form').append('@method('POST')');
                $('#ParcelTypeModal form').get(0).reset();
            });
        });
    </script>
@endsection
