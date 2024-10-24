@extends('admin.layouts.app')

@section('content')
    <section id="column-selectors">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Groups</h4>
                        <span><a href="{{ route('admin.group.create',$id) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a></span>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped dataex-html5-selectors">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $result)
                                            <tr>
                                                <td>{!! Str::limit($result->name, 20, '...') !!}</td>
                                                <td>{!! $result->description !!}</td>
                                                <td><span style="display: none">{!! strtotime($result->created_at) !!}</span>{!! date('d/m/Y H:i:A', strtotime($result->created_at)) !!}</td>
                                                <td>
                                                    <a href="{!! route('admin.group.edit', $result->id) !!}"
                                                        class="btn btn-primary btn-sm waves-effect waves-light"><i
                                                            class="feather icon-edit"></i></a>

                                                     <a href="{!! route('admin.group.show', $result->id) !!}"
                                                        class="btn btn-primary btn-sm waves-effect waves-light"><i
                                                            class="feather icon-eye"></i></a>

                                                            <a href="#" onclick="openModal('{{$result->id}}')" class="btn btn-primary btn-sm waves-effect waves-light"><i
                                                            class="feather icon-upload"></i></a> 
                                                    <button Name="button"
                                                        class="btn btn-danger btn-sm waves-effect waves-light"
                                                        onclick="deleteConfirmation({!! $result->id !!})"><i
                                                            class="feather icon-trash"></i></button>

                                                    <form action="{!! URL::route('admin.group.destroy', $result->id) !!}" method="POST"
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Import Members</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formAdd" method="post" name="formAdd" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                    <input type="hidden" id="group_id" name="group_id" class="form-control">
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
        function openModal(id) {
            $('#group_id').val(id);
            $('#exampleModal').modal({backdrop: 'static',keyboard: false});
        }

        $(function() {
            $('#exampleModal #formAdd').on('submit', function(e) {
                e.preventDefault();
                $(':input').removeClass('has-error');
                $('.text-danger').remove();
                $(this).attr('disabled',true);
                // document.getElementById("overlay").style.display = "block";
                $.ajax({
                    url: "{{route('admin.group.import-csv',$id)}}",
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

        $('#datatable').DataTable({
            "order": [[2, "desc"]] // Sort by the fourth column (created_at) in descending order
        });
    </script>
@endsection
