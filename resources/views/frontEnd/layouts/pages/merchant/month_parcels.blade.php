@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@if($parceltype)
@section('title', $parceltype->title)
@else
@section('title', 'MY ALL PARCEL')
@endif
@section('content')
<div class="profile-edit mrt-30"> 
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="body-title">
                @if($parceltype)
                <h5 class="pageStatusTitle">{{ $parceltype?->title }} PARCEL</h5>
                @else
                <h5 class="pageStatusTitle">MY ALL PARCEL</h5>
                @endif
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <form action="" class="filte-form">
            @csrf
            <div class="row">
              <input type="hidden" value="1" name="filter_id">
              <div class="col-sm-2">
                <input type="text" class="form-control" placeholder="Track Id" name="trackId">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                  <input type="number" class="form-control" placeholder="Phone Number" name="phoneNumber">
              </div>
                <!-- col end -->
              <div class="col-sm-2">
                <input type="text" class="form-control" placeholder="Customer name"
                    name="cname">
             </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <button type="button" class="btn btn-success"
                id="SearchDtataButton">Submit</button>
              </div>
              <!-- col end -->
            </div>
          </form>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner ">
              <table id="example333" class="table table-bordered table-striped custom-table table-responsive">
                 <thead>
                   <tr>
                    {{-- <th>Test Id</th> --}}
                   <th>Tracking ID</th>
                   <th>More</th>
                   <th>Date</th>
                   <th>Customer</th>
                   <th>Phone</th>
                   <th>Status</th>
                   <th>Rider</th>
                   <th>Total</th>
                   <th>Charge</th>
                   <th>Cod Charge</th>
                   <th>Tax</th>
                   <th>Insurance</th>
                   <th>Sub Total</th>
                   <th>L. Update</th>
                   <th>Payment Status</th>
                   <th>Your Note</th>
                   <th>Admin Note</th>
                  
                 </tr>
                 </thead>
                <tbody>
             
                  
                </tbody>
               </table>
             </div>
        </div>
    </div>
    <!-- row end -->
</div>
@endsection
@section('custom_js_scripts')
<script>
  $(document).ready(function(event) {
      var slug = '{{ $slug }}' ?? '';
      var table33 = $('#example333').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          sorting: false,
          responsive: true,
          Xscroll: true,
          lengthMenu: [
              [10, 25, 50, -1],
              ['10 rows', '25 rows', '50 rows', 'Show all']
          ],
          ajax: {
              url: "{{ url('merchant/get_parcel_data_month') }}" + '/' + slug,
              data: {   
                   
              }
          },

          dom: 'Bfrtip',
          buttons: [
              'pageLength',
              {
                  extend: 'copy',
                  text: 'Copy',
                  exportOptions: {
                      columns: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
                      rows: function(idx, data, node) {
                          let found = false;
                          let selectedRowIndexes = table33.rows('.selected').indexes();
                          for (let index = 0; index < selectedRowIndexes.length; index++) {
                              if (idx == selectedRowIndexes[index]) {
                                  found = true;
                                  break;
                              }
                          }
                          return found;
                      }
                  }
              },
              {
                  extend: 'excel',
                  text: 'Excel',
                  exportOptions: {
                      columns: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                      rows: function(idx, data, node) {
                          let found = false;
                          let selectedRowIndexes = table33.rows('.selected').indexes();
                          for (let index = 0; index < selectedRowIndexes.length; index++) {
                              if (idx == selectedRowIndexes[index]) {
                                  found = true;
                                  break;
                              }
                          }
                          return found;
                      }
                  }
              },
              {
                  extend: 'excel',
                  text: 'D_Man',
                  exportOptions: {
                      columns: [1, 3, 4, 5, 7, 8, 10, 14],
                      rows: function(idx, data, node) {
                          let found = false;
                          let selectedRowIndexes = table33.rows('.selected').indexes();
                          for (let index = 0; index < selectedRowIndexes.length; index++) {
                              if (idx == selectedRowIndexes[index]) {
                                  found = true;
                                  break;
                              }
                          }
                          return found;
                      }
                  }
              },

              {
                  extend: 'print',
                  text: 'Print',
                  exportOptions: {
                      columns: [1, 3, 4, 5, 6, 7, 8, 9, 10],
                      rows: function(idx, data, node) {
                          let found = false;
                          let selectedRowIndexes = table33.rows('.selected').indexes();
                          for (let index = 0; index < selectedRowIndexes.length; index++) {
                              if (idx == selectedRowIndexes[index]) {
                                  found = true;
                                  break;
                              }
                          }
                          return found;
                      }
                  }
              },

              {
                  extend: 'print',
                  text: 'Print all',
                  exportOptions: {
                      columns: [1, 3, 4, 5, 6, 7, 8, 9, 10],
                      rows: function(idx, data, node) {
                          let found = true;
                          let selectedRowIndexes = table33.rows('.selected').indexes();
                          for (let index = 0; index < selectedRowIndexes.length; index++) {
                              if (idx == selectedRowIndexes[index]) {
                                  found = false;
                                  break;
                              }
                          }
                          return found;
                      }
                  }
              },
              {
                  extend: 'colvis',
              },

          ],
          createdRow: function(row, data, dataIndex) {
              // Add your desired class to each <tr> element
              $(row).addClass('data_all_trs');
          }

      });

      // Searching
      $('#SearchDtataButton').click(function() {

          var filter_id = $('input[name="filter_id"]').val();
          var trackId = $('input[name="trackId"]').val();
          var phoneNumber = $('input[name="phoneNumber"]').val();
          var startDate = $('input[name="startDate"]').val();
          var endDate = $('input[name="endDate"]').val();
          var cname = $('input[name="cname"]').val();
          var table33 = $('#example333').DataTable();
          table33.destroy();
          $('#example333').DataTable({
              processing: true,
              serverSide: true,
              searching: false,
              sorting: false,
              responsive: true,
              lengthMenu: [
                  [10, 25, 50, -1],
                  ['10 rows', '25 rows', '50 rows', 'Show all']
              ],
              ajax: {
                  url: "{{ url('merchant/get_parcel_data') }}" + '/' + slug,
                  data: {
                      filter_id: filter_id,
                      trackId: trackId,
                      phoneNumber: phoneNumber,
                      startDate: startDate,
                      endDate: endDate,
                      cname: cname,

                  }
              },

              dom: 'Bfrtip',
              buttons: [
                  'pageLength',
                  {
                      extend: 'copy',
                      text: 'Copy',
                      exportOptions: {
                          columns: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16,
                              17
                          ],
                          rows: function(idx, data, node) {
                              let found = false;
                              let selectedRowIndexes = table33.rows('.selected')
                                  .indexes();
                              for (let index = 0; index < selectedRowIndexes
                                  .length; index++) {
                                  if (idx == selectedRowIndexes[index]) {
                                      found = true;
                                      break;
                                  }
                              }
                              return found;
                          }
                      }
                  },
                  {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                          columns: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16,
                              17
                          ],
                          rows: function(idx, data, node) {
                              let found = false;
                              let selectedRowIndexes = table33.rows('.selected')
                                  .indexes();
                              for (let index = 0; index < selectedRowIndexes
                                  .length; index++) {
                                  if (idx == selectedRowIndexes[index]) {
                                      found = true;
                                      break;
                                  }
                              }
                              return found;
                          }
                      }
                  },
                  {
                      extend: 'excel',
                      text: 'D_Man',
                      exportOptions: {
                          columns: [1, 3, 4, 5, 7, 8, 10, 14],
                          rows: function(idx, data, node) {
                              let found = false;
                              let selectedRowIndexes = table33.rows('.selected')
                                  .indexes();
                              for (let index = 0; index < selectedRowIndexes
                                  .length; index++) {
                                  if (idx == selectedRowIndexes[index]) {
                                      found = true;
                                      break;
                                  }
                              }
                              return found;
                          }
                      }
                  },

                  {
                      extend: 'print',
                      text: 'Print',
                      exportOptions: {
                          columns: [1, 3, 4, 5, 6, 7, 8, 9, 10],
                          rows: function(idx, data, node) {
                              let found = false;
                              let selectedRowIndexes = table33.rows('.selected')
                                  .indexes();
                              for (let index = 0; index < selectedRowIndexes
                                  .length; index++) {
                                  if (idx == selectedRowIndexes[index]) {
                                      found = true;
                                      break;
                                  }
                              }
                              return found;
                          }
                      }
                  },

                  {
                      extend: 'print',
                      text: 'Print all',
                      exportOptions: {
                          columns: [1, 3, 4, 5, 6, 7, 8, 9, 10],
                          rows: function(idx, data, node) {
                              let found = true;
                              let selectedRowIndexes = table33.rows('.selected')
                                  .indexes();
                              for (let index = 0; index < selectedRowIndexes
                                  .length; index++) {
                                  if (idx == selectedRowIndexes[index]) {
                                      found = false;
                                      break;
                                  }
                              }
                              return found;
                          }
                      }
                  },
                  {
                      extend: 'colvis',
                  },

              ],
              createdRow: function(row, data, dataIndex) {
                  // Add your desired class to each <tr> element
                  $(row).addClass('data_all_trs');
              }

          });
      });
  });
</script>
@endsection