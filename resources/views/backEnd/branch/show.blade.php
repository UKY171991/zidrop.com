@extends('admin.layout.master')
@section('title')
    Branch
@endsection
@section('css')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Branch
        @endslot
        @slot('title')
            Branch
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <h3 class=" text-center mb-3"> Branch</h3>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary float-end btn-sm" >
                                <a href="{{ route('branch.index') }}" class="text-white">Branchs</a>
                            </button>
                        </div>
                    </div>
                    <div class=" mb-0">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="title" class="col-form-label">Title </label>
                                <input type="text" name="title" value="{{ $branch->title }}" class="form-control"
                                    id="title" disabled>
                               
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="key_person" class="col-form-label">Key Person </label>
                                <input type="text" name="key_person" value="{{ $branch->key_person }}"
                                    class="form-control" id="key_person" disabled>
                               
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="division" class="col-form-label">Division </label>
                               <input type="text" name="division" value="{{ $branch->division->name }}" class="form-control"
                                    id="division" disabled>
                                
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="district" class="col-form-label">District</label>
                               <input type="text" name="district" value="{{ $branch->district->name }}" class="form-control"
                                    id="district" disabled>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="upazila" class="col-form-label">Upazila </label>
                                <input type="text" name="upazila" value="{{ $branch->upazila->name }}" class="form-control"
                                    id="upazila" disabled>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="phone" class="col-form-label">Phone </label>
                                <input type="tel" name="phone" value="{{ $branch->phone }}" class="form-control"
                                    id="phone" disabled>
                                
                            </div>
                            <div class="col-md-4 mb-2">
                                    <label for="serial" class="col-form-label">Serial</label>
                                    <input type="number" name="serial" value="{{ $branch->serial }}" class="form-control"
                                        id="serial">
                                       
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="address" class="col-form-label">Address </label>
                                <input type="text" name="address" value="{{ $branch->address }}" class="form-control"
                                    id="address" disabled>
                               
                            </div>
                            
                            <div class="col-md-4 mb-2">
                                <label for="email" class="col-form-label">Email </label>
                                <input type="text" name="email" value="{{ $branch->email }}" class="form-control"
                                    id="email" disabled>
                               
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="description" class="col-form-label">Description </label>
                                <textarea name="description" id="description" class="form-control" cols="30" rows="2" disabled>{{ $branch->description }}</textarea>
                               
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="is_active" class="col-form-label">Status </label>
                                <input type="text" name="is_active" value="{{ $branch->is_active == 1 ? 'Active' : 'Inactive' }}" class="form-control"
                                    id="is_active" disabled >
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    
@endsection
@section('script')
    <script>
        $(document).ready(function() {
           
        });
    </script>
@endsection
