@extends('backEnd.layouts.master')
@section('title','Merchant payment history')
@section('content')
<style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible !important;
  }
  #printSection {
    position:absolute !important;
    left:0;
    top:0;
  }
}
</style>
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
                          <h5>Returned To Merchant Payment</h5>
                        </div>
                      </div>
                    </div>
                    <small>The merchant who  have parcels with status return to merchant having delivery charge will show here.</small>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                    </div>
                  <div class="card-body">
                      <form action="{{url('editor/merchant/confirm-returned-payment')}}" method="POST" id="myform" class="bulk-status-form">
                      @csrf
                      
                        <input type="hidden" value="{{ request()->startDate }}" name="startDate">
                        <input type="hidden" value="{{ request()->endDate }}" name="endDate">
                      
                     <button type="submit" class="bulkbutton bulk-status-btn" onclick="return (confirm('Are sure??'))">Make Return Payment</button>
                     
                    <table id="" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                         <th><input type="checkbox"  id="My-Button"></th>
                        <th>Id</th>
                        <th>Merchant</th>
                        <th>Payment Method</th>
                        <th>Total Return Due</th>
                        <th width="100px">Action</th>
                      </tr>
                      </thead>
                      
                      <tbody>
                        @foreach($marchents as $key=>$value)
                        <tr>
                          <td><input type="checkbox"  value="{{$value->id}}" name="marchent_id[]" form="myform">  
                          </form>
                          </td>
                          <td>{{ ++$key }}</td>
                          <td>{{ $value->companyName }}</td>
                          <td>{{ $value->paymentMethod ?? 'Not Set Yeat' }}</td>
                          
                          <td>{{ $value->charge }}</td>
                          <td>
                            <ul class="action_buttons cust-action-btn">
                              <li>
                                 <a class="edit_icon anchor" href="{{ route('editor.return_invoice', $value->id) }}" title="Invoice" target="_blank"><i class="fa fa-list"></i></a>
                              </li> 
                           </ul>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
                
                {{-- <div class="py-3">{{$show_data->links()}}</div> --}}
                
            </div>
          </div>
        </div>
    </div>
  </section>

<!-- Modal Section  -->
@endsection
