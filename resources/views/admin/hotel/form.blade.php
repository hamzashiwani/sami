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
                                                <label for="question">Hotel Name *</label>
                                                <input type="text" id="name" name="name" required
                                                       value="{{ old('name', $data->name) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="room_no">Room No *</label>
                                                <input type="text" id="room_no" name="room_no"
                                                value="{{ old('room_no', $data->room_no) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="checkin_date">Checkin Date *</label>
                                                <input type="date" id="checkin_date" name="checkin_date"
                                                value="{{ old('checkin_date', $data->checkin_date) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="checkin_time">Checkin Time *</label>
                                                <input type="time" id="checkin_time" name="checkin_time"
                                                value="{{ old('checkin_time', $data->checkin_time) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="checkout_date">Checkout Date *</label>
                                                <input type="date" id="checkout_date" name="checkout_date"
                                                value="{{ old('checkout_date', $data->checkout_date) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="checkout_time">Checkout Time *</label>
                                                <input type="time" id="checkout_time" name="checkout_time"
                                                value="{{ old('checkout_time', $data->checkout_time) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="user_id">Users *</label>
                                                <select id="user_id" name="user_id" class="users_single_dropdown js-states form-control" required>
                                                    <option value="">Select Users</option>
                                                    @foreach($users as $user)
                                                     <option value="{{$user->id}}" {!! $data->user_id == $user->id ? 'selected' : '' !!}>{{$user->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Save</button>
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
