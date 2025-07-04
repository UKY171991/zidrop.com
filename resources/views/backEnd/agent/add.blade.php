@extends('backEnd.layouts.master')
@section('title','Create Agent')
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
                      <h5>Create Agent</h5>
                    </div>
                    <div class="quick-button">
                      <a href="{{url('author/agent/manage')}}" class="btn btn-primary btn-actions btn-create">
                      Manage Agent
                      </a>
                    </div>  
                  </div>
                </div>
              <div class="col-md-12 col-sm-12">
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Add A New Agent</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                   <form action="{{url('author/agent/save')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="main-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="name">Name</label>
                          <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}">
                           @if ($errors->has('name'))
                            <span class="invalid-feedback">
                              <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                       
                      </div>
                      <!-- column end -->                      
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}">
                          @if ($errors->has('email'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="phone">Phone</label>
                          <input type="text" name="phone" id="phone" placeholder="0802 123 4567" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}">
                          <div class="del_nigeria_flag"></div>
                          @if ($errors->has('phone'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="designation">Designation</label>
                          <input type="text" name="designation" id="designation" class="form-control {{ $errors->has('designation') ? ' is-invalid' : '' }}" value="{{ old('designation') }}">
                          @if ($errors->has('designation'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('designation') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                        
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="commisiontype">Commision Type</label>
                          <select name="commisiontype" id="commisiontype" class="form-control {{ $errors->has('commisiontype') ? ' is-invalid' : '' }}" value="{{ old('commisiontype') }}">
                            <option value="">Select...</option>
                            <option value="1">Flat Rate</option>
                            <option value="2">Percent</option>
                          </select>
                          @if ($errors->has('commisiontype'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('commisiontype') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="commision">Delivery Commision</label>
                          <input type="number" name="commision" id="commision" class="form-control {{ $errors->has('commision') ? ' is-invalid' : '' }}" value="{{ old('commision') }}">
                          @if ($errors->has('commision'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('commision') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                       <div class="col-sm-6">
                        <div class="form-group">
                          <label for="bookcommision">Booking Commision</label>
                          <input type="number" name="bookcommision" id="bookcommision" class="form-control {{ $errors->has('bookcommision') ? ' is-invalid' : '' }}" value="{{old('bookcommision')}}">
                          @if ($errors->has('bookcommision'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('bookcommision') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="image">Image</label>
                          <input type="file" name="image" id="image" class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}" value="{{ old('image') }}">
                          @if ($errors->has('image'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('image') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="cities_id">City</label>
                          <select name="cities_id" id="cities_id" class="select2 form-control {{ $errors->has('cities_id') ? ' is-invalid' : '' }}" value="{{ old('cities_id') }}">
                            <option value="">Select...</option>
                            @foreach ($wcities as $key => $value)
                            <option value="{{ $value->id }}"> {{ $value->title }}
                            </option>
                        @endforeach
                          </select>
                          @if ($errors->has('cities_id'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('cities_id') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="password">Password</label>
                          <input type="password" name="password" id="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ old('password') }}">
                          @if ($errors->has('password'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
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
                      <div class="col-sm-12 mrt-30">
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