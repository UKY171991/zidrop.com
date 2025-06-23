@extends('backEnd.layouts.master')
@section('title','Upload Charge Tariff')
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="box-content">
          <div class="row">
            {{-- Show all errors --}}
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
              </div>
            @endif
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card custom-card">
                    <div class="col-sm-12">
                      <div class="manage-button">
                        <div class="body-title">
                          <h5>Upload City Tariff</h5>
                        </div>
                        <div class="quick-button">
                          <a href="{{route('admin.charge-tarif')}}" class="btn btn-primary btn-actions btn-create">
                          <i class="fa fa-plus"></i> Manage Tariff
                          </a>
                        </div>
                      </div>
                    </div>
                  <div class="card-body">
                   <form action="{{route('admin.charge-tarif-submit')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                      {{-- <label for="pickupcity">Sample Table</label> --}}
                      <a href="{{asset('charge_tarrif_sample.xlsx')}}" class="">Download Sample</a>
                    </div>
                    <div class="form-group">
                      <label for="pickupcity">Select Tariff Table(xlsx)</label>
                      <input type="file" class="form-control" id="tarriftable" name="tarriftable" >
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                      
                   </form>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
          </div>
        </div>
    </div>
  </section>

<!-- Modal Section  -->




@endsection