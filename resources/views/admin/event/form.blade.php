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
                                                       value="{{ old('title', $data->title) }}" class="form-control">
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
                                            </div>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="jobtitle">Document</label>
                                                <input type="file" name="document" id="document" class="form-control">
                                            </div>
                                            <!-- <div class="form-group d-flex">
                                                <div class="single-image position-relative">
                                                    <button id="removeImage" type="button" class="btn btn-danger position-absolute " style="display: none;border-radius:50%;padding: 5px; top: 3px; right:3px"><i class="fa fa-close"></i></button>
                                                    <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 100px;">
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <input type="hidden" name="previous_document" value="{{ $data->document }}" />
                                                
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="content">Start Date</label>
                                                <input type="date" id="date" name="date"
                                                       value="{{ old('date', $data->date) }}" class="form-control" min="{{ \Carbon\Carbon::today()->toDateString() }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="content">End Date</label>
                                                <input type="date" id="end_date" name="end_date"
                                                       value="{{ old('end_date', $data->end_date) }}" class="form-control" min="{{ \Carbon\Carbon::today()->toDateString() }}">
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
        $("#title").on('blur', function (){
            var value = $( this ).val();
            $('#slug').val(slugify(value));
        });
    </script>
@endsection
