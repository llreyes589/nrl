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

<div class="card ">
    <div class="card-header">
        @if(!isset($cert_details->facility_verified_by))
        <h5>Verify Certificate</h5>
        @else
        <h5>This certificate was issued to: {{$cert_details->facility_verified_by}} on {{\Carbon\Carbon::createFromTimeStamp(strtotime($cert_details->facility_verified_at))->toDayDateTimeString()}}</h5>
        @endif
    </div>
    <div class="card-body">

        @if(!isset($cert_details->facility_verified_by))
        <p class="card-text mt-3">Click the indicate your name and click verify button below to verify certificate.</p>
        <hr>
        <form method="POST" action="{{route('verifyMyCertificate', $cert_details->key)}}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="my-input">Verify by:</label>
                <input id="my-input" class="form-control col-md-4" type="text" name="facility_verified_by" required placeholder="Enter you name here">
            </div>
            <button class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-flag"></i>
                </span>
                <span class="text">Verify</span>
            </button>
        </form>
        @endif
    </div>
    <div class="card-footer">
        <p class="lead">Note: If the details on the certificate do not match, please call East Ave NRL.</p>
    </div>
</div>
@endsection