@extends('admin.layouts.app')

@section('content')
    <section id="column-selectors">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Events</h4>
                        <span><a href="{{ route('admin.event.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a></span>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped dataex-html5-selectors">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $result)
                                            <tr>
                                                <td>{!! Str::limit($result->title, 20, '...') !!}</td>
                                                <td><span style="display: none">{!! strtotime($result->created_at) !!}</span>{!! date('d/m/Y H:i:A', strtotime($result->created_at)) !!}</td>
                                                <!-- <td><small><span class="badge badge-{!! $result->status == 'published' ? 'success' : 'danger' !!}">{!! strtoupper($result->status) !!}</span></small></td> -->
                                                <td>
                                                    @if(auth()->user()->type == 2)
                                                        <a title="Quiz" href="{!! route('admin.main-quiz.index', $result->id) !!}" 
                                                            class="btn btn-info btn-sm waves-effect waves-light">
                                                                <i class="feather icon-book-open"></i> <!-- Icon for quiz -->
                                                        </a>
                                                    @else
                                                    <a title="Group" href="{!! route('admin.group.index', $result->id) !!}" 
                                                    class="btn btn-info btn-sm waves-effect waves-light">
                                                        <i class="feather icon-grid"></i> <!-- Icon for transport -->
                                                    </a>
                                                    <a title="Transports" href="{!! route('admin.event-transport.index', $result->id) !!}" 
                                                    class="btn btn-info btn-sm waves-effect waves-light">
                                                        <i class="feather icon-truck"></i> <!-- Icon for transport -->
                                                    </a>
                                                    <a title="Flights" href="{!! route('admin.event-flight.index', $result->id) !!}" 
                                                    class="btn btn-info btn-sm waves-effect waves-light">
                                                        <i class="feather icon-send"></i> <!-- Icon for flight -->
                                                    </a>
                                                    <a title="Hotels" href="{!! route('admin.event-hotel.index', $result->id) !!}" 
                                                    class="btn btn-info btn-sm waves-effect waves-light">
                                                        <i class="feather icon-home"></i> <!-- Icon for hotel -->
                                                    </a>
                                                    <a title="Quiz" href="{!! route('admin.main-quiz.index', $result->id) !!}" 
                                                    class="btn btn-info btn-sm waves-effect waves-light">
                                                        <i class="feather icon-book-open"></i> <!-- Icon for quiz -->
                                                    </a>
                                                    <a title="Event Timeline" href="{!! route('admin.event-timeline.index', $result->id) !!}" 
                                                    class="btn btn-info btn-sm waves-effect waves-light">
                                                        <i class="feather icon-clock"></i> <!-- Icon for timeline -->
                                                    </a>
                                                    <a title="Edit" href="{!! route('admin.event.edit', $result->id) !!}" 
                                                    class="btn btn-primary btn-sm waves-effect waves-light">
                                                        <i class="feather icon-edit"></i> <!-- Icon for edit -->
                                                    </a>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-js')
    <script>
        $('#datatable').DataTable({
            "order": [[2, "desc"]] // Sort by the fourth column (created_at) in descending order
        });
    </script>
@endsection
