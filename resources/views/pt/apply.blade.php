@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Program Application
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> PT Application <u>(SDTL-{{$pt->sdtl}} | Cycle-{{$pt->cycle}})</u></h1>
    <a href="{{route('proficiency-testing.facility.index')}}" class="btn btn-danger">Cancel</a>

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

<!-- Upload Receipt MODAL -->
<div id="upload-receipt-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Upload receipt</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('proficiency-testing.facility.saveReceipt', $pt->id)}}" enctype="multipart/form-data">
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
            </div>

        </div>
    </div>
</div>
<!-- END Upload Receipt MODAL -->

<!-- receive-specimen-modal MODAL -->
<div id="receive-specimen-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Receive Specimen</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('proficiency-testing.facility.receiveSpecimen', $pt->id)}}" enctype="multipart/form-data">
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
            </div>

        </div>
    </div>
</div>
<!-- END receive-specimen-modal MODAL -->

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
                @if($application->specimens()->latest('created_at')->first()->unboxing_video_path)
                <span class="badge badge-pill badge-secondary">Specimen Received : {{$application->specimens()->latest('created_at')->first()->unboxing_video_path == 'accepted' ? 'Accepted' : 'Rejected'}}</span>
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
                @if($application)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if($pt->instruction_file_path)

                        <a href="/storage/{{$pt->instruction_file_path}}" class="dropdown-item btn btn-success" download>
                            <i class="fa fa-download fa-sm"></i> Download instructions
                        </a>
                        @endif
                        <button class="dropdown-item btn btn-info" type="button" data-toggle="modal" data-target="#upload-receipt-modal"><i class="fa fa-upload fa-sm"></i> Upload Receipt</button>
                        @if($application->specimens()->latest('created_at')->first())
                        @if(!$application->specimens()->latest('created_at')->first()->unboxing_video_path)
                        <button class="dropdown-item btn btn-info" type="button" data-toggle="modal" data-target="#receive-specimen-modal"><i class="fab fa-get-pocket"></i> Receive specimen</button>
                        @endif
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($application )
        <div class="card mt-2">
            <div class="card-body">
                <h5 class="card-title">Uploaded File/s:</h5>
                <hr>
                @if($application->receipt_path)
                <p>Receipt</p>
                <img src="/storage/{{$application->receipt_path}}" class="img img-fluid" alt="">
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
    })
</script>
@endsection