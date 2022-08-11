@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Program
@endsection

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> Proficiency Testings</h1>
    <a href="{{route('proficiency-testing.create')}}" class="btn btn-primary">Add new</a>

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
                        <th>SDTL</th>
                        <th>Cycle</th>
                        <th>Date Added</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pts as $pt)
                    <tr>
                        <td>{{$pt->sdtl}}</td>
                        <td>{{$pt->cycle}}</td>
                        <td>{{$pt->created_at}}</td>
                        <td>
                            <a href="{{route('proficiency-testing.edit', $pt->id)}}" class="btn btn-info">Edit</a>
                            <!-- <button class="btn btn-danger" type="button">Delete</button> -->
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
    $(function() {
        $('#pt_table').DataTable()
    })
</script>
@endsection