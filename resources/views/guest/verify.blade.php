@extends('layouts.app')

@section('title')
NRL - Verify my certificate
@endsection

@section('content')
@if(Session::has('message'))
<div class="alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif


<div class="card {{!isset($cert_details->facility_verified_by) ? 'border-primary' : 'border-success'}}">
    <div class="card-header">
        @if(!isset($cert_details->facility_verified_by))
        <h5 class="text-primary">Verify Certificate to DTL Proficiency Certificate Verification</h5>
        @else
        <h5 class="text-success">This is a VALID Certificate until {{\Carbon\Carbon::createFromTimeStamp(strtotime($cert_details->validity))->toDayDateTimeString()}}
        @endif
    </div>
    <div class="card-body">
        @if(!isset($cert_details->facility_verified_by))
        <form method="POST" action="{{route('verifyMyCertificate', $cert_details->key)}}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="my-input">Verification Requested By:</label>
                <input id="my-input" class="form-control col-md-4" type="text" name="facility_verified_by" required placeholder="Enter you name here">
            </div>
            <button class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-flag"></i>
                </span>
                <span class="text">Submit</span>
            </button>
        </form>
        @else
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    Laboratory:
                </div>
                <div class="col-md col-sm-12">
                    <strong>{{$cert_details->facility->name}}</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    Address:
                </div>
                <div class="col-md col-sm-12">
                    <strong>{{$cert_details->facility->address}}</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    Issued to:
                </div>
                <div class="col-md col-sm-12">
                    <strong>{{$cert_details->facility_verified_by}}</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    Issued at:
                </div>
                <div class="col-md col-sm-12">
                    <strong>{{\Carbon\Carbon::createFromTimeStamp(strtotime($cert_details->facility_verified_at))->toDayDateTimeString()}}</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    Accreditation Number:
                </div>
                <div class="col-md col-sm-12">
                    <strong>{{$cert_details->facility->accreditation_no}}</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    Certificate Number:
                </div>
                <div class="col-md col-sm-12">
                    <strong>{{$cert_details->certificate_no}}</strong>
                </div>
            </div>
        @endif
    </div>
    @if(isset($cert_details->facility_verified_by))
    <div class="card-footer">
        <p class="lead text-danger">Note: If the details on the certificate do not match, please call East Ave NRL.</p>
    </div>
    @endif
</div>
@endsection