@extends('backEnd.layouts.master')
@section('title','Auto Refresh')
@section('content')

<style>

</style>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h5 class="m-0 text-dark">Welcome !! {{auth::user()->name}}</h5>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="#">Note</a></li>
            <li class="breadcrumb-item active">Create</li>
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
                <h5>Auto Refresh</h5>
              </div>
              <!-- <div class="quick-button">
                <a href="{{url('editor/note/manage')}}" class="btn btn-primary btn-actions btn-create">
                Manage
                </a>
              </div> -->
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="box-content">
            <div class="row">
              <div class="col-sm-2"></div>
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="card p-5">
                    
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form role="form" action="{{url('admin/refresh/check')}}" method="POST" enctype="multipart/form-data">

                    <div class="card-header">
                      <!-- <h3 class="card-title">Enable Dashboard Table Data Auto Refresh</h3> -->
                      <div class="custom-control custom-switch">
                            <input type="checkbox" name="status" class="custom-control-input"  id="customSwitch1" {{ $old_data->status == 1 ? ' checked' : '' }}>
                            <label class="custom-control-label" for="customSwitch1">Enable Dashboard Table Data Auto Refresh</label>
                        </div>
                    </div>
                    <small class="text-muted "><i>(If enable this setting, dashboard table data refresh automatically with below mentioned time.)</i></small>

                      @csrf
                      <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1"><input type="number" class="form-control {{ $errors->has('time') ? ' is-invalid' : '' }}" value="{{$old_data->time }}" name="time" id="time"></div>
                                <div class="col-md-11">Auto Refresh time in seconds</div>
                            </div>
                            <!-- <label class="form-label mt-2 ms-2">Auto Refresh time in seconds</label> -->
                              
                               @if ($errors->has('time'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('time') }}</strong>
                                </span>
                                @endif
                        </div>
                        <!-- form group -->
                        <!-- <div class="form-group">
                          <div class="custom-label">
                            <label>Publication Status</label>
                          </div>
                          <div class="box-body pub-stat display-inline">
                              <input class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" id="active" name="status" value="1" {{ $old_data->status == 1 ? ' checked' : '' }}>
                              <label for="active">Active</label>
                              @if ($errors->has('status'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('status') }}</strong>
                              </span>
                              @endif
                          </div>
                          <div class="box-body pub-stat display-inline">
                              <input class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" name="status" value="0" id="inactive" {{ $old_data->status == 0 ? ' checked' : '' }}>
                              <label for="inactive">Inactive</label>
                              @if ($errors->has('status'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('status') }}</strong>
                              </span>
                              @endif
                          </div>
                        </div> -->

                        </hr>

                        <div class=" form-group float-right">
                            <button type="submit" class="btn btn-primary" style="background-color: #af251b; border:#af251b">Save Changes</button>
                        </div>
                        <!-- /.form-group -->
                      </div>
                      <!-- /.card-body -->
                      <!-- <div class=" form-group float-right">
                        <button type="submit" class="btn btn-primary" style="background-color: #af251b; border:#af251b">Save Changes</button>
                      </div> -->
                    </form>
                  </div>
              </div>
              <!-- col end -->
              <div class="col-sm-2"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection