@extends('layouts.main')

@section('title')
NRL - Announcements
@endsection

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> @if(isset($announcement->id)) Edit @else Create @endif Announcements</h1>
    <a class="btn btn-danger" href="{{route('announcements.index')}}" data-toggle="modal">Cancel</a>


</div>



@if($errors->any())

<div class="alert alert-danger" role="alert">
    {!! implode('', $errors->all('<div>:message</div>')) !!}
</div>
@endif
<form method="post" action="{{isset($announcement->id) ? route('announcements.update', $announcement->id) : route('announcements.store')}}" enctype="multipart/form-data">
    @csrf
    @if(isset($announcement->id))
    @method('PUT')
    @endif
    <div class="form-group">
        <label for="title">Title</label>
        <input id="title" class="form-control" type="text" name="title" value="{{  isset($announcement->id) ? $announcement->title : old('title') }}">
    </div>

    <div class="form-group">
        <label for="body">Body</label>
        <textarea id="body" class="form-control" name="body" rows="3">{{ isset($announcement->id) ? $announcement->body : old('body') }}</textarea>
    </div>

    @if(isset($announcement->id))
    @if($announcement->attachment)
    <a target="_blank" href="/storage/{{$announcement->attachment}}" class="mb-3">Uploaded attachment</a>
    <hr />
    @endif
    @endif

    <div class="form-group ">
        <label for="announcement_attachment">Attachment</label>
        <input id="announcement_attachment" class="form-control-file" type="file" name="announcement_attachment">
    </div>

    @if(isset($announcement->id))
    <button class="btn btn-success" type="submit">Update</button>
    @else
    <button class="btn btn-primary" type="submit">Save</button>
    @endif

</form>
@endsection

@section('javascript')
<script>
    $(function() {
        $('#announcement_table').DataTable()
    })
</script>
@endsection