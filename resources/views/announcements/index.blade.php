@extends('layouts.main')

@section('title')
NRL - Announcements
@endsection

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> Announcements</h1>
    <a class="btn btn-primary" href="{{\route('announcements.create')}}">Add new</a>


</div>
@if(Session::has('message'))
<div class=" alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif
<table class="table table-light" id="announcement_table">
    <thead class="thead-light">
        <tr>
            <th>Title</th>
            <th>Date Added</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($announcements as $a)
        <tr>
            <td>{{$a->title}}</td>
            <td>{{$a->created_at}}</td>
            <td>
                <form action="{{route('announcements.changeStatus', $a->id)}}" method="post">
                    @csrf
                    @method('PUT')
                    <a class="btn btn-primary" href="{{route('announcements.edit', $a->id)}}">View/Edit</a>
                    @if($a->publish === 1)
                    <button class="btn btn-secondary" type="submit">Hide</button>
                    @else
                    <button class="btn btn-success" type="submit">Publish</button>

                    @endif
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
@endsection

@section('javascript')
<script>
    $(function() {
        $('#announcement_table').DataTable()
    })
</script>
@endsection