@extends('admin.layouts.app')

@section('content')
<section id="number-tabs">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add User Information <i class="feather icon-user"></i></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.store') }}" class="number-tab-steps wizard-circle" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="first_name"> Full Name *</label>
                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="first_name"> Designation *</label>
                                            <input type="text" name="designation" value="{{ old('designation') }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="employee_id"> Employee ID *</label>
                                            <input type="text" name="employee_id" value="{{ old('employee_id') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="passport_number"> Passport Number *</label>
                                            <input type="text" name="passport_number" value="{{ old('passport_number') }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone">Contact Number</label>
                                            <input type="number" name="phone" value="{{ old('phone') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email *</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone">Division *</label>
                                            <input type="text" name="division" value="{{ old('division') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Base Town</label>
                                            <input type="text" name="base_town" value="{{ old('base_town') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
<!--                             <fieldset>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="image">Profile Picture</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="is_active">Is Active</label>
                                            <select class="form-control" name="is_active">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset> -->
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
