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

@if($errors->any())

<div class="alert alert-danger" role="alert">
    {!! implode('', $errors->all('<div>:message</div>')) !!}
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

                    <div class="col-md col-sm-12 mt-3">
                        <form method="post" action="" id="add_score_form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <input id="my-input" class="form-control @error('score') is-invalid @enderror" type="text" name="score" required placeholder="Enter number of wrong answer/s (0-20)" />
                                @error('score')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button class="btn btn-primary" id="save-score">Save</button>

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
                @role('admin')
                <!-- Prepare certificate -->
                <form method="post" action="" id="prepare-certificate-form" style="display:none;">
                    @csrf
                    <div class="form-group">
                        <label for="or_no">OR Number:</label>

                        <input id="or_no" class="form-control" type="text" name="or_no" placeholder="OR Number" required>
                    </div>
                    <div class="form-group">
                        <label for="certificate_no">Certificate Number:</label>
                        <input id="certificate_no" class="form-control" type="text" name="certificate_no" placeholder="Certificate Number" required>
                    </div>

                    <div class="form-group">
                        <label for="performance">Performance:</label>

                        <p class="form-control" id="performance"></p>

                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">Add</button>

                </form>

                <!-- Update certificate -->
                <form method="post" action="" id="edit-certificate-form" style="display:none;">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_or_no">OR Number:</label>

                        <input id="edit_or_no" class="form-control" type="text" name="edit_or_no" placeholder="OR Number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_certificate_no">Certificate Number:</label>
                        <input id="edit_certificate_no" class="form-control" type="text" name="edit_certificate_no" placeholder="Certificate Number" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_performance">Performance:</label>

                        <p class="form-control" id="edit_performance"></p>

                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">Update</button>

                </form>

                <!-- Delete Application -->
                <form method="post" action="" id="delete_application" style="display:none;">
                    @csrf
                    @method('DELETE')
                    <p>Are you sure to delete this Application? This will permanently delete the Application. Proceed?</p>
                    <button class="btn btn-danger btn-sm" type="submit">Yes</button>
                    <button class="btn btn-secondary btn-sm" type="button" onclick="closeModal()">No</button>

                </form>

                <!-- Proceed Specimen -->
                <form method="post" action="" id="proceed_specimen" style="display:none;">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <textarea placeholder="Indicate reason here" required class="form-control" name="proceed_text" id="proceed_text" rows="5"></textarea>
                    </div>

                    <button class="btn btn-primary btn-sm" type="submit">Proceed</button>
                    <button class="btn btn-danger btn-sm" type="button" onclick="closeModal()">Cancel</button>

                </form>


                @endrole
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
                        <th>Remaining Slot/s</th>
                        <th>Facility</th>
                        <th>SDTL</th>
                        <th>Cycle</th>
                        <th>Accepted Bottles</th>
                        <th>Rejected Specimen</th>
                        <th>Date Added</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td>{{$app->pt->application_limit - count($applications)}}</td>
                        <td>{{$app->user->name}}</td>
                        <td>{{$app->pt->sdtl}}</td>
                        <td>{{$app->pt->cycle}}</td>
                        <td>
                            @if($app->specimens()->latest('created_at')->first())
                            {{$app->specimens()->latest('created_at')->first()->accepted_bottles}}
                            @endif
                        </td>
                        <td>
                            @foreach($app->specimens as $specimen)
                            @if($specimen->unboxing_video_path != 'accepted' &&$specimen->unboxing_video_path != null)
                            <a href="/storage/{{$specimen->unboxing_video_path}}" class="btn btn-danger btn-sm m-1" target="_blank">{{$specimen->created_at}}</a>
                            @endif
                            @endforeach
                        </td>

                        <td>{{$app->created_at}}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <!-- certificate buttons -->

                                    <!-- admin role -->
                                    @role('admin')

                                    <!-- Proceed     -->
                                    <button class="dropdown-item btn btn-danger" onclick='handleProceedApplication("{{$app->proficiency_testing_id}}", "{{$app->id}}")'><i class="fa fa-step-forward fa-sm" aria-hidden="true"></i>

                                        Proceed</button>
                                    <!-- update cert button -->
                                    @if($app->certificate)
                                    @if(!$app->certificate->verified_by)
                                    <button class="dropdown-item btn btn-info" id="btn-edit-certificate-modal" onclick="editCert(this)" data-item="{{$app}}" type="button"><i class="fa fa-edit fa-sm"></i> Edit Certificate</button>
                                    @endif
                                    @else
                                    <!-- Prepare cert button -->
                                    @if($app->specimens()->latest('created_at')->first())
                                    @if($app->score < 9 && gettype($app->score) == 'integer' && gettype($app->specimens()->latest('created_at')->first()->accepted_bottles) != NULL || $app->specimens()->latest('created_at')->first()->accepted_bottles > 18) <button class="dropdown-item btn btn-primary" type="button" onclick="prepareCert(this)" data-item="{{$app}}"><i class="fa fa-certificate"></i> Prepare Certificate</button>
                                        @endif
                                        <!-- /Prepare cert button -->
                                        @endif
                                        @endif
                                        <!-- /update cert button -->

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
                                        @if( $app->specimens()->latest('created_at')->first()->unboxing_video_path =='accepted' && $app->result_path && gettype($app->score) != 'integer')

                                        <button class="dropdown-item btn btn-danger" type="button" onclick='handleAddScore("{{$app->proficiency_testing_id}}", "{{$app->id}}", "{{$app->result_path}}")'><i class="fas fa-tasks fa-sm"></i> View Result/Add Score </button>

                                        @endif
                                        @endif
                                        @endif
                                        @endif
                                        @endif
                                        <!-- soft delete application -->
                                        <button class="dropdown-item btn btn-danger" onclick='handleDeleteApplication("{{$app->proficiency_testing_id}}", "{{$app->id}}")'><i class="fa fa-trash fa-sm" aria-hidden="true"></i>
                                            Delete</button>
                                        @endrole

                                        <!-- /admin role -->
                                        @if($app->certificate)
                                        @if(!$app->certificate->verified_by)
                                        <!-- verifier -->
                                        @can('verify')
                                        <form method="post" action="{{route('ptApplication.verify_certificate',['id' => $app->pt->id, 'application_id' => $app->id])}}">
                                            @csrf
                                            @method('PUT')
                                            <button class=" dropdown-item btn btn-secondary" id="btn-verify-certificate-modal" type="submit" data-target="#verify-certificate-form"><i class="fa fa-check fa-sm"></i> Verify Certificate</button>
                                        </form>
                                        @endcan
                                        <!-- /verifier -->
                                        @else
                                        <!-- approve -->
                                        @can('approve')
                                        @if(!$app->certificate->approved_by)
                                        <form method="post" action="{{route('ptApplication.approve_certificate',['id' => $app->pt->id, 'application_id' => $app->id])}}">
                                            @csrf
                                            @method('PUT')
                                            <button class="dropdown-item btn btn-success" id="btn-approve-certificate-modal" type="submit" data-target="#approve-certificate-form"><i class="fa fa-bookmark fa-sm"></i> Approve Certificate</button>
                                        </form>
                                        @endif
                                        @endcan
                                        <!-- /approve -->
                                        @endif
                                        @endif



                                        <a href="{{route('proficiency-testing.applicants.showApplication', ['id' => $app->pt->id, 'application_id' => $app->id])}}" class="dropdown-item btn btn-danger" type="button"><i class="fa fa-search fa-sm"></i> View</a>


                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td>No records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('javascript')
