@extends('backEnd.layouts.master')
@section('title', 'Expense')
@section('content')
    <style>
        .btn-primary, .trash_icon{
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
                                        <h5>Expenses Type</h5>
                                    </div>
                                    <div class="float-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#ExpenseTypeModal"><i class="fa fa-plus"></i>
                                            Create Expense Type
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
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expensetypes as $key => $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->title }}</td>
                                                <td>{{ $value->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <ul class="action_buttons">
                                                       
                                                        <li>
                                                            <button type="button" class="btn btn-primary editButton "
                                                            data-id="{{ $value->id }}" 
                                                            data-title="{{ $value->title }}" 
                                                            data-status="{{ $value->status }}">
                                                            <i class="fa fa-edit"></i>
                                                             </button>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('expense-type.destroy', $value->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="hidden_id"
                                                                    value="{{ $value->id }}">
                                                                <button type="submit" class="trash_icon show_confirm"
                                                                    title="Delete"><i
                                                                        class="fa fa-trash"></i></button>
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
    <div class="modal fade" id="ExpenseTypeModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('expense-type.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="ExpenseTypeModalLabel">Add Exprense Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" name="title" id="title" class="form-control mb-2"
                        placeholder="Enter Expense Type">
                    <select name="status" id="status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>

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
    var status = $(this).data('status');

    $('#ExpenseTypeModal').modal('show');
    $('#ExpenseTypeModalLabel').text('Edit Expense Type');

    // Set the form action dynamically
    // $('#ExpenseTypeModal form').attr('action', '{{ url('expense-type') }}/' + id);
    $('#ExpenseTypeModal form').attr('action', '{{ route('expense-type.update', ':id') }}'.replace(':id', id));
    
    // Set the form method attribute directly
    $('#ExpenseTypeModal form').append('@method('PUT')');

    $('#title').val(title);
    $('#status').val(status);
});


    $(document.body).ready(function() {
    // after submit form data will be reset
    $('#ExpenseTypeModal').on('hidden.bs.modal', function() {
        $('#ExpenseTypeModal').attr('action', '{{ route('expense-type.store') }}');
        $('#ExpenseTypeModalLabel').text('Add Expense Type');
        $('#ExpenseTypeModal form').append('@method('POST')');
        $('#ExpenseTypeModal').get(0).reset();
    });
    });


</script>
@endsection
