@extends('admin.layouts.app')

@section('content')
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
                                                <label for="title">Title *</label>
                                                <input type="text" id="title" name="title"
                                                       value="{{ old('title', $data->title) }}" class="form-control" required>
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
                                                <textarea type="text" name="description" maxlength="190" class="form-control" required>{{ old('description', $data->description) }}</textarea>
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
                                                <label for="topic">Topic *</label>
                                                <select id="topic" name="topic" class="form-control" required>
                                                    <option value="">Select Topic</option>
                                                    <option value="Guest" {{ ($data->topic == 'Guest') ? 'selected': '' }}>Guest</option>
                                                    <option value="Internal" {{ ($data->topic == 'Internal') ? 'selected': '' }}>Users</option>
                                                    <option value="Group" {{ ($data->topic == 'Group') ? 'selected': '' }}>Group</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="events" style="display: none;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="event_id">Events *</label>
                                                <select class="form-control" id="event_id" name="event_id">
                                                    <option value="">Select Event</option>
                                                    @foreach($events as $index => $event)
                                                     <option value="{{ $index }}">{{$event}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="groups" style="display: none;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="group_id">Groups *</label>
                                                <select class="form-control" id="group_id" name="group_id">
                                                    <option value="">Select Group</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="jobtitle">File</label>
                                                <input type="file" name="file" id="file" class="form-control">
                                            </div>
                                            <!-- <div class="form-group d-flex">
                                                <div class="single-image position-relative">
                                                    <button id="removeImage" type="button" class="btn btn-danger position-absolute " style="display: none;border-radius:50%;padding: 5px; top: 3px; right:3px"><i class="fa fa-close"></i></button>
                                                    <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 100px;">
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <input type="hidden" name="previous_image" value="{{ $data->file }}" />
                                                <input type="hidden" name="file_type" value="{{ $data->file_type }}" />
                                                @if ($data->image != '' && file_exists(uploadsDir('front'). $data->image))
                                                    <div class="avatar mr-1 avatar-xl">
                                                        <img src="{!! asset(uploadsDir('front'). $data->image) !!}" alt="Page Image" title="Page Image" class="img-responsive" />
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="content">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    @foreach(pageStatuses() as $val => $label)
                                                        <option value="{!! $val !!}" {!! matchSelected($val, $data->status ?? '') !!}>{!! $label !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> -->
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
        $("#title").on('blur', function (){
            var value = $( this ).val();
            $('#slug').val(slugify(value));
        });

        $('#topic').change(function(){
            let topic = $(this).val();
            if(topic == 'Group') {
                $('.events').show();
                $('#event_id').attr('required', true);
                $('#group_id').attr('required', true);
            } else if(topic == 'Internal') {
                $('.events').show();
                $('#event_id').attr('required', true);
                $('.groups').hide();
                $('#group_id').removeAttr('required');
            } else {
                $('.events').hide();
                $('.groups').hide();
                $('#event_id').removeAttr('required');
                $('#group_id').removeAttr('required');
            }
        });

        $('#event_id').change(async function(){
            try {
                let event_id = $(this).val();
                const response = await fetch(`{{ route('admin.group.groupsByEvent', ':event_id') }}`.replace(':event_id', event_id));
                const groups = await response.json();
                populateGroupDropdown(groups);
                if($('#topic').val() == 'Group') {
                    $('.groups').show();
                }
            } catch (error) {
                console.error('Error fetching groups:', error);
            }
        });

        function populateGroupDropdown(groups) {
            const dropdown = document.getElementById('group_id');
            dropdown.innerHTML = '<option value="">Select Group</option>'; // Reset options

            groups.forEach(group => {
                const option = document.createElement('option');
                option.value = group.id;
                option.textContent = group.name;
                dropdown.appendChild(option);
            });
        }
    </script>
@endsection
