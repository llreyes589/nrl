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

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{route('verifiers.facilities.index')}}" class="btn btn-danger float-right" type="button">Back</a>
        <h3 class="m-0 font-weight-bold text-primary">{{$facility->name}}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <p>Accreditation No.:</p>
            </div>
            <div class="col-md col-sm-12">
                <p class=""><strong>{{$facility->accreditation_no}}</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <p>Name:</p>
            </div>
            <div class="col-md col-sm-12">
                <p class=""><strong>{{$facility->name}}</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <p>Address:</p>
            </div>
            <div class="col-md col-sm-12">
                <p class=""><strong>{{$facility->address}}</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <p>Contact No.:</p>
            </div>
            <div class="col-md col-sm-12">
                <p class=""><strong>{{$facility->contact_no}}</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <p>Email:</p>
            </div>
            <div class="col-md col-sm-12">
                <p class=""><strong>{{$facility->email}}</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <p>Lab Email:</p>
            </div>
            <div class="col-md col-sm-12">
                <p class=""><strong>{{$facility->lab_email}}</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <p>OR No.:</p>
            </div>
            <div class="col-md col-sm-12">
                <p class=""><strong>{{$facility->or_no}}</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <p>Validity:</p>
            </div>
            <div class="col-md col-sm-12">
                <p class=""><strong>{{$facility->validity}}</strong></p>
            </div>
        </div>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
        <h6>Status:</h6>
        <div class="row">
            <div class="col">
                @if(isset($facility->verified_by))
                <div class="btn btn-success btn-icon-split btn-sm">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Verified</span>
                </div>
                @else
                <form method="POST" action="{{route('verifiers.facilities.updateVerified', $facility->id)}}">
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
                @if(isset($facility->endorsed_by))
                <div class="btn btn-success btn-icon-split btn-sm">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Endorse Approved</span>
                </div>
                @else
                <form method="POST" action="{{route('verifiers.facilities.updateEndorse', $facility->id)}}">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-primary btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-flag"></i>
                        </span>
                        <span class="text">Flag as Endorse Approved</span>
                    </button>
                </form>
                @endif
                @if(isset($facility->approved_by))
                <div class="btn btn-success btn-icon-split btn-sm">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Approved</span>
                </div>
                @else
                <form method="POST" action="{{route('verifiers.facilities.updateApproved', $facility->id)}}">
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
            </div>
        </div>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
        <div class="row">
            <div class="col">
                <button class="btn btn-secondary" type="button" id="generate_certificate_btn">Generate Certificate</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    window.onload = function(){
        $('#generate_certificate_btn').click(function(){
            $('#certificate_modal').modal('show')
        })

        $('#certificate_modal').on('hide.bs.modal', function(){
            $('#certificate_container').attr('src', '')
        })

        $('#certificate_modal').on('show.bs.modal', function(){
            $('#certificate_container').attr('src', '{{route("verifiers.facilities.certificate", $facility->id)}}')
        })
    }
</script>
@endsection