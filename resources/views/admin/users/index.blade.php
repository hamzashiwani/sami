@extends('admin.layouts.app')

@section('content')
    <section id="column-selectors">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Users <i class="feather icon-user"></i></h4>
                        <button style="
    margin-right: -848px;" onclick="openModal();" type="button" class="btn btn-primary">
                Import
            </button>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
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
                                        <!-- <th>Status</th> -->
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($data as $key => $user)
                                        <tr>
                                            <td>{!! $user->name !!}</td>
                                            <td>{!! $user->email !!}</td>
                                            <td>{!! $user->phone !!}</td>
                                            <td><span style="display: none">{!! strtotime($user->created_at) !!}</span>{!! date('d/m/Y H:i:A', strtotime($user->created_at)) !!}</td>
                                            <!-- <td class="text-center">
                                                <div class="form-check form-switch mb-2">
                                                    <input class="form-check-input switch-button" type="checkbox" id="isActive" data-id="{!! $user->id !!}" data-column="is_active" {!! matchChecked($user->is_active,1) !!} >
                                                    <label class="form-check-label" for="isActive"></label>
                                                </div>
                                            </td> -->
                                            <td>
                                                <a href="{!! route('admin.users.show', $user->id) !!}" class="btn btn-info btn-sm waves-effect waves-light"><i class="feather icon-search"></i></a>
                                                    <a href="{!! route('admin.users.edit', $user->id) !!}" class="btn btn-primary btn-sm waves-effect waves-light"><i class="feather icon-edit"></i></a>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Import Users</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formAdd" method="post" name="formAdd" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="file" id="file" name="file" class="form-control csv--file">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer-js')
    <script>
         function openModal() {
            $('#exampleModal').modal({backdrop: 'static',keyboard: false});
        }

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


        $(function() {
            $('#exampleModal #formAdd').on('submit', function(e) {
                e.preventDefault();
                $(':input').removeClass('has-error');
                $('.text-danger').remove();
                $(this).attr('disabled',true);
                // document.getElementById("overlay").style.display = "block";
                $.ajax({
                    url: "{{route('admin.users.import-csv')}}",
                    method:"POST",
                    data:new FormData(this),
                    contentType:false,
                    processData:false,
                    success:function(response) {
                        if(response.status == 'error'){
                            $.each(response.errors, function (k, v) {
                                $('input[name="' + k + '"]').addClass("has-error");
                                $('input[name="' + k + '"]').after("<span class='text-danger'>" + v[0] + "</span>");
                            });
                        }else if(response.status == 'fileError'){
                            $('.csv--file').addClass("has-error");
                            $('.csv--file').after("<span class='text-danger'>" + response.error + "</span>");
                        } else{
                            // document.getElementById("overlay").style.display = "none";
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Your work has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#exampleModal').modal('hide');
                            setTimeout(function () {
                                window.location.reload();
                            },1000);
                        }
                    }
                });
            });
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
