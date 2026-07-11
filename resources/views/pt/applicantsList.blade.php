@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Applications List
@endsection

@section('content')

<!-- Page Heading -->
<div class=" mb-4">
    <h1 class="h3 mb-0 text-gray-800"> PT Applications</h1>
    <p class="lead"><strong>Remaining Slot/s: {{$applications[0]->pt->application_limit - count($applications)}}</strong> </p>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal_title"></h3>
                <button class="close" id="close_form_modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="score_form_container" style="display: none;">
                    <div class="col-lg-8 col-md-7 col-sm-12 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong class="mb-0">Submitted Result</strong>
                                <div>
                                    <a href="" id="download-result-btn" class="btn btn-outline-secondary btn-sm mr-1" title="Download Result" download><i class="fas fa-download"></i></a>
                                    <a href="" id="open-result-btn" class="btn btn-outline-primary btn-sm" target="_blank" title="Open in new tab"><i class="fas fa-external-link-alt"></i></a>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center p-0" style="min-height:360px;">
                                <div class="w-100 embed-responsive embed-responsive-4by3 text-center position-relative">
                                    <div id="result-loader" class="py-4">
                                        <i class="fa fa-spinner fa-spin fa-2x text-muted"></i>
                                    </div>
                                    <img id="result-preview-img" class="img-fluid d-none" alt="Result preview" style="max-height:360px; object-fit:contain;" />
                                    <iframe id="result_path" class="embed-responsive-item d-none" allowfullscreen style="border:0;" src=""></iframe>
                                </div>
                            </div>
                            <div class="card-footer text-muted small">
                                <span id="result-file-meta">Preview the submitted result. Use the buttons to download or open full view.</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-5 col-sm-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Add / Edit Score</h5>
                                <p class="text-muted small">Enter the number of wrong answers (0-20). Scores help determine performance classification.</p>
                                <form method="post" action="" id="add_score_form">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="score-input" class="sr-only">Score</label>
                                        <input id="score-input" name="score" type="number" min="0" max="20" class="form-control form-control-lg @error('score') is-invalid @enderror" placeholder="Wrong answers (0-20)" required />
                                        @error('score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button class="btn btn-success btn-block btn-lg" id="save-score">Save Score</button>
                                </form>
                            </div>
                        </div>
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
                        <textarea placeholder="Indicate further instructions here" required class="form-control" name="proceed_text" id="proceed_text" rows="5"></textarea>
                    </div>

                    <button class="btn btn-primary btn-sm" type="submit">Proceed</button>
                    <button class="btn btn-danger btn-sm" type="button" onclick="closeModal()">Cancel</button>

                </form>


                @endrole
                @can('verify')
                <!-- View certificate -->
                 <div class="card">
                    <div class="card-body p-0">
                        <div class="embed-responsive embed-responsive-16by9" id="view-cert-frame" style="display:none;">
                            <p><i class="fa fa-spinner fa-spin" id="cert-container-loader" ></i></p>
                            <iframe class="embed-responsive-item" allowfullscreen id="cert-container" style="display:none;"></iframe>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form method="post" id="verify_certificate_form">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-sm btn-primary" disabled id="btn-verify-certificate-modal" type="submit" data-target="#verify-certificate-form" > Verify Certificate</button>
                        </form>

                    </div>
                 </div>

                @endcan

                <!-- Manage Receipt (redesigned) -->
                <div class="card" id="view-receipt-frame" style="display:none;">
                    <div class="card-body">
                        <div class="row no-gutters">
                            <div class="col-md-7 d-flex align-items-center justify-content-center border-right" style="min-height:260px;">
                                <div id="receipt-preview-container" class="w-100 text-center">
                                    <div id="receipt-loader" class="py-4">
                                        <i class="fa fa-spinner fa-spin fa-2x text-muted"></i>
                                    </div>
                                    <img id="receipt-preview-img" src="" alt="Receipt preview" class="img-fluid d-none" style="max-height:400px; object-fit:contain;">
                                    <iframe id="receipt-preview-iframe" src="" class="w-100 d-none" style="min-height:300px;border:0;"></iframe>
                                    <div id="receipt-preview-fallback" class="text-muted small mt-2 d-none">Cannot preview this file. Use download to open it.</div>
                                </div>
                            </div>
                            <div class="col-md-5 p-3">
                                <p class="mb-2"><strong>Status:</strong> <span id="receipt-status" class="badge badge-secondary">Pending</span></p>

                                <div class="mt-3">
                                    <form method="post" id="verify_receipt_form" class="d-inline-block w-100 mb-2">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-block" disabled id="btn-verify-receipt-modal" type="submit"> Verify Receipt</button>
                                    </form>

                                    <button class="btn btn-danger btn-block" disabled id="btn-reject-receipt-modal" onclick="handleRejectReceipt()" type="button"> Reject Receipt</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="reject-receipt-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal_title">Confirm Receipt Reject</h3>
                <button class="close" id="close_form_modal" onclick="closeRejectReceiptModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="reject_receipt_form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                      <label for="reject_reason">Reason for rejection:</label>
                      <textarea class="form-control" name="reject_reason" id="reject_reason" rows="5" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-secondary" id="cancel-reject-receipt-modal" onclick="closeRejectReceiptModal()" type="button" > Cancel</button>
                    <button class="btn btn-sm btn-danger" id="btn-reject-receipt-modal" type="submit" data-target="#reject-receipt-form" > Reject Receipt</button>
                </form>
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
                        <th>Batch</th>
                        <th>Accepted Bottles</th>
                        <th>Rejected Specimen</th>
                        <th>Date Added</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>

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


                                    <!-- update cert button -->
                                    @if($app->certificate)
                                    @if(!$app->certificate->verified_by)
                                    <button class="dropdown-item btn btn-info" id="btn-edit-certificate-modal" onclick="editCert(this)" data-item="{{$app}}" type="button"><i class="fa fa-edit fa-sm"></i> Edit Certificate</button>
                                    @endif
                                    @else
                                    <!-- Prepare cert button -->
                                    @if($app->specimens()->latest('created_at')->first())
                                    @if($app->score < 9 && gettype($app->score) == 'integer' && gettype($app->specimens()->latest('created_at')->first()->accepted_bottles) != NULL || $app->specimens()->latest('created_at')->first()->accepted_bottles > 18) <button class="dropdown-item btn btn-primary" type="button" onclick="prepareCert(this)" data-item="{{$app}}"><i class="fa fa-certificate fa-sm"></i> Prepare Certificate</button>
                                        @endif
                                        <!-- /Prepare cert button -->
                                        @endif
                                        @endif
                                        <!-- /update cert button -->

                                        @if($app->receipt )
                                        @if($app->receipt->status_id != 1)
                                            
                                            <button class="dropdown-item btn btn-info" type="submit" onclick="handleManageReceipt('{{$app->proficiency_testing_id}}', '{{$app->id}}', '{{ $app->receipt->file_path }}')"><i class="fa fa-receipt fa-sm"></i> Manage Receipt</button>
                                        @endif                                        
                                        @if( $app->receipt->status_id == 1)
                                        @if(!$app->specimens()->latest('created_at')->first() )
                                        <button class="dropdown-item btn btn-info" type="submit" onclick='handleSendSpecimen("{{$app->proficiency_testing_id}}", "{{$app->id}}")'><i class="fa fa-paper-plane fa-sm"></i> Send Specimen</button>
                                        @else
                                        @if($app->specimens()->latest('created_at')->first()->unboxing_video_path != 'accepted' && $app->specimens()->latest('created_at')->first()->unboxing_video_path != null)
                                        <!-- Proceed     -->
                                        <button class="dropdown-item btn btn-danger" onclick='handleProceedApplication("{{$app->proficiency_testing_id}}", "{{$app->id}}")'><i class="fa fa-step-forward fa-sm" aria-hidden="true"></i>

                                            Proceed</button>
                                        <button class="dropdown-item btn btn-info" type="submit" onclick='handleSendSpecimen("{{$app->proficiency_testing_id}}", "{{$app->id}}")'><i class="fa fa-paper-plane fa-sm"></i> Resend Specimen</button>

                                        @else
                                        @if( $app->specimens()->latest('created_at')->first()->unboxing_video_path =='accepted')
                                        @if($app->result_path )
                                        @if(gettype($app->score) != 'integer')
                                        <button class="dropdown-item btn btn-danger" type="button" onclick='handleAddScore("{{$app->proficiency_testing_id}}", "{{$app->id}}", "{{$app->result_path}}")'><i class="fas fa-tasks fa-sm"></i> View Result/Add Score </button>
                                        @else
                                        <button class="dropdown-item btn btn-danger" type="button" onclick='handleAddScore("{{$app->proficiency_testing_id}}", "{{$app->id}}", "{{$app->result_path}}", "{{$app->score}}")'><i class="fas fa-edit fa-sm"></i> Edit Score </button>
                                        @endif
                                        @endif
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
                                        <button class=" dropdown-item btn btn-secondary" id="btn-preview-certificate-modal" type="button" data-target="#preview-certificate" onclick="handlePreviewCertificate('{{$app->proficiency_testing_id}}', '{{$app->id}}', '{{ $app->certificate->key }}')"><i class="fa fa-certificate fa-sm"></i> Preview Certificate</button>
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
    const resultPreviewImg = $('#result-preview-img')
    const resultLoader = $('#result-loader')
    const download_result_btn = $('#download-result-btn')
    const open_result_btn = $('#open-result-btn')
    const close_form_modal = $('#close_form_modal')
    const modal_title = $('#modal_title')
    const modal_dialog = $('.modal-dialog')

    // cert
    const viewCertFrame = $('#view-cert-frame');
    const certContainer = $('#cert-container');
    const certContainerLoader = $('#cert-container-loader');
    const verify_certificate_form = $('#verify_certificate_form');
    const btn_verify_certificate_modal = $('#btn-verify-certificate-modal');
    // verify receipt (redesigned preview elements)
    const viewReceiptFrame = $('#view-receipt-frame');
    const receiptPreviewImg = $('#receipt-preview-img');
    const receiptPreviewIframe = $('#receipt-preview-iframe');
    const receiptLoader = $('#receipt-loader');
    const receiptPreviewFallback = $('#receipt-preview-fallback');
    const receiptFilenameEl = $('#receipt-filename');
    const receiptUploadedAt = $('#receipt-uploaded-at');
    const receiptStatusEl = $('#receipt-status');
    const receiptDownloadLink = $('#receipt-download-link');
    const receiptOpenLink = $('#receipt-open-link');
    const verify_receipt_form = $('#verify_receipt_form');
    const btn_verify_receipt_modal = $('#btn-verify-receipt-modal');

    // reject receipt
    const reject_receipt_form = $('#reject_receipt_form');
    const btn_reject_receipt_modal = $('#btn-reject-receipt-modal');

    let formShowed
    $('#pt_table').DataTable({
        order: [[5, 'desc']]

    })
    const modal = $('#custom-modal');
    const rejectReceiptModal = $('#reject-receipt-modal');
    let or_no = $('[name=edit_or_no]')
    let certificate_no = $('[name=edit_certificate_no]')
    let performance = $('#performance')
    let edit_performance = $('#edit_performance')
    let scoreInput = $('[name=score]')
    const save_score_btn = $('#save-score')

    modal.on('hide.bs.modal', function(e) {
        modal_title.text('')
        formShowed.hide()
        $('.modal-backdrop').hide();
    })

    const handleAddScore = function(pt_id, id, app_result_path, score) {
        modal.modal()
        modal_dialog.addClass('modal-lg')
        modal_title.text('Result submitted')

        score_form_container.show()
        formShowed = score_form_container
        scoreInput.val(score)
        add_score_form.attr('action', `/proficiency-testing/${pt_id}/applicants/${id}/saveScore`);
        save_score_btn.text(score ? 'Update' : 'Save')

        // prepare preview and buttons
        const url = `/storage/${app_result_path}`;
        download_result_btn.attr('href', url)
        open_result_btn.attr('href', url)

        // reset preview
        resultPreviewImg.addClass('d-none').attr('src', '');
        result_path.addClass('d-none').attr('src', '');
        resultLoader.show();

        // determine file type by extension
        const filename = app_result_path.split('/').pop() || '';
        const ext = (filename.split('.').pop() || '').toLowerCase();
        const imageExts = ['jpg','jpeg','png','gif','webp','bmp'];

        if (imageExts.indexOf(ext) !== -1) {
            // show image preview
            resultPreviewImg.one('load', function(){
                resultLoader.hide();
                resultPreviewImg.removeClass('d-none').show();
            }).attr('src', url);
        } else if (ext === 'pdf') {
            // show pdf in iframe
            result_path.one('load', function(){
                resultLoader.hide();
                result_path.removeClass('d-none').show();
            }).attr('src', url);
        } else {
            // fallback: try iframe
            result_path.one('load', function(){
                resultLoader.hide();
                result_path.removeClass('d-none').show();
            }).attr('src', url);
        }
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
    
    const handlePreviewCertificate = function(pt_id, application_id, key){
        certContainer.hide()
        modal.modal()
        modal_title.text('Preview Certificate')
        viewCertFrame.show()
        formShowed = viewCertFrame
        certContainer.attr('src', `/certificate/${key}`)
        verify_certificate_form.attr('action', `/proficiency-testing/${pt_id}/applicants/${application_id}/certificate/verify`);
        // /proficiency-testing/{id}/applicants/{application_id}/certificate/verify
        certContainer.on('load', function(){
            certContainer.show()
            btn_verify_certificate_modal.prop("disabled", false)
        })
    }
    const handleManageReceipt = function(pt_id, application_id, path){
        // Reset preview
        receiptPreviewImg.addClass('d-none').attr('src', '');
        receiptPreviewIframe.addClass('d-none').attr('src', '');
        receiptPreviewFallback.addClass('d-none');
        receiptLoader.show();
        receiptFilenameEl.text('');
        receiptUploadedAt.text('');
        receiptStatusEl.removeClass('badge-success badge-danger badge-warning badge-secondary').addClass('badge-secondary').text('Pending');
        receiptDownloadLink.attr('href', `/storage/${path}`);
        receiptOpenLink.attr('href', `/storage/${path}`);

        modal.modal();
        modal_dialog.removeClass('modal-sm modal-lg').addClass('modal-md');
        modal_title.text('Manage Receipt');
        viewReceiptFrame.show();
        formShowed = viewReceiptFrame;

        verify_receipt_form.attr('action', `/proficiency-testing/${pt_id}/applicants/${application_id}/verifyPayment`);
        reject_receipt_form.attr('action', `/proficiency-testing/${pt_id}/applicants/${application_id}/rejectPayment`);

        // Show filename
        const filename = path.split('/').pop();
        receiptFilenameEl.text(filename);

        const ext = (filename.split('.').pop() || '').toLowerCase();
        const imageExts = ['jpg','jpeg','png','gif','webp','bmp'];
        if (imageExts.indexOf(ext) !== -1) {
            receiptPreviewImg.one('load', function(){
                receiptLoader.hide();
                receiptPreviewImg.removeClass('d-none').show();
                btn_verify_receipt_modal.prop('disabled', false);
                btn_reject_receipt_modal.prop('disabled', false);
            }).attr('src', `/storage/${path}`);
        } else if (ext === 'pdf') {
            receiptPreviewIframe.one('load', function(){
                receiptLoader.hide();
                receiptPreviewIframe.removeClass('d-none').show();
                btn_verify_receipt_modal.prop('disabled', false);
                btn_reject_receipt_modal.prop('disabled', false);
            }).attr('src', `/storage/${path}`);
        } else {
            // cannot preview
            receiptLoader.hide();
            receiptPreviewFallback.removeClass('d-none');
            btn_verify_receipt_modal.prop('disabled', false);
            btn_reject_receipt_modal.prop('disabled', false);
        }
    }

    const handleRejectReceipt = function(){
        modal.modal('toggle')
        rejectReceiptModal.modal()

    }
    const closeRejectReceiptModal = function(){
        rejectReceiptModal.modal('toggle')
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