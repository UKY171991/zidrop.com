@extends('backEnd.layouts.master')
@section('title', 'Expense')
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

        .input-group {
            width: 100%;
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
                                        <h5>Create Expense</h5>
                                    </div>
                                    <div class="float-right">
                                        <a href="{{ route('expense.index') }}" class="btn btn-primary"><i
                                                class="fa fa-list"></i> Expense List</a>

                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <form action="{{ route('expense.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="">
                                        <h5 class="modal-title" id="ExpenseModalLabel">Add Expense</h5>
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
                                                        placeholder="Auto-generated with prefix #EXP-"
                                                        value="{{ $lastExpNUmber }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="title" class="form-label">Expense Type<strong
                                                        class="text-denger">*</strong></label>
                                                <select name="expense_type_id" id="expense_type_id" class="form-control"
                                                    required>
                                                    <option value="">Select Expense Type</option>
                                                    @foreach ($ExpenseTypes as $expense_type)
                                                        <option value="{{ $expense_type->id }}">
                                                            {{ $expense_type->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="title" class="form-label">Vehicle<strong
                                                        class="text-denger">*</strong></label>
                                                <input type="text" name="vehicle" id="vehicle" class="form-control"
                                                    required>
                                            </div>
                                            {{-- <div class="col-md-6 mb-2">
                                                <label for="title" class="form-label">Unit<strong
                                                        class="text-denger">*</strong></label>
                                                <input type="text" name="unit" id="unit" class="form-control" >
                                            </div> --}}
                                            <div class="col-md-6 mb-2">
                                                <label for="title" class="form-label">Date<strong
                                                        class="text-denger">*</strong></label>
                                                <input type="date" name="date" id="date" class="form-control"
                                                    required>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="title" class="form-label">Amount<strong
                                                        class="text-denger">*</strong></label>
                                                <input type="number" name="amount" id="amount" class="form-control"
                                                    required step="any">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="title" class="form-label">Receipt(less than
                                                    1000KB)</label>
                                                <input type="file" name="receipt_file" id="receipt_file"
                                                    class="form-control">
                                                {{-- show image for edit --}}
                                                <div class="mt-2" id="showImage"></div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label for="title" class="form-label">Note<strong
                                                        class="text-denger">*</strong></label>
                                                <textarea name="note" id="note" class="form-control" required></textarea>
                                            </div>

                                        </div>
                                        <div class="">
                                            {{-- <button type="button" class="btn btn-secondary CloseModal"
                                         data-dismiss="modal">Close</button> --}}
                                            <button type="submit" class="btn btn-primary py-1">Save</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>



@endsection

@section('custom_js_scripts')

@endsection
