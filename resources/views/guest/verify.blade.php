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
        <h5>Verify Certificate</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 col-sm-12">
                OR No.:
            </div>
            <div class="col-md col-sm-12">
                <strong>{{$cert_details->or_no}}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                Validity:
            </div>
            <div class="col-md col-sm-12">
                <strong>{{$cert_details->validity}}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                Performance:
            </div>
            <div class="col-md col-sm-12">
                <?php
                    $performance = '';
                    switch ($cert_details->performance) {
                        case 'E':
                            $performance = 'Excellent';
                            break;
                        case 'HS':
                            $performance = 'Highly Satisfactory';
                            break;
                        case 'VS':
                            $performance = 'Very Satisfactory';
                            break;
                        default:
                            $performance = 'Satisfactory';
                            break;
                    }
                ?>
                <strong>{{$performance}}</strong>
            </div>
        </div>
        @if(isset($cert_details->facility_verified_by))
        <div class="row">
            <div class="col-md-2 col-sm-12">
                Verified by:
            </div>
            <div class="col-md col-sm-12">
                <strong>{{$cert_details->facility_verified_by}}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                Verified date:
            </div>
            <div class="col-md col-sm-12">
                <strong>{{\Carbon\Carbon::createFromTimeStamp(strtotime($cert_details->facility_verified_at))->toDayDateTimeString()}}</strong>
            </div>
        </div>
        @endif

        @if(!isset($cert_details->facility_verified_by))
        <p class="card-text mt-3">Click the button below to verify certificate.</p>
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
</div>
@endsection