@extends('admin.layouts.app')

@section('content')
    <section id="column-selectors">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Users <i class="feather icon-user"></i></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped" id="datatable">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($data as $key => $user)
                                        <tr>
                                            <td>{!! $user->name !!}</td>
                                            <td>{!! $user->email !!}</td>
                                            <td>{!! $user->email !!}</td>
                                            <td><span style="display: none">{!! strtotime($user->created_at) !!}</span>{!! date('d/m/Y H:i:A', strtotime($user->created_at)) !!}</td>
                                            <td class="text-center">
                                                <div class="form-check form-switch mb-2">
                                                    <input class="form-check-input switch-button" type="checkbox" id="isActive" data-id="{!! $user->id !!}" data-column="is_active" {!! matchChecked($user->is_active,1) !!} >
                                                    <label class="form-check-label" for="isActive"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{!! route('admin.users.show', $user->id) !!}" class="btn btn-info btn-sm waves-effect waves-light"><i class="feather icon-search"></i></a>

                                                <button type="button"
                                                        class="btn btn-danger btn-sm waves-effect waves-light"
                                                        onclick="deleteConfirmation({!! $user->id !!})"><i
                                                        class="feather icon-trash"></i></button>

                                                <form action="{!! URL::route('admin.users.destroy', $user->id) !!}" method="POST"
                                                      id="deleteForm{!! $user->id !!}">
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
            "order": [[3, "desc"]] // Sort by the fourth column (created_at) in descending order
        });

        $(document).on('change', '.switch-button', function() {
            var status = 0;
            var column = $(this).data('column');
            var id = $(this).data('id');
            if ($(this).is(':checked')) {
                status = 1;
            }
            else {
                status = 0;
            }
            updateStatus(id, status, column)
        });

        function updateStatus(id, status, column) {
            jQuery.ajax({
                url: '{{ route('admin.update-status') }}',
                type: 'POST',
                data: {
                    _token: "{!! csrf_token() !!}",
                    id,
                    status,
                    column
                },
                dataType: "json",
                success: function (data) {

                    if (data.status == false) {
                        toastr.error(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    if (data.status == true) {
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function (reject) {
                    var errors = $.parseJSON(reject.responseText);

                    if (reject.status === 422) {
                        $.each(errors.errors, function (key, val) {
                            toastr.error(val, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        });
                    }

                    toastr.error(errors.message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }
    </script>
@endsection
