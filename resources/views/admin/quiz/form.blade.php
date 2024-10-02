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
                                                <label for="question">Question *</label>
                                                <input type="text" id="question" name="question" required
                                                       value="{{ old('question', $data->question) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="event_id">Code *</label>
                                                <input readonly type="text" id="code" name="code"
                                                value="{{ old('code', $data->code) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="answer_a">Answer A *</label>
                                                <input type="text" id="answer_a" name="answer_a" required
                                                       value="{!! old('answer_a', $data->answer_a) !!}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="answer_b">Answer B *</label>
                                                <input type="text" id="answer_b" name="answer_b" required
                                                       value="{!! old('answer_b', $data->answer_b) !!}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="answer_c">Answer C *</label>
                                                <input type="text" id="answer_c" name="answer_c" required
                                                       value="{!! old('answer_c', $data->answer_c) !!}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="answer_d">Answer D *</label>
                                                <input type="text" id="answer_d" name="answer_d" required
                                                       value="{!! old('answer_d', $data->answer_d) !!}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="correct_answer">Correct Answer *</label>
                                                <select id="correct_answer" name="correct_answer" class="form-control" required>
                                                    <option value="">Select Correct Answer</option>
                                                    <option value="answer_a" {!! $data->correct_answer == 'answer_a' ? 'selected' : '' !!}>A</option>
                                                    <option value="answer_b" {!! $data->correct_answer == 'answer_b' ? 'selected' : '' !!}>B</option>
                                                    <option value="answer_c" {!! $data->correct_answer == 'answer_c' ? 'selected' : '' !!}>C</option>
                                                    <option value="answer_d" {!! $data->correct_answer == 'answer_d' ? 'selected' : '' !!}>D</option>
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
