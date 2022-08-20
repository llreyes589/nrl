@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Program Application
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> @role('admin'){{$application->user->name}} <br>@endrole PT Application <u>(SDTL-{{$pt->sdtl}} | Cycle-{{$pt->cycle}})</u></h1>
    @role('Facility')
    <a href="{{route('proficiency-testing.facility.index')}}" class="btn btn-danger">Cancel</a>
    @else
    <a href="{{route('proficiency-testing.applicants', $pt->id)}}" class="btn btn-danger">Cancel</a>
    @endrole

</div>
@if(Session::has('message'))
<div class=" alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<?php
if ($application)
    $test_method = json_decode($application->test_method_used, true);
// dd($test_method);
?>

<!-- MODAL -->
<div class="modal" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true" id="form-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title"></h5>
                <button class="close" id="close-form-modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- upload-receipt-form -->
                <form method="POST" action="{{route('proficiency-testing.facility.saveReceipt', $pt->id)}}" enctype="multipart/form-data" id="upload-receipt-form" style="display:none;">
                    @csrf
                    @method("PUT")
                    <div class="form-group row">
                        <label for="receipt" class="col-md-4 col-form-label text-md-right">{{ __('Receipt') }}</label>

                        <div class="col-md-6">
                            <input id="receipt" type="file" class="form-control-file" name="receipt">
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>


                <!-- receive-specimen-form -->
                <form method="POST" action="{{route('proficiency-testing.facility.receiveSpecimen', $pt->id)}}" enctype="multipart/form-data" id="receive-specimen-form" style="display:none;">
                    @csrf
                    @method("PUT")
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="accept" name="status" value="accept" required>
                        <label class="custom-control-label" for="accept">Accept</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="reject" name="status" value="reject" required>
                        <label class="custom-control-label" for="reject">Reject</label>
                    </div>
                    <hr>
                    <div class="form-group row" id="unboxing_video_path_container" style="display: none;">
                        <label for="unboxing_video_path" class="col-md-4 col-form-label text-md-right">{{ __('Attach Video/Image') }}</label>

                        <div class="col-md-6">
                            <input id="unboxing_video_path" type="file" class="form-control-file" name="unboxing_video_path">
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>

                <!-- send-result-form -->
                <form method="POST" action="{{route('proficiency-testing.facility.saveResult', $pt->id)}}" enctype="multipart/form-data" id="send-result-form" style="display:none;" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="form-group row">
                        <label for="result" class="col-md-4 col-form-label text-md-right">{{ __('Result') }}</label>

                        <div class="col-md-6">
                            <input id="result" type="file" class="form-control-file" name="result" required>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Send
                            </button>
                        </div>
                    </div>
                </form>


                @if($application)
                <!-- Prepare certificate -->

                <form method="post" action="{{route('ptApplication.create_certificate', ['id' => $pt->id, 'application_id' => $application->id])}}" id="prepare-certificate-form" style="display:none;">
                    @csrf
                    <div class="form-group">
                        <label for="or_no">OR Number:</label>

                        <input id="or_no" class="form-control" type="text" name="or_no" placeholder="OR Number" required>
                    </div>
                    <div class="form-group">
                        <label for="or_no">Certificate Number:</label>
                        <input id="or_no" class="form-control" type="text" name="certificate_no" placeholder="Certificate Number" required>
                    </div>
                    <div class="form-group">
                        <label for="validity">Validity</label>
                        <input id="validity" class="form-control" type="date" name="validity" required>
                    </div>
                    <div class="form-group">
                        <label for="performance">Performance</label>
                        <select id="performance" class="custom-select" name="performance" required>
                            <option value="">--Please select performance here--</option>
                            <option value="A">Acceptable</option>
                            <option value="E">Excellent</option>
                            <option value="HS">Highly Satisfactory</option>
                        </select>
                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">Add</button>

                </form>

                @if($application->certificate)
                <!-- View certificate -->
                <div class="embed-responsive embed-responsive-16by9" id="view-cert-frame" style="display:none;">
                    <iframe class="embed-responsive-item" allowfullscreen src="{{route('certificate', $application->certificate->key)}}"></iframe>
                </div>
                @endif
                @endif
            </div>

        </div>
    </div>
</div>
<!-- END MODAL -->



