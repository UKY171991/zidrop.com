@extends('frontEnd.layouts.pages.agent.agentmaster')
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
                                        <h5>Expense</h5>
                                    </div>
                                    <div class="float-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#ExpenseModal"><i class="fa fa-plus"></i>
                                            Create Expense
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <table id="example55" class="table table-bordered table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Expense</th>
                                            <th>Title</th>
                                            <th>Vehicle</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Receipt</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->expense_number }}</td>
                                                <td>{{ $expense->title }}</td>
                                                <td>{{ $expense->vehicle }}</td>
                                                <td> {{ optional($expense->expensetype)->title }}</td>
                                                <td>{{ \Carbon\Carbon::parse($expense->date)->format('M j, Y') }}</td>
                                                <td>{{ number_format($expense->amount, 2) }}</td>
                                                <td>
                                                    @if ($expense->receipt_file)
                                                        <a href="{{ asset('uploads/expense/' . $expense->receipt_file) }}"
                                                            download="download"><svg xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-download">
                                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4">
                                                                </path>
                                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                                <line x1="12" y1="15" x2="12"
                                                                    y2="3">
                                                                </line>
                                                            </svg></a>
                                                    @else
                                                        <span class="text-danger">__</span>
                                                    @endif
                                                </td>
                                                <td>

                                                    <div class="inline">
                                                        <button type="button"
                                                            class="text-warning no-border pad-zero ExpenseShowButton"
                                                            data-expense_number="{{ $expense->expense_number }}"
                                                            data-title="{{ $expense->title }}"
                                                            data-vehicle="{{ $expense->vehicle }}"
                                                            data-done_by="{{ $expense->done_by }}"
                                                            data-expense_type="{{ $expense->expensetype->title }}"
                                                            data-date="{{ \Carbon\Carbon::parse($expense->date)->format('M j, Y') }}"
                                                            data-amount="{{ number_format($expense->amount, 2) }}"
                                                            data-note="{{ $expense->note }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-eye">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                                                </path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </button>
                                                        <button type="button"
                                                            class="text-success no-border pad-zero ml-2 ExpenseEditButton"
                                                            data-id="{{ $expense->id }}"
                                                            data-expense_number="{{ $expense->expense_number }}"
                                                            data-title="{{ $expense->title }}"
                                                            data-vehicle="{{ $expense->vehicle }}"
                                                            data-expense_type_id="{{ $expense->expense_type_id }}"
                                                            data-date="{{ $expense->date }}"
                                                            data-amount="{{ $expense->amount }}"
                                                            data-receipt_file="{{ $expense->receipt_file }}"
                                                            data-note="{{ $expense->note }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-edit">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                </path>
                                                                <path
                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        {{-- <form method="POST"
                                                            action="{{ route('expense.destroy', $expense->id) }}"
                                                            accept-charset="UTF-8" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class=" text-danger confirm_dialog no-border pad-zero show_confirm"
                                                                data-bs-toggle="tooltip" data-bs-original-title="Detete"
                                                                style="border: none">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-trash-2">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path
                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                    </path>
                                                                    <line x1="10" y1="11" x2="10"
                                                                        y2="17"></line>
                                                                    <line x1="14" y1="11" x2="14"
                                                                        y2="17"></line>
                                                                </svg>
                                                            </button>
                                                        </form> --}}
                                                    </div>
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
        {{-- View Modal --}}
        <div class="modal fade" id="ExpenseViewModal"role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ExpenseViewModalLabel">Exprense Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row  mb-2">
                            <div class="col-md-12 mb-2">
                                <label for="title" class="form-label">Expense Title </label>
                                <p id="view_title" class="dark_pera"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="expense_number" class="form-label">Expense Number</label>
                                <p id="view_expense_number" class="dark_pera"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="title" class="form-label">Expense Type</label>
                                <p id="view_expense_type_id" class="dark_pera"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="title" class="form-label">Vehicle</label>
                                <p id="view_vehicle" class="dark_pera"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="title" class="form-label">Done By</label>
                                <p id="view_done_by" class="dark_pera"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="title" class="form-label">Date</label>
                                <p id="view_date" class="dark_pera"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="title" class="form-label">Amount</label>
                                <p id="view_amount" class="dark_pera"></p>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="title" class="form-label">Note</label>
                                <p id="view_note" class="dark_pera"></p>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--Add/Edit Modal -->
        <div class="modal fade" id="ExpenseModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('agentexpense.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="ExpenseModalLabel">Add Expense</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row  mb-2">
                                <div class="col-md-12 mb-2">
                                    <label for="title" class="form-label">Expense Title <strong
                                            class="text-denger">*</strong></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        placeholder="Enter Expense Title" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="expense_number" class="form-label">Expense Number<strong
                                            class="text-denger">*</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">#EXP-</span>
                                        </div>
                                        <input type="text" name="expense_number" id="expense_number"
                                            class="form-control" style=""
                                            placeholder="Auto-generated with prefix #EXP-" value="{{ $lastExpNUmber }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Expense Type<strong
                                            class="text-denger">*</strong></label>
                                    <select name="expense_type_id" id="expense_type_id" class="form-control" required>
                                        <option value="">Select Expense Type</option>
                                        @foreach ($ExpenseTypes as $expense_type)
                                            <option value="{{ $expense_type->id }}">{{ $expense_type->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Vehicle<strong
                                            class="text-denger">*</strong></label>
                                    <input type="text" name="vehicle" id="vehicle" class="form-control" required>
                                </div>
                                {{-- <div class="col-md-6 mb-2">
                                <label for="title" class="form-label">Unit<strong
                                        class="text-denger">*</strong></label>
                                <input type="text" name="unit" id="unit" class="form-control" >
                            </div> --}}
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Date<strong
                                            class="text-denger">*</strong></label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Amount<strong
                                            class="text-denger">*</strong></label>
                                    <input type="number" name="amount" id="amount" class="form-control" required
                                        step="any">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Receipt(less than 1000KB)</label>
                                    <input type="file" name="receipt_file" id="receipt_file" class="form-control">
                                    {{-- show image for edit --}}
                                    <div class="mt-2" id="showImage"></div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="title" class="form-label">Note<strong
                                            class="text-denger">*</strong></label>
                                    <textarea name="note" id="note" class="form-control" required></textarea>
                                </div>

                            </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-secondary CloseModal"
                         data-dismiss="modal">Close</button> --}}
                                <button type="submit" class="btn btn-primary py-1">Save</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js_scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script>
        $(document.body).on('click', '.ExpenseEditButton', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var expense_number = $(this).data('expense_number');
            expense_number = expense_number.split('-')[1];
            var title = $(this).data('title');
            var vehicle = $(this).data('vehicle');
            var expense_type_id = $(this).data('expense_type_id');
            var date = $(this).data('date');
            var amount = $(this).data('amount');
            var receipt_file = $(this).data('receipt_file');
            var note = $(this).data('note');

            // Set the form action dynamically
            $('#ExpenseModal form').attr('action', '{{ route('agentexpense.update', ':id') }}'.replace(':id', id));

            // Set the form method attribute directly
            $('#ExpenseModal form').append('@method('PUT')');

            $('#expense_number').val(expense_number);
            $('#title').val(title);
            $('#vehicle').val(vehicle);
            $('#expense_type_id').val(expense_type_id);
            $('#date').val(date);
            $('#amount').val(amount);
            $('#ExpenseModal textarea').append(note);
            // image show
            if (receipt_file) {
                $('#showImage').html(
                    `<img src="{{ asset('uploads/expense') }}/${receipt_file}" alt="Receipt Image" width="100px" height="100px">`
                );
            } else {
                $('#showImage').html(`<span class="text-danger">No Image</span>`);
            }
            // remove required attr from file input
            $('#receipt_file').removeAttr('required');
            $('#ExpenseModalLabel').text('Edit Expense');
            $('#ExpenseViewModal').modal('hide');
            $('#ExpenseModal').modal('show');
        });
        $(document.body).on('click', '.ExpenseShowButton', function(e) {
            e.preventDefault();
            var expense_number = $(this).data('expense_number');
            var title = $(this).data('title');
            var vehicle = $(this).data('vehicle');
            var done_by = $(this).data('done_by');
            var expense_type = $(this).data('expense_type');
            var date = $(this).data('date');
            var amount = $(this).data('amount');

            var note = $(this).data('note');

            $('#view_expense_number').html('');
            $('#view_expense_number').html(expense_number);
            $('#view_title').html(title);
            $('#view_vehicle').html(vehicle);
            $('#view_done_by').html(done_by);
            $('#view_expense_type_id').html(expense_type);
            $('#view_date').html(date);
            $('#view_amount').html(amount);
            $('#view_note').html(note);
            $('#ExpenseModal').modal('hide');
            $('#ExpenseViewModal').modal('show');
        });
        $(document.body).ready(function() {
            // after submit form data will be reset
            $('#ExpenseModal').on('hidden.bs.modal', function() {
                $('#ExpenseModal').attr('action', '{{ route('agentexpense.store') }}');
                $('#ExpenseModalLabel').text('Add Expense');
                $('#ExpenseModal form').append('@method('POST')');
                $('#showImage').html('');
                $('#ExpenseModal form').get(0).reset();
            });
        });
        $(document.body).on('click', '#ExpenseModal .close', function(e) {
            e.preventDefault();
            // Listen for the 'hidden.bs.modal' event
            $('#ExpenseModal').on('hidden.bs.modal', function() {
                // Remove the 'hidden.bs.modal' event listener to avoid multiple bindings
                $(this).off('hidden.bs.modal');
                // Reset the form after the modal is hidden
                $('#ExpenseModal').attr('action', '{{ route('agentexpense.store') }}');
                $('#ExpenseModalLabel').text('Add Expense');
                $('#ExpenseModal form').append('@method('POST')');
                $('#ExpenseModal form')[0].reset();
            });

            // Hide the modal
            $('#ExpenseModal').modal('hide');
        });
        $(document.body).on('click', '#ExpenseViewModal .close', function(e) {
            e.preventDefault();
            // Listen for the 'hidden.bs.modal' event
            $('#ExpenseModal').on('hidden.bs.modal', function() {
                // Remove the 'hidden.bs.modal' event listener to avoid multiple bindings
                $(this).off('hidden.bs.modal');
            });

            // Hide the modal
            $('#ExpenseViewModal').modal('hide');
        });
        $(document).ready(function() {
            $('#example55').DataTable({
                dom: 'Bfrtip',
                sort: false,
                lengthMenu: [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]
                ],

                buttons: [
                    'pageLength',
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6]
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
@endsection
