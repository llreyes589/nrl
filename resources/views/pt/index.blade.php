@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Program
@endsection

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> Proficiency Testings</h1>
    @role('admin')
    <a href="{{route('proficiency-testing.create')}}" class="btn btn-primary">Add new</a>
    @endrole

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
                        @role('Facility')
                        <th>Status</th>
                        @endrole
                        <th>Date Added</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pts as $pt)
                    <tr>
                        <td>{{$pt->sdtl}}</td>
                        <td>{{$pt->cycle }}
                            <!-- <pre>{{$pt->applications->where('user_id', Auth::id())}}</pre> -->
                        </td>
                        @role('Facility')

                        <td>
                            @if(count($pt->applications->where('user_id', Auth::id())) > 0)
                            <span class="badge badge-pill badge-success">Applied</span>
                            @endif
                        </td>

                        @endrole
                        <td>{{$pt->created_at}}</td>
                        <td>
                            @role('admin')
                            <a href="{{route('proficiency-testing.edit', $pt->id)}}" class="btn btn-info">Edit</a>
                            @endrole
                            @role('Facility')
                            <a href="{{route('proficiency-testing.facility.apply', $pt->id)}}" class="btn btn-success">View/Apply</a>
                            @endrole

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