@extends('layouts.main')

@section('title')
NRL - Create Proficiency Testing Program
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> {{isset($pt->id) ? "Update" : 'Create'}} Proficiency Testings</h1>
    <a href="{{route('proficiency-testing.index')}}" class="btn btn-danger">Cancel</a>

</div>
@if(Session::has('message'))
<div class=" alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<div class="card">

    <div class="card-body">
        <form method="post" id='form' action="{{isset($pt->id) ? route('proficiency-testing.update', $pt) :route('proficiency-testing.store')}}" enctype="multipart/form-data">
            @csrf
            @if(isset($pt->id))
            @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-6">


                    <p for="sdtl">Proficiency Testing Year</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="sdtl">PT - </span>
                        </div>
                        <input type="sdtl" name="sdtl" placeholder="YYYY" aria-label="sdtl" aria-describedby="sdtl" class="form-control @error('sdtl') is-invalid @enderror" value="{{  isset($pt->id) ? $pt->sdtl : old('sdtl') }}">
                        @error('sdtl')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="cycle">Cycle</label>
                        <input id="cycle" class="form-control @error('cycle') is-invalid @enderror" type="text" value="{{  isset($pt->id) ? $pt->cycle : old('cycle') }}" name="cycle" placeholder="Enter Cycle">
                        @error('cycle')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="application_limit">Application limit</label>
                        <input id="application_limit" class="form-control @error('application_limit') is-invalid @enderror" type="number" min="10" value="{{  isset($pt->id) ? $pt->application_limit : old('application_limit') }}" name="application_limit">
                        @error('application_limit')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <p for="total_amount">Total Amount</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="total_amount">PHP</span>
                        </div>
                        <input type="total_amount" name="total_amount" placeholder="1500.00" aria-label="total_amount" aria-describedby="total_amount" class="form-control @error('total_amount') is-invalid @enderror" value="{{  isset($pt->id) ? $pt->total_amount : old('total_amount') }}">
                        @error('total_amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <p></p>
                    <div class="form-group">
                        <label for="my-input">Instruction file</label>
                        <input id="my-input" class="form-control-file" type="file" name="instruction_file">
                    </div>


                </div>
                <div class="col-md-6">
                    @if($pt->instruction_file_path)
                    <h4>Uploaded instruction file:</h4>
                    <img class="img-fluid" src="/storage/{{$pt->instruction_file_path}}" alt="">
                    <hr>
                    @endif
                    <h3>Certificate Setting</h3>
                    <hr>
                    @if(!$pt->cert_setting)
                    <div class="form-group">
                        <label for="certificate_theme">Certificate Title</label>
                        <textarea id="certificate_theme" class="form-control" name="certificate_theme" rows="3" placeholder="Enter title here" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="certificate_given_at">Certificate Given at</label>
                        <input id="certificate_given_at" class="form-control" type="date" name="certificate_given_at" required>
                    </div>

                    <div class="form-group">
                        <label for="director_id">Final Signatory</label>
                        <select id="director_id" class="form-control" name="director_id" required>
                            <option value="">--Please select Final Signatory--</option>
                            @foreach($directors as $director)
                            <option value="{{$director->id}}">{{$director->name}}</option>
                            @endforeach
                        </select>
                        @if(count($directors) <= 0)
                            <small class="text-danger">No Final Signatory/s found. Click here to <a href="{{route('directors.index')}}">add</a>? </small>
                            @endif
                    </div>
                    @else
                    <a href="{{route('settings.show', $pt->cert_setting->id)}}" class="btn btn-primary ">View Certificate Setting</a>
                    <p></p>
                    @endif
                    <div class=" form-group">
                        <label for="cert_validity">Certificate Validity</label>
                        <input id="cert_validity" class="form-control" type="date" name="cert_validity" value="{{  isset($pt->id) ? $pt->cert_validity : old('cert_validity') }}" required>
                    </div>

                </div>
            </div>
            <button class="btn btn-primary" id="submit" type="submit">{{isset($pt->id) ? "Update" : 'Save'}}</button>
        </form>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(function() {})
</script>
@endsection