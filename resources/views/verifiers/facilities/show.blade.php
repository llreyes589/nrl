@extends('layouts.main')

@section('title')
NFL - Facility
@endsection

@section('content')

@if(Session::has('message'))
<div class="alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<!-- Modal -->

<div id="show_certificate_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="show_certificate_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificate_modal_title">Certificate No: <span class="cert_no"></span></h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <p>
                            OR No:
                        </p>
                    </div>
                    <div class="col-md col-sm-12">
                        <strong class="or_no">
                        </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <p>
                            Performance:
                        </p>
                    </div>
                    <div class="col-md col-sm-12">
                        <strong class="performance">
                        </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <p>
                            Validity:
                        </p>
                    </div>
                    <div class="col-md col-sm-12">
                        <strong class="validity">
                        </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <p>
                            Prepared by:
                        </p>
                    </div>
                    <div class="col-md col-sm-12">
                        <strong class="prepared_by">
                        </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <p>
                            Verified by:
                        </p>
                    </div>
                    <div class="col-md col-sm-12">
                        <strong class="verified_by">
                        </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <p>
                            Approved by:
                        </p>
                    </div>
                    <div class="col-md col-sm-12">
                        <strong class="approved_by">
                        </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <p>
                            Issued On:
                        </p>
                    </div>
                    <div class="col-md col-sm-12">
                        <strong class="issued_on">
                        </strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>

        </div>
    </div>
</div>
<!-- End Modal -->


<!-- Modal -->

<div id="certificate_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="certificate_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificate_modal_title">Certificate</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" id="certificate_container" src="" allowfullscreen></iframe>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Modal -->

@if(isset($facility->certificate->id))
<div id="edit_cert_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificate_modal_title">Edit Certificate</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('verifiers.facilities.edit_certificate', $facility->certificate->id)}}">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="or_no">OR Number:</label>
                        <input id="or_no" class="form-control" value="{{$facility->certificate != null ? $facility->certificate->or_no : ''}}" type="text" name="or_no" placeholder="OR Number" required>
                    </div>
                    <div class="form-group">
                        <label for="or_no">Certificate Number:</label>
                        <input id="or_no" class="form-control" value="{{$facility->certificate != null ? $facility->certificate->certificate_no : ''}}" type="text" name="certificate_no" placeholder="Certificate Number" required>
                    </div>
                    <div class="form-group">
                        <label for="validity">Validity</label>
                        <input id="validity" class="form-control" value="{{$facility->certificate != null ? $facility->certificate->validity : ''}}" type="date" name="validity" required>
                    </div>
                    <div class="form-group">
                        <label for="performance">Performance</label>
                        <select id="performance" class="custom-select" name="performance" required>
                            <option value="">--Please select performance here--</option>
                            <option value="A" {{$facility->certificate->performance == 'A' ? 'selected' : ''}}>Acceptable</option>
                            <option value="E" {{$facility->certificate->performance == 'E' ? 'selected' : ''}}>Excellent</option>
                            <option value="HS" {{$facility->certificate->performance == 'HS' ? 'selected' : ''}}>Highly Satisfactory</option>
                        </select>
                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">Update</button>
                    <button class="btn btn-secondary btn-sm" type="button" id="cancel_btn">Cancel</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endif

<!-- Encoder Modal -->


<div id="encoder_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="encoder_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificate_modal_title">Prepare Certificate</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('verifiers.facilities.create_certificate', $facility->id)}}">
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
                    <button class="btn btn-secondary btn-sm" type="button" id="cancel_btn">Cancel</button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- /Encoder Modal -->
