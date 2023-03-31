@extends('layouts.main')

@section('title')
NRL - Certificate Templates
@endsection

@section('content')



@if(Session::has('message'))
<div class="alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> Cert Templates</h1>
</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#certificate">{{$template->year}} Certificate</a>
    </li>

</ul>


<!-- Tab panes -->
<div class="tab-content mt-4">
    <div class="tab-pane container active" id="certificate">
        <div class="row">
            <div class="col-md-5 col-sm-12">

                <form method="post" action="{{route('certificate-templates.update', $template->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="theme">Theme</label>
                        <textarea id="theme" class="form-control @error('certificate_theme') is-invalid @enderror" name="certificate_theme" rows="3">{{$template->certificate_theme}}</textarea>
                        @error('certificate_theme')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="given_on">Given on</label>
                        <input id="given_on" class="form-control" value="{{$template->certificate_given_at}}" name="certificate_given_at" type="date">
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input id="year" class="form-control" value="{{$template->year}}" name="year" type="number">
                    </div>
                    <!-- Divider -->
                    <hr class="sidebar-divider d-none d-md-block">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a class="btn btn-danger" href="{{route('certificate-templates.index')}}">Cancel</a>
                </form>
            </div>
            <div class="col-md col-sm-12" id='preview' style=" max-height:300px;  overflow-y: scroll;">
                <div class="card shadow mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Preview</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <style>
                                            table {
                                                font-family: Times New Roman;
                                            }

                                            table tr {
                                                text-align: center;
                                            }

                                            p.header {
                                                font-size: 18pt;

                                            }

                                            p.cursive {
                                                font-family: Verdana, sans-serif;
                                                font-size: 16pt;
                                                font-style: italic;

                                            }

                                            p.name {
                                                font-family: Arial;
                                                font-size: 12pt;
                                            }

                                            p.name {
                                                font-family: Arial;
                                                font-size: 11pt;
                                            }
                                        </style>
                                        <table>
                                            <tr>
                                                <td colspan="3"></td>
                                            </tr>
                                            <tr style="text-align:center; ">
                                                <td colspan="3" style="width:100%">
                                                    <br>
                                                    <br>
                                                    <p class="cursive">This</p>
                                                    <br>
                                                </td>
                                            </tr>
                                            <tr style="text-align:center; ">
                                                <td colspan="3">
                                                    <p style="font-size: 26pt; font-weight: bold;">CERTIFICATE OF PROFICIENCY</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                            </tr>
                                            <tr style="text-align:center;">
                                                <td style="width:70%" colspan="3">
                                                    <br>
                                                    <br>
                                                    <p class="cursive">is hereby presented to</p>
                                                    <p style="font-size: 20pt; font-weight: bold;">
                                                        NAME
                                                    </p>
                                                    <p style="font-size: 11pt;">
                                                        ADDRESS
                                                    </p>
                                                    <br>
                                                    <p style="font-size: 14pt;">
                                                        Certificate No. 123
                                                    </p>
                                                    <p style="font-size: 14pt;">
                                                        Accreditation No. 456
                                                    </p>
                                                    <br>
                                                    <p class="cursive">for successful participation, passing and achieving</p>
                                                    <p style="font-size: 22pt; font-weight: bold;">
                                                        Very Satisfactory
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr style="text-align:center;">
                                                <td style="width:80%" colspan="3">
                                                    <p class="cursive">performance in the</p>
                                                    <p style="font-size: 20pt; font-weight: bold;">“
                                                        {{$template->certificate_theme}}
                                                        ”
                                                    </p>
                                                    <br>
                                                    <?php
                                                    $given = \Carbon\Carbon::parse($template->certificate_given_at);
                                                    \Carbon\Carbon::setToStringFormat('jS \d\a\y \o\f F Y');
                                                    ?>
                                                    <p class="cursive">
                                                        Given this {{$given}}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:40%">
                                                </td>
                                                <td style="width:40%">
                                                </td>
                                                <td style="width:20%">

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

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

</script>
@endsection