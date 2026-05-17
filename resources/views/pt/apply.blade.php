@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Program Application
@endsection

@section('content')
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

@php
    $test_method = $application ? json_decode($application->test_method_used, true) : null;
@endphp

<style>
    .btn-purple { background-color: #5b3ca1; border-color: #5b3ca1; color: #fff; }
    .timeline ul { padding-left: 0; }
    .timeline li { position: relative; padding-left: 28px; margin-bottom: 16px; }
    .timeline li:before { content: ""; position: absolute; left: 0; top: 6px; width: 12px; height: 12px; background: #5b3ca1; border-radius: 50%; }
    .table-sm td, .table-sm th { vertical-align: middle; }
    .card .form-control[readonly] { background-color: #f8f9fa; border: 1px solid #e9ecef; }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">PT Application <small class="text-muted">(SDTL-{{$pt->sdtl}} | Cycle-{{$pt->cycle}})</small></h1>
    <div>
        @role('Facility')
            <a href="{{route('proficiency-testing.facility.index')}}" class="btn btn-danger">Cancel</a>
        @else
            <a href="{{route('proficiency-testing.applicants', $pt->id)}}" class="btn btn-danger">Cancel</a>
        @endrole
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        @if($application)
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Application Overview</h4>
                <p class="text-muted mb-0">Overview of the current PT application</p>
            </div>
            <div class="text-right">
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md">

                @if($application)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input type="text" class="form-control" readonly value="SDTL-{{$pt->sdtl}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Year</label>
                                    <input type="text" class="form-control" readonly value="{{ $pt->year ?? date('Y') }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Cycle</label>
                                    <input type="text" class="form-control" readonly value="{{$pt->cycle}}">
                                </div>
                            </div>
                            @role('Facility')
                                @if($application)

                                    <div class="mt-3 d-flex">
                                        @if($pt->instruction_file_path)
                                        <a href="/storage/{{$pt->instruction_file_path}}" class="btn btn-outline-secondary mr-2" download><i class="fa fa-download"></i> Download Instructions</a>
                                        @endif
                                        @if($application && $application->certificate && $application->certificate->approved_by)
                                        <button class="btn btn-success" id="btn-view-certificate-modal" data-target="#view-cert-frame">View Certificate</button>
                                        @endif                                    
                                        @if(count($application->receipts) > 0)
                                            @if($application->receipts[0]->status_details->id == 4)
                                            <!-- upload receipt -->
                                            <button class="btn btn-success" id="btn-upload-receipt-modal" data-target="#upload-receipt-form"><i class="fa fa-upload mr-1"></i> Upload Receipt</button>
                                            @endif
                                        @else
                                            <button class="btn btn-success" id="btn-upload-receipt-modal" data-target="#upload-receipt-form"><i class="fa fa-upload mr-1"></i> Upload Receipt</button>
                                        @endif  
                                        
                                        <!-- specimen -->
                                        @if($application->specimens()->latest('created_at')->first())
                                            @if(!$application->specimens()->latest('created_at')->first()->unboxing_video_path)
                                                <button class="btn btn-warning" id="btn-receive-specimen-modal" type="button" data-target="#receive-specimen-form"><i class="fab fa-get-pocket fa-sm"></i> View/Receive specimen</button>
                                            @else

                                                @if($application->specimens()->latest('created_at')->first()->unboxing_video_path =='accepted' && !$application->result_path)
                                                    <button class="btn btn-primary" id="btn-send-result-modal" type="button" data-target="#send-result-form"><i class="fa fa-paper-plane fa-sm"></i> Submit result</button>
                                                @endif
                                            @endif
                                        @endif                                    
                                    </div>
                                @endif
                            @endrole


                        </div>
                    </div>
                @endif
                <form method="post" id='form' action="">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    
                                        <h5>Test Method Used</h5>
                                        @if(!$application)
                                            <div class="form-group">
                                                <select class="custom-select form-control" name="test_method_used" id="test_method_used">
                                                    <option value="itk">Immunoassay Test Kit</option>
                                                    <option value="inst">Instrumented</option>
                                                </select>
                                            </div>
                                            <div id="itk_fields" class="mb-2">
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Brand</span>
                                                    </div>
                                                    <input class="form-control" type="text" name="immunoassay_brand" placeholder="Enter brand here" required />
                                                </div>
                                            </div>
                                            <div id="inst_fields" style="display:none;">
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Type of Instrument</span>
                                                    </div>
                                                    <input class="form-control" type="text" name="instrument_type" placeholder="Enter Type of Instrument here" />
                                                </div>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Brand</span>
                                                    </div>
                                                    <input class="form-control" type="text" name="instrument_brand" placeholder="Enter brand here" />
                                                </div>
                                            </div>
                                        @else
                                            @if(!empty($test_method[0]['immunoassay_brand']))
                                                <p class="lead">Immunoassay Test Kit</p>
                                                <div class="form-control">{{$test_method[0]['immunoassay_brand']}}</div>
                                            @else
                                                <p class="lead">Instrumented</p>
                                                <div class="form-control mb-2">{{$test_method[1]['instrument_type'] ?? ''}}</div>
                                                <div class="form-control">{{$test_method[1]['instrument_brand'] ?? ''}}</div>
                                            @endif
                                        @endif
                                </div>

                                <div class="col-md-6">
                                    <h5>Cut Off Value for Method/s (ng/ml)</h5>
                                    <div class="form-group">
                                        <label>Methamphetamine (METH)</label>
                                        @if($application)
                                        <textarea class="form-control" rows="3" readonly>{{$application->methamphetamine}}</textarea>
                                        @else
                                        <textarea class="form-control" name="methamphetamine" rows="3" required></textarea>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Tetrahydrocannabinol (THC)</label>
                                        @if($application)
                                        <textarea class="form-control" rows="3" readonly>{{$application->tetrahydrocannabinol}}</textarea>
                                        @else
                                        <textarea class="form-control" name="tetrahydrocannabinol" rows="3" required></textarea>
                                        @endif
                                    </div>

                                    <div class="alert alert-primary">
                                        <strong>Total amount:</strong> P{{$pt->total_amount}}
                                    </div>

                                    @if(!$application)
                                        <div class="form-group">
                                            <label>Mode of Payment</label>
                                            <select id="mode_of_payment" class="custom-select" name="mode_of_payment">
                                                <option value="bt">Bank Transfer</option>
                                                <option value="ccp">Cash/Check Padala</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" id="submit" type="submit" @if($limit_reached) disabled @endif>Apply / Checkout</button>
                                    @else
                                        <h6>Mode of Payment</h6>
                                        <p class="lead">@if($application->mode_of_payment === 'bt') Bank Transfer @else Cash/Check Padala @endif</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


            </div>

            @if($application)
            <div class="col-md-4">
                <h5>Application Timeline</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="timeline">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <strong>Applied</strong>
                                    <div class="text-muted small">{{\Carbon\Carbon::parse($application->created_at ?? now())->toDayDateTimeString()}}</div>
                                </li>
                                @if($application && $application->result_path)
                                <li class="mb-3">
                                    <strong>Result Sent / for checking</strong>
                                    <div class="text-muted small">{{\Carbon\Carbon::parse($application->scored_at ?? $application->updated_at ?? now())->diffForHumans()}}</div>
                                </li>
                                @endif

                                @if($application && $application->certificate)
                                    @if($application->certificate->prepared_at)
                                    <li class="mb-3">
                                        <strong>Certificate Created</strong>
                                        <div class="text-muted small">{{\Carbon\Carbon::parse($application->certificate->prepared_at)->toDayDateTimeString()}}</div>
                                    </li>
                                    @endif
                                    @if($application->certificate->verified_by)
                                    <li class="mb-3">
                                        <strong>Certificate Verified</strong>
                                        <div class="text-muted small">{{\Carbon\Carbon::parse($application->certificate->verified_at)->toDayDateTimeString()}}</div>
                                    </li>
                                    @endif
                                    @if($application->certificate->approved_by)
                                    <li class="mb-3">
                                        <strong>Certificate Approved</strong>
                                        <div class="text-muted small">{{\Carbon\Carbon::parse($application->certificate->approved_at)->toDayDateTimeString()}}</div>
                                    </li>
                                    @endif
                                @endif

                                @if(count($application->receipts ?? []) > 0)
                                    @foreach($application->receipts as $receipt)
                                    <li class="mb-3">
                                        <strong>Receipt Uploaded</strong>
                                        <div><a href="/storage/{{$receipt->file_path}}" target="_blank">{{\Carbon\Carbon::parse($receipt->created_at)->format('M d, Y')}}</a></div>
                                        <div class="text-muted small">{{$receipt->status_details->status_name ?? ''}}</div>
                                    </li>
                                    @endforeach
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>

                <h5>Receipts</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>Attached</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($application && count($application->receipts)>0)
                                        @foreach($application->receipts as $receipt)
                                        <tr>
                                            <td><a href="/storage/{{$receipt->file_path}}" target="_blank">View</a></td>
                                            <td>{{\Carbon\Carbon::parse($receipt->created_at)->toDayDateTimeString()}}</td>
                                            <td>{{$receipt->status_details->status_name ?? ''}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="3" class="text-center text-muted">No receipts</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
            @endif
        </div>

    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="my-modal-title"></h5>
        <button type="button" class="close" id="close-form-modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="modal-body">
        <!-- upload-receipt-form -->
        <form method="POST" action="{{route('proficiency-testing.facility.saveReceipt', $pt->id)}}" enctype="multipart/form-data" id="upload-receipt-form" style="display:none;">
            @csrf
            @method("PUT")
            <div class="form-group">
                <label for="receipt">Receipt</label>
                <input id="receipt" type="file" class="form-control-file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" required>
                <small class="form-text text-muted">Files accepted: jpg, png and pdf</small>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <!-- receive-specimen-form -->
        @if($application && count($application->specimens) > 0)
        <form method="POST" action="{{route('proficiency-testing.facility.receiveSpecimen', $pt->id)}}" enctype="multipart/form-data" id="receive-specimen-form" style="display:none;">
            @csrf
            @method("PUT")
            <div class="form-group">
                <label>Courier</label>
                <div class="form-control">{{$application->specimens()->latest('created_at')->first()->courier}}</div>
            </div>
            <div class="form-group">
                <label>Tracking Number</label>
                <div class="form-control">{{$application->specimens()->latest('created_at')->first()->tracking_number}}</div>
            </div>
            <div class="form-group form-check">
                <input type="radio" class="form-check-input" id="accept" name="status" value="accept" required>
                <label class="form-check-label" for="accept">Accept</label>
                <input type="radio" class="form-check-input ml-3" id="reject" name="status" value="reject" required>
                <label class="form-check-label" for="reject">Reject</label>
            </div>
            <div id="accepted_bottles_container" style="display:none;" class="form-group">
                <label>Accepted bottles (optional)</label>
                <input id="accepted_bottles" class="form-control" type="number" name="accepted_bottles" min="0" max="20">
            </div>

            <div id="unboxing_video_path_container" style="display:none;">
                <div class="form-group">
                    <label>Unboxing video (optional)</label>
                    <input id="unboxing_video_path" type="file" class="form-control-file" name="unboxing_video_path" accept="video/*">
                </div>
            </div>

            <div class="text-right">
                <button id="submit-receive-specimen" class="btn btn-primary">Submit</button>
            </div>
        </form>
        @endif

        <!-- send-result-form -->
        <form method="POST" action="{{route('proficiency-testing.facility.saveResult', $pt->id)}}" id="send-result-form" style="display:none;" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <div class="form-group">
                <label for="result">Result</label>
                <input id="result" type="file" class="form-control-file" name="result" required>
                <small class="form-text text-muted">Take picture of test result.</small>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>

        <!-- view certificate -->
        @if($application && $application->certificate && $application->certificate->approved_by)
        <div id="view-cert-frame" style="display:none;">
            <iframe class="embed-responsive-item w-100" style="height:500px;" allowfullscreen src="{{route('certificate', $application->certificate->key)}}"></iframe>
        </div>
        @endif

      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')
<script>
$(function(){
    const form_modal = $('#form-modal');
    const close_form_modal = $('#close-form-modal');
    const my_modal_title = $('#my-modal-title');
    let formShowed = null;

    // Show modal and the selected form
    $('#btn-upload-receipt-modal, #btn-receive-specimen-modal, #btn-send-result-modal, #btn-view-certificate-modal').on('click', function(e){
        e.preventDefault();
        const target = $(this).data('target');
        if(!target) return;
        my_modal_title.text($(this).text().trim());
        form_modal.modal({backdrop:'static', keyboard:false});
        formShowed = $(target);
        // hide others & show target
        $('#upload-receipt-form, #receive-specimen-form, #send-result-form, #view-cert-frame').hide();
        formShowed.show();
    });

    // Close modal
    close_form_modal.on('click', function(){
        form_modal.modal('hide');
        $('#upload-receipt-form, #receive-specimen-form, #send-result-form, #view-cert-frame').hide();
    });

    // receive specimen submit handler
    $('#submit-receive-specimen').on('click', function(e){
        e.preventDefault();
        const statusVal = $('[name="status"]:checked').val();
        if (statusVal === undefined) {
            alert('Please select Accept or Reject.');
            return;
        }
        if (statusVal === 'accept') {
            if (confirm('Confirm specimen acceptance?')) {
                $('#receive-specimen-form').submit();
            }
        } else {
            $('#receive-specimen-form').submit();
        }
    });

    // status radio change
    $(document).on('change', '[name="status"]', function(){
        const val = $(this).val();
        if(val === 'reject'){
            $('#unboxing_video_path_container').show();
            $('#unboxing_video_path').attr('required', 'required');
            $('#accepted_bottles_container').hide();
        } else {
            $('#unboxing_video_path_container').hide();
            $('#unboxing_video_path').removeAttr('required');
            $('#accepted_bottles_container').show();
        }
    });

    // Test method toggle
    $('#test_method_used').change(function(){
        const val = $(this).val();
        if(val === 'itk'){
            $('#itk_fields').show();
            $('#inst_fields').hide();
        } else {
            $('#itk_fields').hide();
            $('#inst_fields').show();
        }
    });
});
</script>
@endsection