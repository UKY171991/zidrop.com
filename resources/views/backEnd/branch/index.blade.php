@extends('backEnd.layouts.master')
@section('title', 'Branch')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0 text-dark">Welcome !! {{ auth::user()->name }}</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="#">Branch</a></li>

                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <div class="manage-button">
                        <div class="body-title">
                            <h5>Branch</h5>
                        </div>
                        <div class="quick-button">
                            <button type="button" class="btn btn-primary float-end btn-sm" data-toggle="modal"
                                data-target="#addBranch">
                                Add Branch
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="datatable" class="table table-striped">
                                    <thead class="bg-blue">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Phone</th>
                                            <th>City</th>
                                            <th>Town</th>
                                            <th>Serial</th>
                                            <th>Email</th>
                                            <th>Image</th>
                                            <th>Poster</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Branches as $Branche)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $Branche->title }}</td>
                                                <td>{{ $Branche->phone }}</td>
                                                <td>{{ $Branche->city->title }}</td>
                                                <td>{{ $Branche->town->title }}</td>
                                                <td>{{ $Branche->serial }}</td>
                                                <td>{{ $Branche->email }}</td>
                                                <td>
                                                    @if ($Branche->key_person_image)
                                                        <img src="{{ asset($Branche->key_person_image) }}" alt="Avatar"
                                                            width="50px">
                                                    @else
                                                        <img src="{{ asset('/images/user.png') }}" alt="Avatar"
                                                            width="50px">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($Branche->bank_poster)
                                                        <img src="{{ asset($Branche->bank_poster) }}" alt="Avatar"
                                                            width="50px">
                                                    @else
                                                        N/F
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($Branche->is_active == 1)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-link dropdown-toggle" type="button"
                                                            id="dropdownMenuButton1" data-toggle="dropdown"
                                                            aria-expanded="true" aria-haspopup="true">
                                                            <span class="fa fa-ellipsis-v"></span>
                                                        </button>
                                                        <ul class="dropdown-menu px-2 py-2" style="min-width: 3rem;"
                                                            aria-labelledby="dropdownMenuButton1">
                                                            <li class="m-2">
                                                                <button type="button"
                                                                    class="btn btn-primary btn-sm editButton"
                                                                    data-id="{{ $Branche->id }}"
                                                                    data-title="{{ $Branche->title }}"
                                                                    data-key_person="{{ $Branche->key_person }}"
                                                                    data-address="{{ $Branche->address }}"
                                                                    data-phone="{{ $Branche->phone }}"
                                                                    data-state="{{ $Branche->cities_id }}"
                                                                    data-area="{{ $Branche->town_id }}"
                                                                    data-serial="{{ $Branche->serial }}"
                                                                    data-image="{{ $Branche->key_person_image }}"
                                                                    data-poster="{{ $Branche->bank_poster }}"
                                                                    data-email="{{ $Branche->email }}"
                                                                    data-description="{{ $Branche->description }}"
                                                                    data-is_active="{{ $Branche->is_active }}">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </li>
                                                            {{-- <li class="m-2">
                                                    <a href="{{ route('admin.branch.show', $Branche->id) }}"
                                                        class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                </li> --}}

                                                            <li class="m-2">
                                                                <form
                                                                    action="{{ route('admin.branch.destroy', $Branche->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger btn-sm show_confirm"
                                                                        id="">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>

                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
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
        <!-- Add Menu Modal -->

        <!-- Modal -->
        <div class="modal fade" id="addBranch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="aaddBranchLabel">Add Branch</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.branch.store') }}" method="POST" class="needs-validation"
                        id="addBranchform" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="col-form-label">Title <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}"
                                        class="form-control" id="title" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide Title.
                                    </div>
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="key_person" class="col-form-label">Head Of Branch </label>
                                    <input type="text" name="key_person" value="{{ old('key_person') }}"
                                        class="form-control" id="key_person">
                                    @error('key_person')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="address" class="col-form-label">Address <span
                                            class="text-danger">*</span></label>
                                    <textarea name="address" id="address" class="form-control" cols="30" rows="2" required>{{ old('address') }}</textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide Address.
                                    </div>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="col-md-6 mb-2">
                                    <label for="phone" class="col-form-label">Phone <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}"
                                        class="form-control" id="phone" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide Phone.
                                    </div>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="serial" class="col-form-label">Serial</label>
                                    <input type="number" name="serial" value="{{ old('serial') }}"
                                        class="form-control" id="serial">
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide Phone.
                                    </div>
                                    @error('serial')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="city" class="col-form-label">City <span
                                            class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <select type="text" class=" form-control" value="{{ old('city') }}"
                                            name="city" id="city" required>
                                            <option value="" selected="selected">City</option>
                                            @foreach ($wcities as $key => $value)
                                                <option value="{{ $value->id }}"> {{ $value->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please provide Phone.
                                        </div>
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="town" class="col-form-label">Town <span
                                            class="text-danger">*</span></label>
                                    <select type="text" class="form-control" value="{{ old('town') }}"
                                        name="town" id="town" placeholder="" required>
                                        <option value="" selected="selected">Town</option>

                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide Phone.
                                    </div>
                                    @error('town')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label for="email" class="col-form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control" id="email" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide Email.
                                    </div>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="description" class="col-form-label">Description </label>
                                    <textarea name="description" id="description" class="form-control" cols="30" rows="2">{{ old('description') }}</textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide Description.
                                    </div>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="mt-2">
                                        <div>
                                            <label class="form-label">Image(Max 500kb) </label>
                                            <input type="file" name="image" id="image" class="form-control">
                                        </div>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please provide Image.
                                        </div>
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2" id="holder">
                                    {{-- <img src="{{ asset('preview.jpg') }}" class="mt-4" width="90" /> --}}
                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="mt-2">
                                        <div>
                                            <label class="form-label">Bank Account Poster(Max 500kb) </label>
                                            <input type="file" name="bank_poster" id="bank_poster" class="form-control">
                                        </div>
                                        <div class="valid-feedback"> 
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please provide Poster Image.
                                        </div>
                                        @error('bank_poster')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2" id="holder2">
                                    {{-- <img src="{{ asset('preview.jpg') }}" class="mt-4" width="90" /> --}}
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="is_active" class="col-form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select name="is_active" id="is_active" class="form-control" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please select Status.
                                    </div>
                                    @error('is_active')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
    <script>
        $(document.body).ready(function() {
            $('#city').on('change', function() {
                var city_id = $(this).val();
                if (city_id) {
                    $.ajax({
                        url: "{{ url('/admin/get-town/') }}/" + city_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var d = $('select[name="town"]').empty();
                            $('select[name="town"]').append(
                                '<option value="" selected="selected" disabled>Town</option>'
                                );
                            $.each(data, function(key, value) {
                                $('select[name="town"]').append('<option value="' +
                                    value.id + '">' +
                                    value.title + '</option>');
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
        });
        $(document.body).on('click', '.editButton', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var title = $(this).data('title');
            var key_person = $(this).data('key_person');
            var address = $(this).data('address');
            var phone = $(this).data('phone');
            var state = $(this).data('state');
            var town = $(this).data('area');
            console.log('Town', town);
            var image = $(this).data('image');
            var bank_poster = $(this).data('bank_poster');
            var serial = $(this).data('serial');
            var email = $(this).data('email');
            var description = $(this).data('description');
            var is_active = $(this).data('is_active');
            $('#addBranch').modal('show');
            $('#aaddBranchLabel').text('Edit Branch');
            $('#addBranchform').attr('action', '{{ route('admin.branch.update', ':id') }}'.replace(':id',
                id));
            $('#addBranchform').append('@method('PUT')');
            $('#title').val(title);
            $('#key_person').val(key_person);
            $('#address').val(address);
            $('#inputGroupFile02').val(image);
            $('#inputGroupFile03').val(bank_poster);
            $('#holder img').attr('src', image);
            $('#holder2 img').attr('src', bank_poster);
            $('#phone').val(phone);
            $('#city').val(state);
            
            $.ajax({
                url: "{{ url('/admin/get-town/') }}/" + state,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var d = $('select[name="town"]').empty();
                    $('select[name="town"]').append(
                        '<option value="" selected="selected" disabled>Town</option>');
                    $.each(data, function(key, value) {
                        $('select[name="town"]').append('<option value="' + value.id + '">' +
                            value.title + '</option>');
                    });
                    $('select[name="town"]').val(town);
                },
            });
            $('#serial').val(serial);
            $('#email').val(email);
            $('#description').val(description);
            $('#is_active').val(is_active);
            // get state id from data atti  and pass to getArea function
            var StateIdd = $('#state').find(':selected').data('stateid');

        });
        $(document).ready(function() {
            // after submit form data will be reset
            $('#addBranch').on('hidden.bs.modal', function() {
                $('#addBranchform').attr('action', '{{ route('admin.branch.store') }}');
                $('#aaddBranchLabel').text('Add Branch');
                $('#addBranchform').append('@method('POST')');
                $('#addBranchform').get(0).reset();
            });

        });
        $(document).ready(function() {
            $('#BranchDatatable').DataTable({
                paging: true,
                ordering: true,
                info: false,
                scrollX: true,
            });
        });

        function getArea(stateid, areaNAme) {
            $.ajax({
                url: "{{ url('/get-area/') }}/" + stateid,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    var d = $('select[name="area"]').empty();
                    $('select[name="area"]').append(
                        '<option value="" selected="selected" disabled>Area</option>'
                    );
                    $.each(data, function(key, value) {
                        $('select[name="area"]').append(
                            '<option  value="' + value.zonename + '">' +
                            value
                            .zonename + '</option>');
                    });
                    $('select[name="area"]').val(areaNAme);
                },
            });
        }
        $(document.body).ready(function() {
            $('select[name="state"]').on('change', function() {
                var orderType = $(this).val();
                var stateid = $(this).find(':selected').data('stateid');
                console.log(stateid);
                if (stateid) {
                    $.ajax({
                        url: "{{ url('/get-area/') }}/" + stateid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            var d = $('select[name="area"]').empty();
                            $('select[name="area"]').append(
                                '<option value="" selected="selected" disabled>Area</option>'
                            );
                            $.each(data, function(key, value) {
                                $('select[name="area"]').append(
                                    '<option  value="' + value.zonename + '">' +
                                    value
                                    .zonename + '</option>');
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
        });
    </script>
@endsection
