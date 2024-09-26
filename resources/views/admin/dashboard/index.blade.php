@extends('admin.layouts.app')

@push('css')
    <style>
        .link-color {
            color: black !important;
        }
    </style>
@endpush
@section('content')
    <section id="dashboard-analytics">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ $usersCount ?? 0 }}</h2>
                        <p class="mb-0"><a href="{!! route('admin.users.index') !!}" style="color: black">Total Users</a></p>
                    </div>
                    <div class="card-content">
                        {{--                    <div id="orders-received-chart"></div>--}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ $blogCount ?? 0 }}</h2>
                        <p class="mb-0"><a href="{!! route('admin.blog.index') !!}?type=dealer" style="color: black">Total Blogs</a></p>
                    </div>
                    <div class="card-content">
                        {{--                    <div id="orders-received-chart"></div>--}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ $testimonialCount ?? 0 }}</h2>
                        <p class="mb-0"><a href="{!! route('admin.testimonial.index') !!}?type=agent" style="color: black">Total Testimonials</a></p>
                    </div>
                    <div class="card-content">
                        {{--                    <div id="orders-received-chart"></div>--}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ $pagesCount ?? 0 }}</h2>
                        <p class="mb-0"><a href="{!! route('admin.pages.index') !!}" style="color: black">Total Pages</a></p>
                    </div>
                    <div class="card-content">
                        {{--                    <div id="subscribe-gain-chart"></div>--}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{--        <div class="col-md-6 col-12">--}}
            {{--            <div class="card">--}}
            {{--                <div class="card-content">--}}
            {{--                    <div class="card-body">--}}
            {{--                        <div class="row pb-50">--}}
            {{--                            <div class="col-lg-6 col-12 d-flex justify-content-between flex-column order-lg-1 order-2 mt-lg-0 mt-2">--}}
            {{--                                <div>--}}
            {{--                                    <h2 class="text-bold-700 mb-25">2.7K</h2>--}}
            {{--                                    <p class="text-bold-500 mb-75">Avg Sessions</p>--}}
            {{--                                    <h5 class="font-medium-2">--}}
            {{--                                        <span class="text-success">+5.2% </span>--}}
            {{--                                        <span>vs last 7 days</span>--}}
            {{--                                    </h5>--}}
            {{--                                </div>--}}
            {{--                                <a href="#" class="btn btn-primary shadow">View Details <i class="feather icon-chevrons-right"></i></a>--}}
            {{--                            </div>--}}
            {{--                            <div class="col-lg-6 col-12 d-flex justify-content-between flex-column text-right order-lg-2 order-1">--}}
            {{--                                <div class="dropdown chart-dropdown">--}}
            {{--                                    <button class="btn btn-sm border-0 dropdown-toggle p-0" type="button" id="dropdownItem5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
            {{--                                        Last 7 Days--}}
            {{--                                    </button>--}}
            {{--                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownItem5">--}}
            {{--                                        <a class="dropdown-item" href="#">Last 28 Days</a>--}}
            {{--                                        <a class="dropdown-item" href="#">Last Month</a>--}}
            {{--                                        <a class="dropdown-item" href="#">Last Year</a>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                                <div id="avg-session-chart"></div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                        <hr />--}}
            {{--                        <div class="row avg-sessions pt-50">--}}
            {{--                            <div class="col-6">--}}
            {{--                                <p class="mb-0">Goal: $100000</p>--}}
            {{--                                <div class="progress progress-bar-primary mt-25">--}}
            {{--                                    <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="50" aria-valuemax="100" style="width:50%"></div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="col-6">--}}
            {{--                                <p class="mb-0">Users: 100K</p>--}}
            {{--                                <div class="progress progress-bar-warning mt-25">--}}
            {{--                                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="60" aria-valuemax="100" style="width:60%"></div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="col-6">--}}
            {{--                                <p class="mb-0">Retention: 90%</p>--}}
            {{--                                <div class="progress progress-bar-danger mt-25">--}}
            {{--                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="70" aria-valuemax="100" style="width:70%"></div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="col-6">--}}
            {{--                                <p class="mb-0">Duration: 1yr</p>--}}
            {{--                                <div class="progress progress-bar-success mt-25">--}}
            {{--                                    <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="90" aria-valuemax="100" style="width:90%"></div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Users</h4>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive mt-1">
                            <table class="table table-hover-animation mb-0">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users ?? [] as $user)
                                    <tr>
                                        <td><a href="{!! route('admin.users.show', $user->id) !!}">{!! $user->name !!}</a></td>
                                        <td>{!! $user->email !!}</td>
                                        <td>{!! $user->phone !!}</td>
                                        <td>{!! $user->is_active ? 'Active' : 'Inactive'  !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
