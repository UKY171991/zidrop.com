@extends('backEnd.layouts.master')
@section('title','State Add')
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="box-content">
            <div class="row">
              <div class="col-sm-12">
                  <div class="manage-button">
                    <div class="body-title">
                      <h5>Add</h5>
                    </div>
                    <div class="quick-button">
                      <a href="{{url('admin/deliverycharge/manage')}}" class="btn btn-primary btn-actions btn-create">
                      Manage
                      </a>
                    </div>  
                  </div>
                </div>
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">State Charge Add Instructions</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                   <form action="{{url('admin/deliverycharge/save')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="main-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="title">State Title</label>
                          <input type="text" name="title" id="title" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}">
                           @if ($errors->has('title'))
                            <span class="invalid-feedback">
                              <strong>{{ $errors->first('title') }}</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->

                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="deliverycharge">Delivery Charge</label>
                          <input type="text" name="deliverycharge" id="deliverycharge" class="form-control {{ $errors->has('deliverycharge') ? ' is-invalid' : '' }}" value="{{ old('deliverycharge') }}">
                           @if ($errors->has('deliverycharge'))
                            <span class="invalid-feedback">
                              <strong>{{ $errors->first('deliverycharge') }}</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="extradeliverycharge">Extra delivery charge more than 1 kg</label>
                          <input type="text" name="extradeliverycharge" id="extradeliverycharge" class="form-control {{ $errors->has('extradeliverycharge') ? ' is-invalid' : '' }}" value="{{ old('extradeliverycharge') }}">
                           @if ($errors->has('extradeliverycharge'))
                            <span class="invalid-feedback">
                              <strong>{{ $errors->first('extradeliverycharge') }}</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="cod">Cod charge</label>
                          <input type="text" name="cod" id="cod" class="form-control {{ $errors->has('cod') ? ' is-invalid' : '' }}" value="{{ old('cod') }}">
                           @if ($errors->has('cod'))
                            <span class="invalid-feedback">
                              <strong>{{ $errors->first('cod') }}</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="tax">Tax</label>
                          <input type="text" name="tax" id="tax" class="form-control {{ $errors->has('tax') ? ' is-invalid' : '' }}" value="{{ old('tax') }}">
                           @if ($errors->has('tax'))
                            <span class="invalid-feedback">
                              <strong>{{ $errors->first('tax') }}</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="insurance">Insurance</label>
                          <input type="text" name="insurance" id="insurance" class="form-control {{ $errors->has('insurance') ? ' is-invalid' : '' }}" value="{{ old('insurance') }}">
                           @if ($errors->has('insurance'))
                            <span class="invalid-feedback">
                              <strong>{{ $errors->first('insurance') }}</strong>
                            </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->


                      <div class="col-sm-6">
                        <div class="form-group">
                          <div class="custom-label">
                            <label>Publication Status</label>
                          </div>
                          <div class="box-body pub-stat display-inline">
                              <input class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" id="active" name="status" value="1">
                              <label for="active">Active</label>
                              @if ($errors->has('status'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('status') }}</strong>
                              </span>
                              @endif
                          </div>
                          <div class="box-body pub-stat display-inline">
                              <input class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" name="status" value="0" id="inactive">
                              <label for="inactive">Inactive</label>
                              @if ($errors->has('status'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('status') }}</strong>
                              </span>
                              @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 mrt-15">
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                </div>
              </div>
              <!-- col end -->

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection