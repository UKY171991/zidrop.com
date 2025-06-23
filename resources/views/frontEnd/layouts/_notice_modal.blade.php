@if(!empty($globNotice))
    <!-- The Modal -->
    <div class="modal fade-scale" id="globalNoticeModal">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content p-0">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{ $globNotice->title }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {!! $globNotice->text !!}
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif