@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Detail</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <div class="row" style="width: 100%;">
                                    <div class="col-6">
                                        <p><h5>Name: </h5>{!! $data->name ?? 'N/A' !!}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><h5>Email: </h5>{!! $data->email ?? 'N/A' !!}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><h5>Contact Number: </h5>{!! $data->phone ?? 'N/A' !!}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><h5>Designation: </h5>{!! $data->designation ?? 'N/A' !!}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><h5>Employee ID: </h5>{!! $data->employee_id ?? 'N/A' !!}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><h5>Passport Number: </h5>{!! $data->passport_number ?? 'N/A' !!}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><h5>Division: </h5>{!! $data->division ?? 'N/A' !!}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><h5>Base Town: </h5>{!! $data->base_town ?? 'N/A' !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{!! route('admin.users.index') !!}"
                               class="btn btn-primary waves-effect waves-light">
                                <i class="ti ti-check me-1"></i>Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
