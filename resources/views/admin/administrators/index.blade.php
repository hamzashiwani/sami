@extends('admin.layouts.app')

@section('content')
<section id="column-selectors">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Administrators <i class="feather icon-user"></i></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-striped dataex-html5-selectors">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $admin)
                                        <tr>
                                            <td>{!! $admin->first_name !!} {!! $admin->last_name !!}</td>
                                            <td>{!! $admin->phone !!}</td>
                                            <td>{!! $admin->email !!}</td>
                                            <td>{!! ($admin->is_active > 0) ? 'Yes' : 'No' !!}</td>
                                            <td>
                                                <a href="{!! route('admin.administrators.show', $admin->id) !!}" class="btn btn-info btn-sm waves-effect waves-light"><i class="feather icon-search"></i></a>

                                            @if(auth()->user()->is_system_admin == 1 || auth()->user()->id == $admin->id)
                                                <a href="{!! route('admin.administrators.edit', $admin->id) !!}" class="btn btn-primary btn-sm waves-effect waves-light"><i class="feather icon-edit"></i></a>
                                            @else
                                                <a href="{!! route('admin.administrators.edit', $admin->id) !!}" class="btn btn-primary btn-sm waves-effect waves-light disabled"><i class="feather icon-edit"></i></a>
                                            @endif

                                            @if(auth()->user()->is_system_admin != 1)
                                                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light disabled"><i class="feather icon-trash"></i></button>
                                            @elseif (auth()->user()->is_system_admin == 1 && auth()->id() != $admin->id)
                                                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="deleteConfirmation({!! $admin->id !!})"><i class="feather icon-trash"></i></button>
                                            @endif

                                                <form action="{!! URL::route('admin.administrators.destroy', $admin->id) !!}" method="POST" id="deleteForm{!! $admin->id !!}">
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