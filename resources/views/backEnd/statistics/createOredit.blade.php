@extends('backEnd.layouts.master')
@section('title', 'Create Feature')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h5 class="m-0 text-dark">Welcome !! {{ auth::user()->name }}</h5> --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="#">Statistics Details</a></li>
                        <li class="breadcrumb-item active">Manage</li>
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
                            <h5>Create Statistics Details</h5>
                        </div>
                        <div class="quick-button">
                            <a href="{{ route('statistics-details.index') }}"
                                class="btn btn-primary btn-actions btn-create">
                                Manage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="box-content">
                        <div class="row">
                            <div class=" col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Add Statistics Details </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form role="form" action="{{ route('statistics-details.update', $statisticDetail->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                      @csrf
                                      @method('PUT') {{-- Method override for PUT/PATCH requests --}}
                                      <div class="card-body">
                                             
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_delivery">Total Delivery (M)</label>
                                                    <input type="number" min="0" step="any"
                                                           class="form-control {{ $errors->has('total_delivery') ? ' is-invalid' : '' }}"
                                                           value="{{ old('total_delivery', $statisticDetail->total_delivery ?? '') }}" name="total_delivery"
                                                           id="total_delivery">
                                                    @error('total_delivery')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_customers">Total Customers (M)</label>
                                                    <input type="number" min="0" step="any"
                                                           class="form-control {{ $errors->has('total_customers') ? ' is-invalid' : '' }}"
                                                           value="{{ old('total_customers', $statisticDetail->total_customers ?? '') }}" name="total_customers"
                                                           id="total_customers">
                                                    @error('total_customers')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_years">Total Years</label>
                                                    <input type="number" min="0" step="any"
                                                           class="form-control {{ $errors->has('total_years') ? ' is-invalid' : '' }}"
                                                           value="{{ old('total_years', $statisticDetail->total_years ?? '') }}" name="total_years" id="total_years">
                                                    @error('total_years')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_member">Total Member (K)</label>
                                                    <input type="number" min="0" step="any"
                                                           class="form-control {{ $errors->has('total_member') ? ' is-invalid' : '' }}"
                                                           value="{{ old('total_member', $statisticDetail->total_member ?? '') }}" name="total_member"
                                                           id="total_member">
                                                    @error('total_member')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                          {{-- <div class="row">
                                              <div class="col-md-6">
                                                  <div class="form-group">
                                                      <div class="custom-label">
                                                          <label>Status</label>
                                                      </div>
                                                      <div class="box-body pub-stat display-inline">
                                                          <input
                                                              class="form-control{{ $errors->has('is_active') ? ' is-invalid' : '' }}"
                                                              type="radio" id="active" name="is_active" value="1"
                                                              {{ old('is_active', $statisticDetail->is_active ?? 1) == 1 ? 'checked' : '' }}>
                                                          <label for="active">Active</label>
                                                          @error('is_active')
                                                          <span class="invalid-feedback">
                                                              <strong>{{ $message }}</strong>
                                                          </span>
                                                          @enderror
                                                      </div>
                                                      <div class="box-body pub-stat display-inline">
                                                          <input
                                                              class="form-control{{ $errors->has('is_active') ? ' is-invalid' : '' }}"
                                                              type="radio" name="is_active" value="0" id="inactive"
                                                              {{ old('is_active', $statisticDetail->is_active ?? 1) == 0 ? 'checked' : '' }}>
                                                          <label for="inactive">Inactive</label>
                                                          @error('is_active')
                                                          <span class="invalid-feedback">
                                                              <strong>{{ $message }}</strong>
                                                          </span>
                                                          @enderror
                                                      </div>
                                                  </div>
                                              </div>
                                          </div> --}}
                                      </div>
                                      <div class="card-footer">
                                          <button type="submit" class="btn btn-primary">Update</button>
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