<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 for="cycle">PT Details</h4>
                @if($application)
                <span class="badge badge-pill badge-success">Applied</span>
                @if($application->receipt_path)
                <span class="badge badge-pill badge-primary">Receipt Uploaded</span>
                @endif
                @if($application->specimens()->latest('created_at')->first())
                <span class="badge badge-pill badge-info">Specimen Sent</span>
                @endif
                @if(count($application->specimens) > 0)
                @if($application->specimens()->latest('created_at')->first()->unboxing_video_path)
                <span class="badge badge-pill badge-secondary">Specimen Received : {{$application->specimens()->latest('created_at')->first()->unboxing_video_path == 'accepted' ? 'Accepted' : 'Rejected'}}</span>
                @endif
                @endif
                @if($application->result_path)
                <span class="badge badge-pill badge-warning">Result sent</span>
                @endif
                @endif
                <hr>
                <div class="form-group">
                    <label for="sdtl">SDTL</label>
                    <input type="text" class="form-control" readonly value="{{$pt->sdtl}}" />
                </div>
                <div class="form-group">
                    <label for="cycle">Cycle</label>
                    <input type="text" class="form-control" readonly value="{{$pt->cycle}}" />
                </div>
                <hr />
                @role('Facility')
                @if($application)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <!-- download instruction -->
                        @if($pt->instruction_file_path)
                        <a href="/storage/{{$pt->instruction_file_path}}" class="dropdown-item btn btn-success" download>
                            <i class="fa fa-download fa-sm"></i> Download instructions
                        </a>
                        @endif

                        <!-- upload receipt -->
                        <button class="dropdown-item btn btn-info" id="btn-upload-receipt-modal" type="button" data-target="#upload-receipt-form"><i class="fa fa-upload fa-sm"></i> Upload Receipt</button>

                        <!-- specimen -->
                        @if($application->specimens()->latest('created_at')->first())
                        @if(!$application->specimens()->latest('created_at')->first()->unboxing_video_path)
                        <button class="dropdown-item btn btn-info" id="btn-receive-specimen-modal" type="button" data-target="#receive-specimen-form"><i class="fab fa-get-pocket fa-sm"></i> Receive specimen</button>
                        @else

                        @if($application->specimens()->latest('created_at')->first()->unboxing_video_path =='accepted' && !$application->result_path)
                        <button class="dropdown-item btn btn-info" id="btn-send-result-modal" type="button" data-target="#send-result-form"><i class="fa fa-paper-plane fa-sm"></i> Send result</button>
                        @endif
                        @endif
                        @if($application->certificate)
                        @if($application->certificate->approved_by)
                        <button class="dropdown-item btn btn-success" id="btn-view-certificate-modal" type="submit" data-target="#view-cert-frame"><i class="fa fa-certificate fa-sm"></i> View Certificate</button>
                        @endif
                        @endif
                        @endif
                    </div>
                </div>
                @endif
                @else

                @if($application->certificate)

                @can('verify')
                @if(!$application->certificate->verified_by)
                <form method="post" action="{{route('ptApplication.verify_certificate',['id' => $pt->id, 'application_id' => $application->id])}}">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-secondary" id="btn-verify-certificate-modal" type="submit" data-target="#verify-certificate-form">Verify Certificate</button>
                </form>
                @endif
                @endcan

                @can('approve')
                @if(!$application->certificate->approved_by)
                <form method="post" action="{{route('ptApplication.approve_certificate',['id' => $pt->id, 'application_id' => $application->id])}}">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success" id="btn-approve-certificate-modal" type="submit" data-target="#approve-certificate-form">Approve Certificate</button>
                </form>
                @endif
                @endcan
                @if($application->certificate->approved_by)
                <button class="btn btn-success" id="btn-view-certificate-modal" type="submit" data-target="#view-cert-frame">View Certificate</button>
                @endif
                @else
                <button class="btn btn-primary" id="btn-prepare-certificate-modal" type="button" data-target="#prepare-certificate-form">Prepare Certificate</button>
                @endif
                @endrole
            </div>
        </div>

        @if($application )
        <div class="card mt-2">
            <div class="card-body">
                <h5 class="card-title">Uploaded File/s:</h5>
                <hr>
                @if($application->receipt_path)
                <p>Receipt</p>
                <img src="/storage/{{$application->receipt_path}}" class="img-fluid" alt="">
                <hr>
                @endif
                @if($application->result_path)
                <p>Result:</p>
                <img src="/storage/{{$application->result_path}}" class="img-fluid" alt="">
                @endif
            </div>
        </div>
        @endif
    </div>
    <div class="col-md col-sm-12">
        <div class="card">
            <div class="card-body">
                <form method="post" id='form' action="">
                    @csrf
                    <h4 for="cycle">Test Method Used</h4>
                    <hr>
                    <p class="lead">Immunoassay Test Kit</p>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="my-addon">Brand</span>
                        </div>
                        @if($application)
                        <div class="form-control">{{$test_method[0]['immunoassay_brand']}} </div>
                        @else
                        <input class="form-control" type="text" name="immunoassay_brand" placeholder="Enter brand here" aria-label="Recipient's " aria-describedby="my-addon">
                        @endif
                    </div>
                    <br>
                    <p class="lead">Instrumented</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="my-addon">Type of Instrument used</span>
                        </div>
                        @if($application)
                        <div class="form-control">{{$test_method[1]['instrument_type']}} </div>
                        @else
                        <input class="form-control" type="text" name="instrument_type" placeholder="Enter Type of Instrument here" aria-label="Recipient's " aria-describedby="my-addon">
                        @endif
                    </div>
                    <br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="my-addon">Brand</span>
                        </div>
                        @if($application)
                        <div class="form-control">{{$test_method[1]['instrument_brand']}} </div>
                        @else
                        <input class="form-control" type="text" name="instrument_brand" placeholder="Enter brand here" aria-label="Recipient's " aria-describedby="my-addon">
                        @endif
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="cutoff">Cut Off Value for Method </label>
                        @if($application)
                        <textarea id="cutoff" class="form-control" name="cutoff_value" rows="3" readonly>{{$application->cutoff_value}}</textarea>
                        @else
                        <textarea id="cutoff" class="form-control" name="cutoff_value" rows="3"></textarea>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="cutoff">Methamphetamine (METH) </label>
                        @if($application)
                        <textarea id="methamphetamine" class="form-control" name="methamphetamine" rows="3" readonly>{{$application->methamphetamine}}</textarea>
                        @else
                        <textarea id="methamphetamine" class="form-control" name="methamphetamine" rows="3"></textarea>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="cutoff">Tetrahydrocannabinol (THC) </label>
                        @if($application)
                        <textarea id="tetrahydrocannabinol" class="form-control" name="tetrahydrocannabinol" rows="3" readonly>{{$application->tetrahydrocannabinol}}</textarea>
                        @else
                        <textarea id="tetrahydrocannabinol" class="form-control" name="tetrahydrocannabinol" rows="3"></textarea>
                        @endif
                    </div>
                    <div class="alert alert-primary" role="alert">
                        <p class="lead p-0 m-0">Total amount: <span class="font-weight-bold">P{{$pt->total_amount}}</span></p>
                    </div>
                    <hr />
                    @if(!$application)
                    <button class="btn btn-primary" id="submit" type="submit">Apply/Checkout</button>
                    @endif
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@section('javascript')
<script>
    $(function() {
        const status = $('[name="status"]')
        const unboxing_video_path_container = $('#unboxing_video_path_container')
        const unboxing_video_path_input = $('#unboxing_video_path')
        status.change(function(e) {
            if (e.target.value === 'reject') {
                unboxing_video_path_container.show()
                unboxing_video_path_input.attr('required', 'required');
            } else {
                unboxing_video_path_container.hide()
                unboxing_video_path_input.removeAttr('required')
            }
        })

        const form_modal = $('#form-modal')
        const close_form_modal = $('#close-form-modal')
        const modal_body = $('#modal-body')
        const btn_upload_receipt_modal = $('#btn-upload-receipt-modal')
        const btn_receive_specimen_modal = $('#btn-receive-specimen-modal')
        const btn_send_result_modal = $('#btn-send-result-modal')
        const btn_prepare_certificate_modal = $('#btn-prepare-certificate-modal')
        const btn_view_certificate_modal = $('#btn-view-certificate-modal')

        const my_modal_title = $('#my-modal-title')
        const upload_receipt_form = $('#upload-receipt-form')

        let formShowed;
        btn_upload_receipt_modal.click(function(e) {
            my_modal_title.text('Upload Receipt')
            form_modal.modal({
                backdrop: 'static',
                keyboard: false
            }, 'show')
            formShowed = $(`${$(this).data('target')}`)
            formShowed.show()
        })
        btn_receive_specimen_modal.click(function(e) {
            my_modal_title.text('Receive Specimen')
            form_modal.modal({
                backdrop: 'static',
                keyboard: false
            }, 'show')
            formShowed = $(`${$(this).data('target')}`)
            formShowed.show()

        })
        btn_send_result_modal.click(function(e) {
            my_modal_title.text('Send Result')
            form_modal.modal({
                backdrop: 'static',
                keyboard: false
            }, 'show')
            formShowed = $(`${$(this).data('target')}`)
            formShowed.show()

        })
        btn_prepare_certificate_modal.click(function(e) {
            my_modal_title.text('Prepare Certificate')
            form_modal.modal({
                backdrop: 'static',
                keyboard: false
            }, 'show')
            formShowed = $(`${$(this).data('target')}`)
            formShowed.show()

        })
        btn_view_certificate_modal.click(function(e) {
            my_modal_title.text('View Certificate')
            form_modal.modal({
                backdrop: 'static',
                keyboard: false
            }, 'show')
            formShowed = $(`${$(this).data('target')}`)

            formShowed.show()
            console.log(formShowed)

        })
        close_form_modal.click(function(e) {
            formShowed.hide()
            form_modal.modal('toggle')
            $('.modal-backdrop').hide();
        })
    })
</script>
@endsection