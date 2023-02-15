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

<!-- Tab panes -->
<div class="tab-content mt-4">
    <div class="tab-pane container active" id="certificate">
        <div class="row">
            <table class="table table-light">
                <thead class="thead-light">
                    <tr>
                        <th>Theme</th>
                        <th>Given at</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $t)
                    <tr>
                        <td>{{$t->certificate_theme}}</td>
                        <td>{{$t->certificate_given_at}}</td>
                        <td>{{$t->year}}</td>
                        <td>
                            <a class="btn btn-primary" href="{{route('certificate-templates.show', $t->id)}}">Edit</a>
                        </td>
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