<!-- End Modal -->

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{auth()->user()->can('create facility') ? route('facilities.index') : route('verifiers.facilities.index')}}" class="btn btn-danger float-right" type="button">Back</a>
                <h3 class="m-0 font-weight-bold text-primary">{{$facility->name}}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Accreditation No.:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->accreditation_no}}</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Name:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->name}}</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Address:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->address}}</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Contact No.:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->contact_no}}</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Email:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->email}}</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Lab Email:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->lab_email}}</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>OR No.: </p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->certificate != null ? $facility->certificate->or_no : 'N/A'}}</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Certificate No.:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->certificate != null ? $facility->certificate->certificate_no : 'N/A'}}</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Validity:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->certificate != null ? $facility->certificate->validity : 'N/A'}}</strong></p>
                    </div>
                </div>
                @if(isset($facility->certificate->performance))
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Performance:</p>
                    </div>
                    <?php
                    $performance = '';
                    switch ($facility->certificate->performance) {
                        case 'A':
                            $performance = 'Acceptable';
                            break;
                        case 'E':
                            $performance = 'Excellent';
                            break;
                        case 'HS':
                            $performance = 'Highly Satisfactory';
                            break;
                        default:
                            $facility->certificate->performance;
                            break;
                    }
                    ?>
                    <div class="col-md col-sm-12">
                        <p class=""><strong>{{$facility->certificate != null ? $performance : 'N/A'}}</strong></p>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <p>Action:</p>
                    </div>
                    <div class="col-md col-sm-12">
                        @role('encoder')
                        @if($facility->certificate != null)

                        @if(isset($facility->certificate->approved_by))

                        <button class="btn btn-success btn-icon-split btn-sm" id="add_certificate_btn">
                            <span class="icon text-white-50">
                                <i class="fas fa-certificate"></i>
                            </span>
                            <span class="text">Prepare Certificate</span>
                        </button>
                        @else
                        <button class="btn btn-info btn-icon-split btn-sm" id="edit_certificate_btn">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Edit Certificate</span>
                        </button>
                        @endif

                        @if(!isset($facility->certificate->prepared_by))
                        <form method="POST" action="{{route('verifiers.facilities.updatePrepared', ['id'=>$facility->id, 'cert_id' => $facility->certificate->id])}}">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-primary btn-icon-split btn-sm">
                                <span class="icon text-white-50">
                                    <i class="fas fa-flag"></i>
                                </span>
                                <span class="text">Flag as Prepared</span>
                            </button>
                        </form>
                        @endif
                        @else
                        <button class="btn btn-success btn-icon-split btn-sm" id="add_certificate_btn">
                            <span class="icon text-white-50">
                                <i class="fas fa-certificate"></i>
                            </span>
                            <span class="text">Prepare Certificate</span>
                        </button>
                        @endif

                        @endrole

                        @role('verifier')
                        @if($facility->certificate != null)
                        @if(isset($facility->certificate->prepared_by))
                        @if(!isset($facility->certificate->verified_by))
                        <form method="POST" action="{{route('verifiers.facilities.updateVerified', ['id' => $facility->id, 'cert_id' => $facility->certificate->id])}}">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-primary btn-icon-split btn-sm">
                                <span class="icon text-white-50">
                                    <i class="fas fa-flag"></i>
                                </span>
                                <span class="text">Flag as Verified</span>
                            </button>
                        </form>
                        @endif
                        @endif
                        @endif
                        @endrole

                        @role('head')
                        @if($facility->certificate != null)
                        @if(isset($facility->certificate->verified_by))
                        @if(!isset($facility->certificate->approved_by))
                        <form method="POST" action="{{route('verifiers.facilities.updateApproved', ['id' => $facility->id, 'cert_id' => $facility->certificate->id])}}">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-primary btn-icon-split btn-sm">
                                <span class="icon text-white-50">
                                    <i class="fas fa-flag"></i>
                                </span>
                                <span class="text">Flag as Approved</span>
                            </button>
                        </form>
                        @endif
                        @endif
                        @endif
                        @endrole
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Certificate Status</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <div class="row mb-1">
                                    <div class="col">
                                        @if($facility->certificate != null)

                                        <div class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Certificate Template: {{$facility->certificate->cert_template->year}}</span>
                                        </div>
                                        @if(isset($facility->certificate->prepared_by))
                                        <div class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Certificate Prepared</span>
                                        </div>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col">
                                        @if(isset($facility->certificate))
                                        @if(isset($facility->certificate->verified_by))
                                        <div class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Endorsed for Approval</span>
                                        </div>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col">
                                        @if(isset($facility->certificate))
                                        @if(isset($facility->certificate->approved_by))
                                        <div class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Approved</span>
                                        </div>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @if(isset($facility->certificate->approved_by))
        <div class="card shadow mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <!-- <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                View Certificate</div> -->
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <button disable="{{$facility->certificate->approved_by}}" class="btn btn-info btn-sm" type="button" id="generate_certificate_btn"><i class="fa fa-certificate"></i> View Certificate</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Issued Certificates</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @forelse($certificates as $certificate)
                                <button onclick="viewCert({{$certificate}})" class="btn btn-primary btn-icon-split btn-sm">
                                    <span class="icon text-white-50">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <span class="text">{{$certificate->certificate_no}}</span>
                                </button>
                                @empty
                                <p class="lead text-danger text-center">No certificate found</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    const facility = JSON.parse('{!!json_encode($facility) !!}')
    window.onload = function() {
        $('#generate_certificate_btn').click(function() {
            $('#certificate_modal').modal('show')
        })

        $('#certificate_modal').on('hide.bs.modal', function() {
            $('#certificate_container').attr('src', '')
        })




        if (facility.certificate) {

            $('#certificate_modal').on('show.bs.modal', function() {
                $('#certificate_container').attr('src', '{{route("certificate", ["key" => $facility->certificate->key])}}')
            })
        }

        $('#add_certificate_btn').click(function() {
            $('#encoder_modal').modal('show')
        })

        $('#cancel_btn').click(function() {
            $('#encoder_modal').modal('hide')
            $('#edit_cert_modal').modal('hide')

        })

        $('#edit_certificate_btn').click(function() {
            $('#edit_cert_modal').modal('show')
        })

    }

    function viewCert(cert) {
        let performance = ''
        switch (cert.performance) {
            case 'E':
                performance = 'EXCELLENT';
                break;
            case 'VS':
                performance = 'VERY SATISFACTORY';
                break;
            case 'HS':
                performance = 'HIGHLY SATISFACTORY';
                break;

            default:
                performance = 'SATISFACTORY';
                break;
        }
        $('.cert_no').html(cert.certificate_no)
        $('.or_no').html(cert.or_no)
        $('.performance').html(performance)
        $('.validity').html(cert.validity)
        $('.prepared_by').html(cert.prepared_by_details.name)
        $('.verified_by').html(cert.verified_by_details.name)
        $('.approved_by').html(cert.approved_by_details.name)
        $('.issued_on').html(cert.facility_verified_at)
        $('#show_certificate_modal').modal('show')
    }
</script>
@endsection