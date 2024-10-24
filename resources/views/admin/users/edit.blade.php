@extends('admin.layouts.app')

@section('content')
<section id="number-tabs">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit User Information </h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.update', $data->id) }}" class="number-tab-steps wizard-circle" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="first_name"> Full Name *</label>
                                            <input type="text" name="name" value="{{ old('name', $data->name) }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="first_name"> Designation *</label>
                                            <input type="text" name="designation" value="{{ old('designation', $data->designation) }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="employee_id">Employee ID *</label>
                                            <input type="text" name="employee_id" value="{{ old('employee_id', $data->employee_id) }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="passport_number"> Passport Number *</label>
                                            <input type="text" name="passport_number" value="{{ old('passport_number', $data->passport_number) }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone">Contact Number</label>
                                            <input type="number" name="phone" value="{{ old('phone', $data->phone) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" value="{{  $data->email }}" class="form-control" readonly required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone">Division *</label>
                                            <input type="text" name="division" value="{{ old('division',$data->division) }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Base Town</label>
                                            <input type="text" name="base_town" value="{{ old('base_town',$data->base_town) }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                           
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Save Changes</button>
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
    $( "#page_title" ).blur(function() {
        var value = $( this ).val();
        $('#slug').val(slugify(value));
    }).blur();

    function slugify(text)
    {
      return text.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
    }
</script>
@endsection
