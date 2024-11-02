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


@if($errors->any())

<div class="alert alert-danger" role="alert">
    {!! implode('', $errors->all('<div>:message</div>')) !!}
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

                @if($application)
                @if(count($application->specimens) > 0)
                <!-- receive-specimen-form -->
                <form method="POST" action="{{route('proficiency-testing.facility.receiveSpecimen', $pt->id)}}" enctype="multipart/form-data" id="receive-specimen-form" style="display:none;">
                    @csrf
                    @method("PUT")
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="courier">Courier:</label>
                                <div id="courier" class="form-control"> {{$application->specimens()->latest('created_at')->first()->courier}}</div>
                            </div>
                            <div class="form-group">
                                <label for="tracking_number">Tracking Number:</label>
                                <div id="tracking_number" class="form-control"> {{$application->specimens()->latest('created_at')->first()->tracking_number}}</div>
                            </div>
                        </div>
                        <div class="col-md col-sm-12">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="accept" name="status" value="accept" required>
                                <label class="custom-control-label" for="accept">Accept</label>
                            </div>
                            <div class="form-group" id="accepted_bottles_container" style="display: none;">
                                <input id="accepted_bottles" class="form-control" type="number" name="accepted_bottles" placeholder="Enter number of accepted bottles (optional)" min="0" max="20">
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="reject" name="status" value="reject" required>
                                <label class="custom-control-label" for="reject">Reject</label>
                            </div>
                            <hr>
                            <div id="unboxing_video_path_container" style="display: none;">
                                <div class="form-group row">
                                    <label for="unboxing_video_path" class="col-md-4 col-form-label text-md-right">{{ __('Attach Video/Image') }}</label>

                                    <div class="col-md-6">
                                        <input id="unboxing_video_path" type="file" class="form-control-file" name="unboxing_video_path">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                                    <div class="col-md-6">
                                        <textarea id="description" class="form-control" name="reject_description" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button id="submit-receive-specimen" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
                @endif
                @endif

                <!-- send-result-form -->
                <form method="POST" action="{{route('proficiency-testing.facility.saveResult', $pt->id)}}" id="send-result-form" style="display:none;" enctype="multipart/form-data">
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle"></i> Take picture of test result.
                    </div>
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


                @if($application->certificate)
                @if($application->certificate->approved_by)
                <!-- View certificate -->
                <div class="embed-responsive embed-responsive-16by9" id="view-cert-frame" style="display:none;">
                    <iframe class="embed-responsive-item" allowfullscreen src="{{route('certificate', $application->certificate->key)}}"></iframe>
                </div>
                @endif
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
                        <button class="dropdown-item btn btn-info" id="btn-receive-specimen-modal" type="button" data-target="#receive-specimen-form"><i class="fab fa-get-pocket fa-sm"></i> View/Receive specimen</button>
                        @else

                        @if($application->specimens()->latest('created_at')->first()->unboxing_video_path =='accepted' && !$application->result_path)
                        <button class="dropdown-item btn btn-info" id="btn-send-result-modal" type="button" data-target="#send-result-form"><i class="fa fa-paper-plane fa-sm"></i> Submit result</button>
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



                @if($application->certificate->approved_by)
                <button class="btn btn-success" id="btn-view-certificate-modal" type="submit" data-target="#view-cert-frame">View Certificate</button>
                @endif

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
                <a href="/storage/{{$application->receipt_path}}" target="_blank" class="btn btn-success btn-sm">Receipt</a>

                <hr>
                @endif
                @if($application->result_path)
                <a href="/storage/{{$application->result_path}}" target="_blank" class="btn btn-primary btn-sm">Result</a>

                @endif
            </div>
        </div>
        @endif
    </div>
    <div class="col-md col-sm-12">
        <div class="card">
            <div class="card-body">
                @if($application)
                <!-- Timeline -->
                <div class="row d-flex justify-content-center">
                    <div class="col-md-8 col-sm-12 ">
                        <h4>Application History</h4>
                        <hr>
                        <ul class="timeline">
                            @if($application->certificate)
                            @if($application->certificate->approved_by)
                            <li class="text-info">
                                <u>Certificate Approved</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($application->certificate->approved_at)->diffForHumans()}}</span>
                                <p class="text-secondary">Certificate was approved on {{\Carbon\Carbon::parse($application->certificate->approved_at)->toDayDateTimeString()}}</p>
                            </li>
                            @endif
                            @if($application->certificate->verified_by)
                            <li class="text-info">
                                <u>Certificate Verified</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($application->certificate->verified_at)->diffForHumans()}}</span>
                                <p class="text-secondary">Certificate was verified on {{\Carbon\Carbon::parse($application->certificate->verified_at)->toDayDateTimeString()}}</p>
                            </li>
                            @endif
                            <li class="text-info">
                                <u>Certificate Created</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($application->certificate->prepared_at)->diffForHumans()}}</span>
                                <p class="text-secondary p-0 m-0">Certificate was created on {{\Carbon\Carbon::parse($application->certificate->prepared_at)->toDayDateTimeString()}}</p>
                                <p class="text-secondary"><strong>Validity: {{$application->pt->cert_validity}}</strong></p>
                            </li>
                            @endif
                            @if(gettype($application->score) == 'integer')
                            <?php
                            $score = new \App\Library\Scoring($application->score);

                            ?>
                            <li class="@if($application->score > 2) text-danger @else text-info @endif">
                                <u>Added Score</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($application->scored_at)->diffForHumans()}}</span>
                                <p class="@if($application->score > 2) text-danger @else text-secondary @endif">Wrong Answers: {{$application->score}} ({{$score->get_performance()[1]}}) on {{\Carbon\Carbon::parse($application->scored_at)->toDayDateTimeString()}}</p>
                            </li>
                            @endif
                            @if($application->result_path)
                            <li class="text-info">
                                <u>Result sent</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($application->result_uploaded_at)->diffForHumans()}}</span>
                                <p class="text-secondary">Result was uploaded on {{\Carbon\Carbon::parse($application->result_uploaded_at)->toDayDateTimeString()}}</p>
                            </li>
                            @endif
                            @if(count($application->specimens) > 0)
                            <?php
                            $specimens = $application->specimens()->orderBy('created_at', 'desc')->get();
                            $firstSpecimen = $application->specimens()->first();
                            ?>
                            @foreach($specimens as $specimen)
                            @if($specimen->unboxing_video_path)
                            <li class="text-info">
                                <u>@if($specimen->id != $firstSpecimen->id) Resent @endif Specimen Received</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($specimen->updated_at)->diffForHumans()}}</span>
                                <p class="text-secondary m-0">@if($specimen->id != $firstSpecimen->id) Resent @endif Specimen was
                                    @if($specimen->unboxing_video_path == 'accepted')
                                    <span class="text-success">Accepted</span>
                                    @else
                                    <span class="text-danger">Rejected</span>
                                    @endif
                                    on {{\Carbon\Carbon::parse($specimen->updated_at)->toDayDateTimeString()}}
                                </p>
                                @if($specimen->reject_description)
                                <p class="m-0 p-0 text-danger">Rejected Reason: <strong>{{$specimen->reject_description}}</strong></p>
                                @endif
                                @if($specimen->proceed_at)
                                <p class="m-0 p-0 text-success">Proceed Instruction: <strong>{{$specimen->proceed_text}}</strong></p>
                                @endif
                            </li>
                            @endif
                            <li class="text-info">
                                <u>Specimen @if($specimen->id != $firstSpecimen->id) Resent @else Sent @endif</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($specimen->created_at)->diffForHumans()}}</span>
                                <p class="text-secondary">Specimen was @if($specimen->id != $firstSpecimen->id) Resent @else Sent @endif on {{\Carbon\Carbon::parse($specimen->created_at)->toDayDateTimeString()}}</p>
                            </li>
                            @endforeach

                            @endif
                            @if($application->verified_payment_at)
                            <li class="text-info">
                                <u>Payment Verified</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($application->verified_payment_at)->diffForHumans()}}</span>
                                <p class="text-secondary">Payment was verified on {{\Carbon\Carbon::parse($application->verified_payment_at)->toDayDateTimeString()}}</p>
                            </li>
                            @endif

                            @if($application->receipt_path)
                            <li class="text-info">
                                <u>Receipt Uploaded</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($application->receipt_uploaded_at)->diffForHumans()}}</span>
                                <p class="text-secondary">Receipt was uploaded on {{\Carbon\Carbon::parse($application->receipt_uploaded_at)->toDayDateTimeString()}}</p>
                            </li>
                            @endif
                            <li class="text-info">
                                <u>Applied</u>
                                <span class="float-right">{{\Carbon\Carbon::parse($application->created_at)->diffForHumans()}}</span>
                                <p class="text-secondary">Applied on {{\Carbon\Carbon::parse($application->created_at)->toDayDateTimeString()}}</p>

                            </li>
                        </ul>
                    </div>
                </div>
                <hr>
                @endif
                <form method="post" id='form' action="">
                    @csrf
                    <h4 for="cycle">Test Method Used</h4>
                    <hr>
                    @if(!$application)
                    <div class="form-group">
                        <select class="custom-select-lg form-control" name="test_method_used" id="test_method_used">
                            <option value="itk">Immunoassay Test Kit</option>
                            <option value="inst">Instrumented</option>
                        </select>
                    </div>
                    <div class="input-group" id="itk_fields">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="my-addon">Brand</span>
                        </div>
                        @if($application)
                        <div class="form-control">{{$test_method[0]['immunoassay_brand']}} </div>
                        @else
                        <input class="form-control" type="text" name="immunoassay_brand" placeholder="Enter brand here" aria-label="Brand" aria-describedby="my-addon" required />
                        @endif
                    </div>
                    <div id="inst_fields" style="display: none;">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="my-addon">Type of Instrument used</span>
                            </div>
                            @if($application)
                            <div class="form-control">{{$test_method[1]['instrument_type']}} </div>
                            @else
                            <input class="form-control" type="text" name="instrument_type" placeholder="Enter Type of Instrument here" aria-label="Recipient's " aria-describedby="my-addon" />
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
                            <input class="form-control" type="text" name="instrument_brand" placeholder="Enter brand here" aria-label="Recipient's " aria-describedby="my-addon" />
                            @endif
                        </div>
                    </div>
                    @else
                    @if($test_method[0]['immunoassay_brand'])
                    <p class="lead">Immunoassay Test Kit</p>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="my-addon">Brand</span>
                        </div>
                        @if($application)
                        <div class="form-control">{{$test_method[0]['immunoassay_brand']}} </div>
                        @else
                        <input class="form-control" type="text" name="immunoassay_brand" placeholder="Enter brand here" aria-label="Recipient's " aria-describedby="my-addon" />
                        @endif
                    </div>
                    @else
                    <p class="lead">Intrumented</p>
                    <div>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="my-addon">Type of Instrument used</span>
                            </div>
                            @if($application)
                            <div class="form-control">{{$test_method[1]['instrument_type']}} </div>
                            @else
                            <input class="form-control" type="text" name="instrument_type" placeholder="Enter Type of Instrument here" aria-label="Recipient's " aria-describedby="my-addon" />
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
                            <input class="form-control" type="text" name="instrument_brand" placeholder="Enter brand here" aria-label="Recipient's " aria-describedby="my-addon" />
                            @endif
                        </div>
                    </div>
                    @endif
                    @endif

                    <br>
                    <h4 for="cycle">Cut Off Value for Method/s (ng/ml)</h4>
                    <hr>
                    <div class="form-group">
                        <label for="cutoff">Methamphetamine (METH) </label>
                        @if($application)
                        <textarea id="methamphetamine" class="form-control" name="methamphetamine" rows="3" readonly>{{$application->methamphetamine}}</textarea>
                        @else
                        <textarea id="methamphetamine" class="form-control" name="methamphetamine" rows="3" required></textarea>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="cutoff">Tetrahydrocannabinol (THC) </label>
                        @if($application)
                        <textarea id="tetrahydrocannabinol" class="form-control" name="tetrahydrocannabinol" rows="3" readonly>{{$application->tetrahydrocannabinol}}</textarea>
                        @else
                        <textarea id="tetrahydrocannabinol" class="form-control" name="tetrahydrocannabinol" rows="3" required></textarea>
                        @endif
                    </div>
                    <div class="alert alert-primary" role="alert">
                        <p class="lead p-0 m-0">Total amount: <span class="font-weight-bold">P{{$pt->total_amount}}</span></p>
                    </div>
                    @if(!$application)
                    <div class="form-group">
                        <label for="mode_of_payment">Mode of Payment</label>
                        <select id="mode_of_payment" class="custom-select" name="mode_of_payment">
                            <option value="bt">Bank Transfer</option>
                            <option value="ccp">Cash/Check Padala</option>
                        </select>
                    </div>
                    @else
                    <h4>Mode of Payment:</h4>
                    <p class="lead">-@if($application->mode_of_payment === 'bt') Bank Transfer @else Cash/Check Padala @endif</p>
                    @endif
                    <hr />
                    @if(!$application)
                    <button class="btn btn-primary" id="submit" type="submit" @if($limit_reached) disabled @endif>Apply/Checkout</button>
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
                $('#accepted_bottles_container').hide()
            } else {
                unboxing_video_path_container.hide()
                $('#accepted_bottles_container').show()
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

        const submit_receive_specimen = $('#submit-receive-specimen')

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

        })
        close_form_modal.click(function(e) {
            formShowed.hide()
            form_modal.modal('toggle')
            $('.modal-backdrop').hide();
        })

        submit_receive_specimen.click(function(e) {
            e.preventDefault()
            const statusVal = $('[name="status"]:checked').val();
            if (statusVal === undefined) {
                alert('Please select Accept or Reject.')
                return
            }

            if (statusVal === 'accept') {
                const conf = window.confirm('Confirm specimen acceptance?')
                if (conf) $('#receive-specimen-form').submit()
            } else {
                $('#receive-specimen-form').submit()
            }


        })

        const immunoassay_brand = $('[name="immunoassay_brand"]')
        const instrument_type = $('[name="instrument_type"]')
        const instrument_brand = $('[name="instrument_brand"]')

        $('#test_method_used').change(function(e) {
            e.preventDefault()
            immunoassay_brand.removeAttr('required')
            instrument_type.removeAttr('required')
            instrument_brand.removeAttr('required')
            // alert(e.target.value)
            const val = e.target.value
            if (val === 'itk') {
                immunoassay_brand.attr('required', 'required')
                $('#itk_fields').toggle()
                $('#inst_fields').toggle()
            } else {
                instrument_type.attr('required', 'required')
                instrument_brand.attr('required', 'required')
                $('#itk_fields').toggle()
                $('#inst_fields').toggle()

            }
        })

        // limit reached 
        const limit_reached = '{{$limit_reached ?? ``}}'
        const limitReachedContent = $('#limitReachedContent')
        if (limit_reached) {
            limitReachedContent.show()
            gmodalTitle.text('Attention!')
            gmodal.modal()
        }
    })
</script>
@endsection

@section('javascript')
<script>
    $(function() {

        alert('test')
        $(function() {

            console.log({
                modal
            })
            modal.modal()
        })
    })
</script>
@endsection