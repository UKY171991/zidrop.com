@extends('backEnd.layouts.master')
@section('title', 'Agent Notice')
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
                                        <h5>Agent Notice</h5>
                                    </div>
                                    <div class="quick-button">
                                        <a href="{{ url('author/merchant/manage') }}"
                                            class="btn btn-primary btn-actions btn-create">
                                            Manage User
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Agent Notice</h3>
                                    </div>
                                    <div class="profile-edit mrt-30">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <form action="{{ url('author/agent/notice-store') }}" method="POST"
                                                    name="editForm">
                                                    @csrf
                                                    <div class="form-group">
                                                      <textarea name="title" style="width:100%;" rows="10">{{ $notice->title??'' }}</textarea>
                                                    </div>
                                                    <button type="submit">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- row end -->
                                    </div>
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
