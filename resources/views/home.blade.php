@extends('layouts.main')

@section('title')
NRL - Dashboard
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<div class="card mb-4 py-3 border-left-success">
    <div class="card-body">
        <h4>Announcements:</h4>
        <hr>
        <table class="table table-light" id="a_table">
            <thead class="thead-light">
                <tr>
                    <th>Title</th>
                    <th>Date added</th>
                    <th>Show</th>
                </tr>
            </thead>
            <tbody>
                @foreach($announcements as $a)
                <tr>
                    <td>{{$a->title}}</td>
                    <td>{{$a->created_at}}</td>
                    <td><button class="btn btn-primary" type="button" onclick="viewAnnouncement('{{$a}}')">View</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label for="title">Title</label>
                    <div id="title" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label for="body">Body</label>
                    <textarea id="body" cols="30" rows="4" class="form-control" readonly></textarea>
                </div>
                <div class="form-group">
                    <label for="attachment">Attachment</label>
                    <div id="attachment" class="form-control"></div>
                </div>
                <button class="btn btn-primary" id="done-btn" type="button">Done</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(function() {
        $('#a_table').DataTable();


    })

    const viewAnnouncement = (a) => {
        const json = JSON.parse(a)
        console.log(json)

        $('#myModal').modal()
        $('#title').text(json.title)
        $('#body').text(json.body)
        $('#attachment').prepend(`<a target="_blank" href="/storage/${json.attachment}">Attachment</a>`)
    }

    $('#done-btn').click(function(e) {
        e.preventDefault()
        $('#myModal').modal('hide')
    })

    $('#myModal').on('hide.bs.modal', function(e) {
        $('#title').empty()
        $('#body').empty()
        $('#attachment').empty()
    })
</script>
@endsection