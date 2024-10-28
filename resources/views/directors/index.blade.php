@extends('layouts.main')

@section('title')
NRL - Announcements
@endsection

@section('content')
<!-- Page Heading -->
<div class=" mb-4">
    <h1 class="h3 mb-0 text-gray-800 mb-3">Final Signatories</h1>
    @if(Session::has('message'))
    <div class=" alert {{session('classname')}}">
        {{session('message')}}
    </div>
    @endif

    <div class="row">
        <div class="col-md-5 col-sm-12">

            <div class="card">
                <form
                    enctype="multipart/form-data"
                    method="POST" action="{{ route('directors.store') }}">
                    <div class="card-body">
                        @csrf
                        @if(isset($director->id))
                        <input type="hidden" name="id" value="{{$director->id}}" />
                        @endif

                        <div class="mb-2">
                            <label for="" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                id="name"
                                aria-describedby="helpId"
                                value="{{isset($director->id) ? $director->name : old('name')}}"
                                placeholder="Enter name here" />
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="" class="form-label">Position</label>
                            <input
                                type="text"
                                class="form-control  @error('position') is-invalid @enderror"
                                name="position"
                                id="position"
                                aria-describedby="helpId"
                                value="{{isset($director->id) ? $director->position : old('name')}}"
                                placeholder="Enter position here" />
                            @error('position')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="" class="form-label">Designation</label>
                            <input
                                type="text"
                                class="form-control  @error('designation') is-invalid @enderror"
                                name="designation"
                                id="designation"
                                aria-describedby="helpId"
                                value="{{isset($director->id) ? $director->designation : old('designation')}}"
                                placeholder="Enter designation here" />
                            @error('designation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="signature" class="form-label">Signature</label>
                            <input
                                type="file"
                                class="form-control  @error('signature') is-invalid @enderror"
                                name="signature"
                                id="signature"
                                placeholder=""
                                accept="image/png"
                                value="{{isset($director->id) ? $director->signature : old('signature')}}"
                                aria-describedby="fileHelpId"
                                @if(!isset($director->id))
                            required
                            @endif
                            />
                            @error('signature')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <div id="fileHelpId" class="form-text">Maximum of image size must be 2mb with png extension.</div>
                            @if(isset($director->id))

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Signature preview</h5>
                                    <img
                                        src="{{asset('storage/' . $director->signature)}}"
                                        class="img-fluid rounded-top"
                                        alt="" />
                                </div>
                            </div>
                            @endif


                        </div>


                    </div>
                    <div class="card-footer">
                        @if(isset($director->id))
                        <button class="btn btn-info btn-sm" type="submit">Update</button>
                        <a href="{{route('directors.index')}}" class="btn btn-danger btn-sm" type="button">Cancel</a>
                        @else
                        <button class="btn btn-primary btn-sm" type="submit">Save</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md col-sm-12">
            <div class="table-responsive">
                <table class="table table-light" id="directors_table">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Designation</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($directors as $director)
                        <tr>
                            <td>{{$director->name}}</td>
                            <td>{{$director->position}}</td>
                            <td>{{$director->designation}}</td>
                            <td>{{$director->created_at}}</td>
                            <td nowrap>
                                <a class="btn btn-info btn-sm" href="{{route('directors.show', ['director' => $director])}}">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="handleDeleteDirector('{{$director->id}}')">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">No record found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @endsection

    @section('javascript')
    <script>
        const deleteDirectorModalBody = $('#deleteDirectorModalBody')

        $('#directors_table').DataTable({
            responsive: true
        });

        const handleDeleteDirector = function(id) {

            gmodal.modal()
            gmodalDialog.addClass('modal-md')
            gmodalTitle.text('Confirm Delete?')
            deleteDirectorModalBody.show()
            deleteDirectorModalBody.attr('action', `/directors/${id}`);

        }
    </script>
    @endsection