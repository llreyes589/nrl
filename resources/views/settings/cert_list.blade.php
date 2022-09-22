@extends('layouts.main')

@section('title')
NRL - Facilities
@endsection

@section('content')



@if(Session::has('message'))
<div class="alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> Settings</h1>
</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#certificate">Certificates</a>
    </li>

</ul>


<!-- Tab panes -->
<div class="tab-content mt-4">
    <div class="tab-pane container active" id="certificate">
        <div class="row">
            <table class="table table-light" id="certificates_list">
                <thead>
                    <tr>
                        <th>SDTL - Cycle</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settings as $setting)
                    <tr>
                        <td>{{$setting->pt->sdtl}} - {{$setting->pt->cycle}}</td>
                        <td>{{$setting->created_at}}</td>
                        <td><a href="{{route('settings.show', $setting->id)}}" class="btn btn-info btn-sm">Update</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>



@endsection

@section('javascript')
<script>

</script>
@endsection