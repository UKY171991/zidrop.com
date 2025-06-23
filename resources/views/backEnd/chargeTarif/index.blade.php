@extends('backEnd.layouts.master')
@section('title','Manage City Tariff')
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="box-content">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card custom-card">
                    <div class="col-sm-12">
                      <div class="manage-button">
                        <div class="body-title">
                          <h5>Manage City Tariff</h5>
                        </div>
                        <div class="quick-button">
                          <a href="{{route('admin.charge-tarif-upload')}}" class="btn btn-primary btn-actions btn-create">
                          <i class="fa fa-plus"></i> Upload Tariff
                          </a>
                        </div>
                      </div>
                    </div>
                  <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Pickup City</th>
                        <th>Delivery City</th>
                        <th>DeliveryCharge</th>
                        <th> Extra Delivery Charge</th>
                        <th>COD Charge</th>
                        <th> Tax </th>
                        <th> Insurance </th>
                        <th>Status</th>
                        
                      </tr>
                      </thead>
                        <tbody>
                            @foreach($Chargetariffs as $key=>$value)
                            <tr>
                            <td>{{$value->id}}</td>
                            <td>{{$value->pickupcity->title}}</td>
                            <td>{{$value->deliverycity->title}}</td>
                            <td>{{$value->deliverycharge}}</td>
                            <td>{{$value->extradeliverycharge}}</td>
                            <td>{{$value->codcharge}}</td>
                            <td>{{$value->tax}}</td>
                            <td>{{$value->insurance}}</td>
                            <td>{{$value->status==1? "Active":"Inactive"}}</td>
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
  </section>

<!-- Modal Section  -->




@endsection