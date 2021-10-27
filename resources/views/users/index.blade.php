@extends('layouts.main')

@section('title')
NRL - Users
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users</h1>
        <a class="btn btn-primary" type="button" href="{{route('users.create')}}">Add new</a>
    </div>

    @if(Session::has('message'))
    <div class="alert {{session('classname')}}">
        {{session('message')}}
    </div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <div class="col">
            <div class="table-responsive">

                <table class="table table-light" id="users_table">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date Added</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr >
                                
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->created_at}}</td>
                                <td>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" type="button" href="{{route('users.edit', $user)}}">EDIT</a>
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-danger">DEL</button>
                                    </form>
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
    $(document).ready( function () {
        $('#users_table').DataTable({
            responsive:true
        });
    } );
</script>
@endsection