<script>
    const btn_prepare_certificate_modal = $('#btn-prepare-certificate-modal')
    const btn_edit_certificate_modal = $('#btn-edit-certificate-modal')
    const prepare_certificate_form = $('#prepare-certificate-form')
    const edit_certificate_form = $('#edit-certificate-form')
    const add_score_form = $('#add_score_form')
    const specimen_form = $('#specimen_form')
    const delete_application = $('#delete_application')
    const proceed_specimen = $('#proceed_specimen')
    const score_form_container = $('#score_form_container')
    const specimen_form_container = $('#specimen_form_container')
    const result_path = $('#result_path')
    const close_form_modal = $('#close_form_modal')
    const modal_title = $('#modal_title')
    const modal_dialog = $('#modal-dialog')
    let formShowed
    $('#pt_table').DataTable()
    const modal = $('#custom-modal');
    let or_no = $('[name=edit_or_no]')
    let certificate_no = $('[name=edit_certificate_no]')
    let performance = $('#performance')
    let edit_performance = $('#edit_performance')

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



    const handleDeleteApplication = function(pt_id, application_id) {

        modal.modal()
        modal_dialog.addClass('modal-sm')
        modal_title.text('Delete this application?')
        delete_application.show()
        formShowed = delete_application
        delete_application.attr('action', `/proficiency-testing/${pt_id}/applicants/${application_id}/delete`);
    }

    const handleProceedApplication = function(pt_id, application_id) {

        modal.modal()
        modal_dialog.addClass('modal-sm')
        modal_title.text('Proceed Specimen')
        proceed_specimen.show()
        formShowed = proceed_specimen
        proceed_specimen.attr('action', `/proficiency-testing/${pt_id}/applicants/${application_id}/proceed_specimen`);
    }

    // btn_prepare_certificate_modal.click(function() {
    //     modal.modal()
    //     const item = $(this).data('item')
    //     const [key, value] = item.scoring
    //     const fd = new FormData();
    //     // fd.append('or_no', or_no)
    //     // fd.append('certificate_no', certificate_no)
    //     // fd.append('performance', performance)

    //     const api = `/proficiency-testing/${item.pt.id}/applicants/${item.id}/certificate/create`
    //     prepare_certificate_form.attr('action', api)
    //     modal_title.text('Prepare Certificate')
    //     performance.text(`${value} (Score: ${item.score})`)
    //     prepare_certificate_form.show()
    //     formShowed = prepare_certificate_form
    // })
    // btn_edit_certificate_modal.click(function() {
    //     modal.modal()
    //     const item = $(this).data('item')
    //     const [key, value] = item.scoring
    //     const fd = new FormData();
    //     // fd.append('or_no', or_no)
    //     // fd.append('certificate_no', certificate_no)
    //     // fd.append('performance', performance)

    //     or_no.val(item.certificate.or_no)
    //     certificate_no.val(item.certificate.certificate_no)

    //     const api = `/proficiency-testing/${item.pt.id}/applicants/${item.id}/certificate/${item.certificate.id}`
    //     edit_certificate_form.attr('action', api)
    //     modal_title.text('Edit Certificate')
    //     edit_performance.text(`${value} (Score: ${item.score})`)

    //     edit_certificate_form.show()
    //     formShowed = edit_certificate_form
    // })

    close_form_modal.click(function(e) {
        modal_title.text('')
        modal.modal('toggle')
        formShowed.hide()
        $('.modal-backdrop').hide();
    })

    $('#save-score').click(function(e) {
        e.preventDefault()
        const conf = window.confirm('Confirm number of wrong answers?')
        if (conf) add_score_form.submit()
    })

    const prepareCert = (el) => {
        modal.modal()

        const dataItem = el.getAttribute('data-item')
        const item = JSON.parse(dataItem)
        const [key, value] = item.scoring
        const fd = new FormData();
        // fd.append('or_no', or_no)
        // fd.append('certificate_no', certificate_no)
        // fd.append('performance', performance)

        const api = `/proficiency-testing/${item.pt.id}/applicants/${item.id}/certificate/create`
        prepare_certificate_form.attr('action', api)
        modal_title.text('Prepare Certificate')
        performance.text(`${value} (Score: ${item.score})`)
        prepare_certificate_form.show()
        formShowed = prepare_certificate_form

    }
    const editCert = (el) => {
        modal.modal()

        const dataItem = el.getAttribute('data-item')
        const item = JSON.parse(dataItem)
        const [key, value] = item.scoring
        const fd = new FormData();
        // fd.append('or_no', or_no)
        // fd.append('certificate_no', certificate_no)
        // fd.append('performance', performance)

        or_no.val(item.certificate.or_no)
        certificate_no.val(item.certificate.certificate_no)

        const api = `/proficiency-testing/${item.pt.id}/applicants/${item.id}/certificate/${item.certificate.id}`
        edit_certificate_form.attr('action', api)
        modal_title.text('Edit Certificate')
        edit_performance.text(`${value} (Score: ${item.score})`)

        edit_certificate_form.show()
        formShowed = edit_certificate_formconst[key, value] = item.scoring


    }
    const closeModal = function() {
        modal.modal('toggle')
    }
</script>
@endsection