@extends('layouts.main')

@section('title')
NRL - Facilities
@endsection

@section('content')

    <!-- MODAL -->
    <div id="form-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">New Facility</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('facilities.createOrUpdate')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="" class="text-uppercase">Accreditation Number:</label>
                            <input class="form-control" type="text" name="accreditation_no" placeholder="Enter accreditation number">
                        </div>
                        <div class="form-group">
                            <label for="" class="text-uppercase">Name:</label>
                            <input class="form-control" type="text" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="" class="text-uppercase">Address:</label>
                            <input class="form-control" type="text" name="address" placeholder="Enter address">
                        </div>
                        <div class="form-group">
                            <label for="" class="text-uppercase">City:</label>
                            <input class="form-control" type="text" name="city" placeholder="Enter city">
                        </div>
                        <div class="form-group">
                            <label for="" class="text-uppercase">contact number:</label>
                            <input class="form-control" type="text" name="contact_no" placeholder="Enter contact number">
                        </div>
                        <div class="form-group">
                            <label for="" class="text-uppercase">email address:</label>
                            <input class="form-control" type="email" name="email" placeholder="Enter email address">
                        </div>
                        <div class="form-group">
                            <label for="" class="text-uppercase">lab email address:</label>
                            <input class="form-control" type="email" name="lab_email" placeholder="Enter lab email address">
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    <!-- END MODAL -->

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Facilities</h1>
        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#form-modal">Add new</button>
    </div>

    @if(Session::has('message'))
    <div class="alert {{session('classname')}}">
        {{session('message')}}
    </div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <div class="col">
            
            <table class="table table-light" id="facility_table">
                <thead class="thead-light">
                    <tr>
                        <th>Accreditation Number</th>
                        <th>Name</th>
                        <th>Date Added</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facilities as $facility)
                        <tr>
                            <td>{{$facility->accreditation_no}}</td>
                            <td>{{$facility->name}}</td>
                            <td>{{$facility->created_at}}</td>
                            <td>
                                <form action="{{ route('facilities.destroy', $facility) }}" method="POST">
                                    <button class="btn btn-primary btn-sm" type="button" onclick="selectFacility({{$facility}})">EDIT</button>
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
    
@endsection

@section('javascript')
<script>
    function selectFacility(facility){
        for (let [key, value] of Object.entries(facility)) {
            $('input[name="'+key+'"]').val(value);
        }
        $('#form-modal').modal('show')
    }
    $(document).ready( function () {
        $('#facility_table').DataTable();
    } );
</script>
@endsection
