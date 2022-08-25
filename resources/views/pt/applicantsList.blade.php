@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Applications List
@endsection

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> PT Applications</h1>
</div>
@if(Session::has('message'))
<div class=" alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<!-- Modal -->
<div id="custom-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal_title"></h3>
                <button class="close" id="close_form_modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="score_form_container" style="display: none;">

                    <div class="col-md-10 col-sm-12">
                        <h3>Result submitted:</h3>
                        <img class="img-thumbnail" id="result_path" src="" alt="">
                    </div>
                    <div class="col-md col-sm-12">
                        <form method="post" action="" id="add_score_form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="my-input">Add Score:</label>
                                <input id="my-input" class="form-control @error('total_amount') is-invalid @enderror" type="text" name="score" required />
                                @error('score')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button class="btn btn-primary" type="submit">Save</button>

                        </form>
                    </div>
                </div>
                <div id="specimen_form_container" style="display: none;">

                    <form action="" method="post" id="specimen_form">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="courier">Courier:</label>
                            <input id="courier" class="form-control" type="text" name="courier" placeholder="Enter courier" required />
                        </div>
                        <div class="form-group">
                            <label for="courier">Tracking number:</label>
                            <input id="courier" class="form-control" type="text" name="tracking_number" placeholder="Enter tracking number" required />
                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Content Row -->
<div class="row">
    <div class="col">
        <div class="table-responsive">
            <table class="table table-light" id="pt_table">
                <thead class="thead-light">
                    <tr>
                        <th>Facility</th>
                        <th>SDTL</th>
                        <th>Cycle</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>{{$app->user->name}}</td>
                        <td>{{$app->pt->sdtl}}</td>
                        <td>{{$app->pt->cycle}}</td>
                        <td>
                            <!-- verified payment -->
                            @if($app->verified_payment === 1)
                            <span class="badge badge-pill badge-success">Payment verified</span>
                            @endif
                            <!-- specimen sent -->
                            @if($app->specimens()->latest('created_at')->first())
                            <span class="badge badge-pill badge-info">Specimen sent</span>
                            @endif

                            @if(count($app->specimens) > 0)
                            @if($app->specimens()->latest('created_at')->first()->unboxing_video_path)
                            <span class="badge badge-pill badge-secondary">Specimen Received : {{$app->specimens()->latest('created_at')->first()->unboxing_video_path === 'accepted' ? 'Accepted' : 'Rejected'}}</span>
                            @endif
                            @endif
                            @if($app->result_path)
                            <span class="badge badge-pill badge-warning">Result sent</span>
                            @endif
                            @if($app->score)
                            <span class="badge badge-pill badge-success">Score: {{$app->score}}</span>
                            @endif
                        </td>
                        <td>{{$app->created_at}}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if($app->receipt_path )
                                    @if($app->verified_payment != 1)
                                    <form action="{{route('proficiency-testing.applicants.verifyPayment', ['id' => $app->pt->id, 'application_id' => $app->id])}}" method="post">
                                        @csrf
                                        @method('PUT')

                                        <button class="dropdown-item btn btn-primary" type="submit"><i class="fa fa-check fa-sm"></i> Verify payment</button>
                                    </form>
                                    @endif
                                    <a href="/storage/{{$app->receipt_path}}" target="_blank" class="dropdown-item btn btn-success"><i class="fas fa-receipt fa-sm"></i> View Receipt</a>
                                    @if( $app->verified_payment != 0)
                                    @if(!$app->specimens()->latest('created_at')->first() )
                                    <button class="dropdown-item btn btn-info" type="submit" onclick='handleSendSpecimen("{{$app->proficiency_testing_id}}", "{{$app->id}}")'><i class="fa fa-paper-plane fa-sm"></i> Send Specimen</button>
                                    @else
                                    @if($app->specimens()->latest('created_at')->first()->unboxing_video_path != 'accepted' && $app->specimens()->latest('created_at')->first()->unboxing_video_path != null)
                                    <button class="dropdown-item btn btn-info" type="submit" onclick='handleSendSpecimen("{{$app->proficiency_testing_id}}", "{{$app->id}}")'><i class="fa fa-paper-plane fa-sm"></i> Resend Specimen</button>

                                    @else
                                    @if( $app->specimens()->latest('created_at')->first()->unboxing_video_path =='accepted' && $app->result_path && !$app->score)

                                    <button class="dropdown-item btn btn-danger" type="button" onclick='handleAddScore("{{$app->proficiency_testing_id}}", "{{$app->id}}", "{{$app->result_path}}")'><i class="fas fa-tasks fa-sm"></i> View Result/Add Score</button>
                                    @endif
                                    @endif
                                    @endif
                                    @endif
                                    @endif

                                    <a href="{{route('proficiency-testing.applicants.showApplication', ['id' => $app->pt->id, 'application_id' => $app->id])}}" class="dropdown-item btn btn-danger" type="button"><i class="fa fa-search fa-sm"></i> View</a>


                                </div>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('javascript')
<script>
    const add_score_form = $('#add_score_form')
    const specimen_form = $('#specimen_form')
    const score_form_container = $('#score_form_container')
    const specimen_form_container = $('#specimen_form_container')
    const result_path = $('#result_path')
    const close_form_modal = $('#close_form_modal')
    const modal_title = $('#modal_title')
    const modal_dialog = $('#modal-dialog')
    let formShowed
    $('#pt_table').DataTable()
    const modal = $('#custom-modal');
    const handleAddScore = function(pt_id, id, app_result_path) {
        modal.modal()
        modal_dialog.addClass('modal-xl')
        score_form_container.show()
        formShowed = score_form_container
        add_score_form.attr('action', `/proficiency-testing/${pt_id}/applicants/${id}/saveScore`);
        result_path.attr('src', `/storage/${app_result_path}`)
    }
    const handleSendSpecimen = function(pt_id, id) {
        modal.modal()
        modal_dialog.addClass('modal-sm')
        modal_title.text('Send Specimen')
        specimen_form_container.show()
        formShowed = specimen_form_container
        specimen_form.attr('action', `/proficiency-testing/${pt_id}/applicants/${id}`);
    }

    close_form_modal.click(function(e) {
        modal_title.text('')
        modal.modal('toggle')
        formShowed.hide()
        $('.modal-backdrop').hide();
    })
</script>
@endsection