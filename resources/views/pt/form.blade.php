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
                <form method="post" id='form' action="{{isset($pt->id) ? route('proficiency-testing.update', $pt) :route('proficiency-testing.store')}}">
                    @csrf
                    @if(isset($pt->id))
                    @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="sdtl">SDTL</label>
                        <input id="sdtl" class="form-control @error('sdtl') is-invalid @enderror" type="text" value="{{ isset($pt->id) ? $pt->sdtl : old('sdtl') }}" name="sdtl">

                        @error('sdtl')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cycle">Cycle</label>
                        <input id="cycle" class="form-control @error('cycle') is-invalid @enderror" type="text" value="{{  isset($pt->id) ? $pt->cycle : old('cycle') }}" name="cycle">
                        @error('cycle')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <button class="btn btn-primary" id="submit" type="submit">{{isset($pt->id) ? "Update" : 'Save'}}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(function() {})
</script>
@endsection