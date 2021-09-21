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
    </div>
</div>
@endsection