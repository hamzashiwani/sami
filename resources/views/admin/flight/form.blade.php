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
                                <input type="hidden" name="event_id" value="{!! $id !!}">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="user_id">Users *</label>
                                                <select class="users_single_dropdown js-states form-control" id="user_id" name="user_id" required>
                                                    <option value="">Select Users</option>
                                                    @foreach($users as $user)
                                                     <option value="{{$user->id}}" {!! $data->user_id == $user->id ? 'selected' : '' !!}>{{$user->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <section style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><h4>Departure</h4></label>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="departure_airline">Airline *</label>
                                                    <input type="text" id="departure_airline" name="departure_airline" required
                                                        value="{{ old('departure_airline', $data->departure_airline) }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="departure_flight_no">Flight No *</label>
                                                    <input type="text" id="departure_flight_no" name="departure_flight_no" required
                                                        value="{{ old('departure_flight_no', $data->departure_flight_no) }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="departure_fly_from">Fly From *</label>
                                                    <input type="text" id="departure_fly_from" name="departure_fly_from" required
                                                        value="{{ old('departure_fly_from', $data->departure_fly_from) }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="departure_takeoff_date">Takeoff Date *</label>
                                                    <input type="date" id="departure_takeoff_date" name="departure_takeoff_date"
                                                    value="{{ old('departure_takeoff_date', $data->departure_takeoff_date) }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="departure_takeoff_time">Takeoff Time *</label>
                                                    <input type="time" id="departure_takeoff_time" name="departure_takeoff_time"
                                                    value="{{ old('departure_takeoff_time', $data->departure_takeoff_time) }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="departure_destination">Destination *</label>
                                                    <input type="text" id="departure_destination" name="departure_destination"
                                                    value="{{ old('departure_destination', $data->departure_destination) }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="departure_landing_date">Landing Date *</label>
                                                    <input type="date" id="departure_landing_date" name="departure_landing_date"
                                                    value="{{ old('departure_landing_date', $data->departure_landing_date) }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="departure_landing_time">Landing Time *</label>
                                                    <input type="time" id="departure_landing_time" name="departure_landing_time"
                                                    value="{{ old('departure_landing_time', $data->departure_landing_time) }}" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </section>

                                <section style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><h4>Arrival</h4></label>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="arrival_airline">Airline *</label>
                                                    <input type="text" id="arrival_airline" name="arrival_airline" required
                                                        value="{{ old('arrival_airline', $data->arrival_airline) }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="arrival_flight_no">Flight No *</label>
                                                    <input type="text" id="arrival_flight_no" name="arrival_flight_no" required
                                                        value="{{ old('arrival_flight_no', $data->arrival_flight_no) }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="arrival_fly_from">Fly From *</label>
                                                    <input type="text" id="arrival_fly_from" name="arrival_fly_from" required
                                                        value="{{ old('arrival_fly_from', $data->arrival_fly_from) }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="arrival_takeoff_date">Takeoff Date *</label>
                                                    <input type="date" id="arrival_takeoff_date" name="arrival_takeoff_date"
                                                    value="{{ old('arrival_takeoff_date', $data->arrival_takeoff_date) }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="arrival_takeoff_time">Takeoff Time *</label>
                                                    <input type="time" id="arrival_takeoff_time" name="arrival_takeoff_time"
                                                    value="{{ old('arrival_takeoff_time', $data->arrival_takeoff_time) }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="arrival_destination">Destination *</label>
                                                    <input type="text" id="arrival_destination" name="arrival_destination"
                                                    value="{{ old('arrival_destination', $data->arrival_destination) }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="arrival_landing_date">Landing Date *</label>
                                                    <input type="date" id="arrival_landing_date" name="arrival_landing_date"
                                                    value="{{ old('arrival_landing_date', $data->arrival_landing_date) }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="arrival_landing_time">Landing Time *</label>
                                                    <input type="time" id="arrival_landing_time" name="arrival_landing_time"
                                                    value="{{ old('arrival_landing_time', $data->arrival_landing_time) }}" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </section>
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
        @if(!$data->code)
        $(document).ready(function(){
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
        })
        @endif
        $("#title").on('blur', function (){
            var value = $( this ).val();
            $('#slug').val(slugify(value));
	});

	$(".users_single_dropdown").select2({
   		placeholder: "Select User",
    		allowClear: true
	});
    </script>
@endsection
