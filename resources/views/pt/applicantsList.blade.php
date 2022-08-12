@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Applications List
@endsection

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> PT Applications</h1>


</div>
@if(Session::has('message'))
<div class=" alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<!-- Content Row -->
<div class="row">
    <div class="col">
        <div class="table-responsive">
            <table class="table table-light" id="pt_table">
                <thead class="thead-light">
                    <tr>
                        <th>Facility</th>
                        <th>SDTL</th>
                        <th>Cycle</th>
                        <th>Date Added</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>{{$app->user->name}}</td>
                        <td>{{$app->pt->sdtl}}</td>
                        <td>{{$app->pt->cycle}}</td>
                        <td>{{$app->created_at}}</td>
                        <td><button class="btn btn-primary" type="button">Approve</button></td>
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
    $(function() {
        $('#pt_table').DataTable()
    })
</script>
@endsection