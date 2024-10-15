@extends('admin.layouts.app')

@section('content')
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 CSS and JS -->

    <section id="number-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{!! $form['heading'] !!}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="POST" action="{!! $form['action'] !!}"
                                  class="number-tab-steps wizard-circle" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                @csrf
                                <input type="hidden" name="_method" value="{!! $form['method'] !!}">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="title">Name *</label>
                                                <input type="text" id="name" name="name"
                                                       value="{{ old('name', $data->name) }}" class="form-control">
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="slug">Slug *</label>
                                                <input type="text" id="slug" name="slug"
                                                       value="{!! old('slug', $data->slug) !!}" class="form-control">
                                            </div>
                                        </div> -->
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="short_description">Description</label>
                                                <textarea type="text" name="description" maxlength="190" class="form-control">{{ old('description', $data->description) }}</textarea>
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="long_description">Content</label>
                                                <textarea name="content" maxlength="65000" rows="5" id="content" class="form-control editor-tinymce">{{ old('content', $data->content ?? '') }}</textarea>
                                            </div>
                                        </div> -->
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="topic">Cordinator *</label>
                                                <select id="user-dropdown" name="cordinator_id" class="form-control" required>
                                                    <!-- Options will be populated by AJAX -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="topic">Users *</label>
                                                <select id="user-dropdown1" name="users[]" multiple  class="form-control" required>
                                                    @if(isset($data->id))
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" {{ $data->members->contains($user->id) ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                    @endif
                                                    <!-- Options will be populated by AJAX -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Save Changes</button>
                                    <a href="{!! $form['cancel_url'] !!}" class="btn btn-danger mr-1 mb-1 waves-effect waves-light" style="color: white">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
@section('footer-js')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#user-dropdown').select2({
            placeholder: "Select Users",
            allowClear: true
        });

        // Fetch users using AJAX
        $.ajax({
            url: "{{route('admin.get-users')}}",
            method: 'GET',
            success: function(data) {
                data.forEach(function(user) {
                    let option = new Option(user.name, user.id, false, false);
                    $('#user-dropdown').append(option);
                });
                $('#user-dropdown').trigger('change'); // Notify Select2 to refresh
            },
            error: function(err) {
                console.log(err);
            }
        });

        $('#user-dropdown1').select2({
            placeholder: "Select Users",
            allowClear: true
        });

        // Fetch users using AJAX
        $.ajax({
            url: "{{route('admin.get-users')}}",
            method: 'GET',
            success: function(data) {
                data.forEach(function(user) {
                    let option = new Option(user.name, user.id, false, false);
                    $('#user-dropdown1').append(option);
                });
                $('#user-dropdown1').trigger('change'); // Notify Select2 to refresh
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
</script>   
    <script>
        $("#title").on('blur', function (){
            var value = $( this ).val();
            $('#slug').val(slugify(value));
        });
    </script>
@endsection
