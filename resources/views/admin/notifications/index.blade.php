@extends('admin.layouts.app')

@section('content')
    <section id="column-selectors">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Notifications</h4>
                        <span><a href="{{ route('admin.notification.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a></span>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped dataex-html5-selectors">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Notification Type</th>
                                            <th>Event</th>
                                            <th>Group</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $result)
                                            <tr>
                                                <td>{!! Str::limit($result->title, 20, '...') !!}</td>
                                                <td>{!! $result->topic == 'Internal' ? 'Users' : $result->topic !!}</td>
                                                <td>{!! $result->event->title ?? '' !!}</td>
                                                <td>{!! $result->group->name ?? '' !!}</td>
                                                <td><span style="display: none">{!! strtotime($result->created_at) !!}</span>{!! date('d/m/Y H:i:A', strtotime($result->created_at)) !!}</td>
                                                <td>
{{--                                                    <a href="{!! route('admin.notification.show', $result->id) !!}"--}}
{{--                                                        class="btn btn-info btn-sm waves-effect waves-light"><i--}}
{{--                                                            class="feather icon-search"></i></a>--}}
                                                    <a href="{!! route('admin.notification.show', $result->id) !!}" class="btn btn-info btn-sm waves-effect waves-light"><i class="feather icon-eye"></i></a>
                                                    {{-- <a href="{!! route('admin.notification.edit', $result->id) !!}"
                                                        class="btn btn-primary btn-sm waves-effect waves-light"><i
                                                            class="feather icon-edit"></i></a> --}}

                                                    <button type="button"
                                                        class="btn btn-danger btn-sm waves-effect waves-light"
                                                        onclick="deleteConfirmation({!! $result->id !!})"><i
                                                            class="feather icon-trash"></i></button>

                                                    <form action="{!! URL::route('admin.notification.destroy', $result->id) !!}" method="POST"
                                                        id="deleteForm{!! $result->id !!}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

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
