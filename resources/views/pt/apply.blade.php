@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Program Application
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> PT Application <u>(SDTL-{{$pt->sdtl}} | Cycle-{{$pt->cycle}})</u></h1>
    <a href="{{route('proficiency-testing.index')}}" class="btn btn-danger">Cancel</a>

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

<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 for="cycle">PT Details</h4>
                @if($application)
                <span class="badge badge-pill badge-success">Applied</span>
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
            </div>
        </div>

    </div>
    <div class="col-md col-sm-12">
        <div class="card">
            <div class="card-body">
                <form method="post" id='form' action="">
                    @csrf
                    <h4 for="cycle">Test Method Used</h4>
                    <hr>
                    <div class="custom-control custom-checkbox">
                        <input id="immunoassay" class="custom-control-input" type="checkbox" name="" value="immunoassay">
                        <label for="immunoassay" class="custom-control-label">Immunoassay Test Kit</label>
                    </div>
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
                    <div class="custom-control custom-checkbox">
                        <input id="instrumented" class="custom-control-input" type="checkbox" name="" value="true">
                        <label for="instrumented" class="custom-control-label">Instrumented</label>
                    </div>
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
                    <br>
                    @if(!$application)
                    <button class="btn btn-primary" id="submit" type="submit">Submit</button>
                    @endif
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