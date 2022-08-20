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
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">

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
                                    @if(!$app->specimens()->latest('created_at')->first() ) <form action="{{route('proficiency-testing.applicants.sendSpecimen', ['id' => $app->pt->id, 'application_id' => $app->id])}}" method="post">
                                        @csrf
                                        @method('PUT')

                                        <button class="dropdown-item btn btn-info" type="submit"><i class="fa fa-paper-plane fa-sm"></i> Send Specimen</button>
                                    </form>
                                    @else
                                    @if($app->specimens()->latest('created_at')->first()->unboxing_video_path != 'accepted' && $app->specimens()->latest('created_at')->first()->unboxing_video_path != null)
                                    <form action="{{route('proficiency-testing.applicants.sendSpecimen', ['id' => $app->pt->id, 'application_id' => $app->id])}}" method="post">
                                        @csrf
                                        @method('PUT')

                                        <button class="dropdown-item btn btn-info" type="submit"><i class="fa fa-paper-plane fa-sm"></i> Resend Specimen</button>
                                    </form>
                                    @else
                                    @if( $app->specimens()->latest('created_at')->first()->unboxing_video_path =='accepted' && !$app->score)

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
    const result_path = $('#result_path')
    $('#pt_table').DataTable()
    const modal = $('#custom-modal');
    const handleAddScore = function(pt_id, id, app_result_path) {
        modal.modal()
        add_score_form.attr('action', `/proficiency-testing/${pt_id}/applicants/${id}/saveScore`);
        result_path.attr('src', `/storage/${app_result_path}`)
    }
</script>
@endsection