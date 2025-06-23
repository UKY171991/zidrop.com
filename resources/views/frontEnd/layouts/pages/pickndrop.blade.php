@extends('frontEnd.layouts.master') 
@section('title','Pick and Drop') 
@section('content')
<!-- Breadcrumb -->
<div class="breadcrumbs" style="background: #db0022;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <!-- Bread Menu -->
                    <div class="bread-menu">
                        <ul>
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><a href="">Pick and Drop</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->

<section class="section-padding">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Pick and Drop</h2>
            <div class="shape"></div>
        </div>

        <div class="row">
            <div class="content">
                <div class="contact-block">
                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="address" name="address" placeholder="PickUp Address" required="" data-error="Please enter your PickUp address" />
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <select class="form-control" name="state">
                                    <option selected="">Select State</option>
                                    @foreach($state as $item)
                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-12 mb-3">
                                <select class="form-control" name="area">
                                    
                                </select>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="note" placeholder="Note (Optional)" class="form-control" />
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="estimate" placeholder="Estimated Parcel (Optional)" class="form-control" />
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="phone" placeholder="Phone Number" class="form-control" />
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="submit-button text-left">
                                    <button class="btn btn-common" id="form-submit" type="submit">Send Request</button>
                                    <div id="msgSubmit" class="h3 text-center hidden"></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- <script type="text/javascript">
	$(document).ready(function() {
		$('select[name="state"]').on('change', function() {
			var state = $(this).val();
            alert(state)
			if (state) {
				$.ajax({
					url: "{{ url('/get-area/') }}/" + state,
					type: "GET",
					dataType: "json",
					success: function(data) {
						var d = $('select[name="area"]').empty();
						$.each(data, function(key, value) {
							$('select[name="area"]').append(
								'<option value="' + value.id + '">' + value
								.zonename + '</option>');
						});
					},
				});
			} else {
				alert('danger');
			}
		});
	});
</script> --}}
@endsection
