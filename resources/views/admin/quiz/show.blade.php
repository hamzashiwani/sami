@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quiz Details <i class="feather icon-film"></i></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                            <li><a data-action="close"><i class="feather icon-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <div class="row" style="width: 100%;">
                                        <div class="col-6">
                                            <p><h5>Question: </h5>{!! $data->question !!}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><h5>Event: </h5>{!! $data->event->title !!}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><h5>Answer A: </h5>{!! $data->answer_a ?? '' !!}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><h5>Answer B: </h5>{!! $data->answer_b ?? '' !!}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><h5>Answer C: </h5>{!! $data->answer_c ?? '' !!}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><h5>Answer D: </h5>{!! $data->answer_d ?? '' !!}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><h5>Correct Answer: </h5>{!! $data->correct_answer ?? '' !!}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{!! route('admin.quiz.index') !!}" style="margin-left: -1rem;
                                                    margin-top: 2rem;"
                                           class="btn btn-primary waves-effect waves-light">
                                            <i class="ti ti-check me-1"></i>Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
