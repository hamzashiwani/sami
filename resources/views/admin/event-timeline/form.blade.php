@extends('admin.layouts.app')

@section('content')
<style>
        .tags-list {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            padding: 5px;
            min-height: 40px;
        }
        .tag {
            background-color: #e0e0e0;
            border-radius: 5px;
            padding: 5px;
            margin: 5px;
            display: flex;
            align-items: center;
        }
        .remove-tag {
            margin-left: 5px;
            cursor: pointer;
            color: red;
        }
    </style>
    <section id="number-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{!! $form['heading'] !!}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="tag-form" method="POST" action="{!! $form['action'] !!}"
                                  class="number-tab-steps wizard-circle" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <input type="hidden" name="attendance" value="No">
                                @csrf
                                <input type="hidden" name="_method" value="{!! $form['method'] !!}">
                                <input type="hidden" name="event_id" value="{!! $id !!}">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="title">Title *</label>
                                                <input type="text" id="title" name="title"
                                                       value="{{ old('title', $data->title) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="tags">Tags *</label>
                                                <input type="text" id="tag-input" name="tags[]"
                                                    @if(old('tags'))
                                                        value="{{ is_array(old('tags')) ? implode(',', old('tags')) : old('tags') }}" 
                                                    @else
                                                        value="{{ is_array($data->tags) ? implode(',', $data->tags) : $data->tags }}" 
                                                    @endif
                                                    class="form-control">
                                                <div id="tags-list" class="tags-list">
                                                    @if($data->tags)
                                                        @foreach($data->tags as $tag)
                                                            <div class="tag">
                                                                {{ $tag }}
                                                                <span class="remove-tag" onclick="removeTag(this)">×</span>
                                                                <input type="hidden" name="tags[]" value="{{ $tag }}">
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-12">
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

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="content">Date</label>
                                                <select name="date" id="date" class="form-control">
                                                    <option value="" >Select Date</option>
                                                    @foreach($dates as $date)
                                                        <option value="{{$date}}" {!! matchSelected($date, $data->date ?? '') !!}>{{$date}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="content">Time</label>
                                                <input type="time" id="time" name="time"
                                                       value="{{ old('time', $data->time) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="jobtitle">Image</label>
                                                <input type="file" name="image" id="image" class="form-control">
                                            </div>
                                            <div class="form-group d-flex">
                                                <div class="single-image position-relative">
                                                    <button id="removeImage" type="button" class="btn btn-danger position-absolute " style="display: none;border-radius:50%;padding: 5px; top: 3px; right:3px"><i class="fa fa-close"></i></button>
                                                    <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 100px;">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="previous_image" value="{{ $data->image }}" />
                                                @if ($data->image)
                                                    <div class="avatar mr-1 avatar-xl">
                                                        <img src="{!! asset('storage/'. $data->image) !!}" alt="Page Image" title="Page Image" class="img-responsive" />
                                                    </div>
                                                @endif
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
        function showCode() {
            var selectedValue = $('#attendance').val();
            if(selectedValue == 'Yes') {
                $('.show-code').show();

                function generateRandomCode(length) {
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let result = '';
                for (let i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return result;
            }

            // Set the generated code to the input field
            const randomCode = generateRandomCode(5); // Adjust length as needed
            document.getElementById('code').value = randomCode;
            } else {
                $('.show-code').hide();
                document.getElementById('code').value = "";
            }
        }

    </script>

        <script>
        const tagInput = document.getElementById('tag-input');
        const tagsList = document.getElementById('tags-list');
        const tagForm = document.getElementById('tag-form');

        tagInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter' && tagInput.value) {
                event.preventDefault();
                addTag(tagInput.value.trim());
                tagInput.value = '';
            }
        });

        function addTag(tag) {
            // Check for duplicate tags
            if ([...tagsList.children].some(tagEl => tagEl.textContent.includes(tag))) {
                alert('Tag already exists!');
                return;
            }

            const tagElement = document.createElement('div');
            tagElement.className = 'tag';
            tagElement.textContent = tag;

            const removeTagBtn = document.createElement('span');
            removeTagBtn.textContent = '×';
            removeTagBtn.className = 'remove-tag';
            removeTagBtn.onclick = function() {
                tagsList.removeChild(tagElement);
            };

            tagElement.appendChild(removeTagBtn);
            tagsList.appendChild(tagElement);

            // Create a hidden input for the tag
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'tags[]'; // Using array notation
            hiddenInput.value = tag;
            tagForm.appendChild(hiddenInput);
        }
    </script>

        }
    </script>
@endsection
