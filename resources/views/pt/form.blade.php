@extends('layouts.main')

@section('title')
NRL - New Proficiency Testing Program
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> {{isset($pt->id) ? "Update" : 'New'}} Proficiency Testings</h1>
    <a href="{{route('proficiency-testing.index')}}" class="btn btn-danger">Cancel</a>

</div>
@if(Session::has('message'))
<div class=" alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<div class="card">

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form method="post" id='form' action="{{isset($pt->id) ? route('proficiency-testing.update', $pt) :route('proficiency-testing.store')}}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($pt->id))
                    @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="sdtl">SDTL</label>
                        <input id="sdtl" class="form-control @error('sdtl') is-invalid @enderror" type="text" value="{{ isset($pt->id) ? $pt->sdtl : old('sdtl') }}" name="sdtl" placeholder="Enter SDTL">

                        @error('sdtl')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cycle">Cycle</label>
                        <input id="cycle" class="form-control @error('cycle') is-invalid @enderror" type="text" value="{{  isset($pt->id) ? $pt->cycle : old('cycle') }}" name="cycle" placeholder="Enter Cycle">
                        @error('cycle')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cert_validity">Certificate Validity</label>
                        <input id="cert_validity" class="form-control" type="date" name="cert_validity" value="{{  isset($pt->id) ? $pt->cert_validity : old('cert_validity') }}">
                    </div>
                    <p for="total_amount">Total Amount</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="total_amount">P</span>
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


                    <button class="btn btn-primary" id="submit" type="submit">{{isset($pt->id) ? "Update" : 'Save'}}</button>
                </form>
            </div>
            @if($pt->instruction_file_path)
            <div class="col-md-6">
                <h4>Uploaded instruction file:</h4>
                <img class="img-fluid" src="/storage/{{$pt->instruction_file_path}}" alt="">
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(function() {})
</script>
@endsection