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
                                                <label for="question">From To *</label>
                                                <input type="text" id="from_to" name="from_to" required
                                                       value="{{ old('from_to', $data->from_to) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="event_id">Date *</label>
                                                <input type="date" id="date" name="date"
                                                value="{{ old('date', $data->date) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="room_no">Ticket No *</label>
                                                <input type="text" id="ticket_no" name="ticket_no"
                                                value="{{ old('ticket_no', $data->ticket_no) }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="room_no">Seat No *</label>
                                                <input type="text" id="seat_no" name="seat_no"
                                                value="{{ old('seat_no', $data->seat_no) }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="room_no">From Date Time *</label>
                                                <input type="datetime-local" id="from_date_time" name="from_date_time"
                                                value="{{ old('from_date_time', $data->from_date_time) }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="room_no">To Date Time *</label>
                                                <input type="datetime-local" id="to_date_time" name="to_date_time"
                                                value="{{ old('to_date_time', $data->to_date_time) }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="user_id">Users *</label>
                                                <select id="user_id" name="user_id" class="form-control" required>
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
    </script>
@endsection
