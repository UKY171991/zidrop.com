@extends('backEnd.layouts.master')
@section('title','Manage Merchant Payment')
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
                          <h4><b><p style="color:green">Manage Merchant Payment</P></b></h4>
                        </div>
                      </div>
                    </div>
                  <div class="card-body">
                      
                    <table id="Paymentinvoice" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Done By</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Total Invoice</th>
                        <th>Total Payment(BDT)</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                          
                        @foreach($merchantInvoice as $key=>$value)
                        <tr>
                           
                          <td><p style="font-size:25px">{{$loop->iteration}}</p></td>
                          <td><p style="font-size:25px">
                             
                              @php 
                              $m=DB::table('merchantpayments')->where('updated_at',$value->updated_at)->get();
                               @endphp
                               @foreach($m as $key=>$n)
                               @php
                               if($key>0){
                               continue; 
                               }
                               @endphp
                              {{$n->done_by}}
                              @endforeach
                             
                          </p></td>
                          
                          <td><p style="font-size:25px">{{date('d M Y', strtotime($value->updated_at))}}</p></td>
                          <td><p style="font-size:25px">{{date("g:i a", strtotime($value->updated_at))}}</p></td>
                          <td><p style="font-size:25px">{{$value->total_parcel}}</p></td>
                          <td><p style="font-size:25px">{{$value->total}}</p></td>


                          <td>
                                <ul>
                                    <li>
                                      <form action="{{ url('author/merchant/payment/invoice-details') }}" method="post">
                                        @csrf
                                        <input type="hidden" value="{{ $value->updated_at }}" name="update">
                                        <input type="hidden" name="merchant_id" value="{{ request()->id }}">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-eye"></i> View</button>
                                    </form>
                                    </li>
                              </ul>
                        </td>




                          
                        </tr>
                        @endforeach
                      </tfoot>
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



<script>
       $(document).ready(function() {
          $('#Paymentinvoice').DataTable( {
              dom: 'Bfrtip',
              "lengthMenu": [[ 10, 20, 50, -1], [ 10, 20, 50, "All"]],
              buttons: [
                  {
                      extend: 'copy',
                      text: 'Copy',
                      exportOptions: {
                           columns: [0, 1, 2, 3, 4]
                      }
                  },
                  {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4]
                      }
                  },
                  {
                      extend: 'csv',
                      text: 'Csv',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4]
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      exportOptions: {
                           columns: [  0,1, 2, 3, 4]
                      }
                  },
                  
                  {
                      extend: 'print',
                      text: 'Print',
                      exportOptions: {
                           columns: [  0,1, 2, 3, 4]
                      }
                  },
                  {
                      extend: 'print',
                      text: 'Print all',
                      exportOptions: {
                          modifier: {
                              selected: null
                          }
                      }
                  },
                  {
                      extend: 'colvis',
                  },
                  
              ],
              select: true
          } );
          
           table.buttons().container()
              .appendTo( '#example_wrapper .col-md-6:eq(0)' );
      });
</script>





@endsection