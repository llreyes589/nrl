@extends('layouts.main')

@section('title')
NRL - Facilities
@endsection

@section('content')


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Facilities</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col">
            
            <table class="table table-light" id="facility_table">
                <thead class="thead-light">
                    <tr>
                        <th>Accreditation Number</th>
                        <th>Name</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facilities as $facility)
                        <tr>
                            <td>{{$facility->accreditation_no}}</td>
                            <td>{{$facility->name}}</td>
                            <td>{{$facility->created_at}}</td>
                            <td>
                                <a class="btn btn-success" href="{{route('verifiers.facilities.show', $facility->id)}}">View</a>
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
    
    $(document).ready( function () {
        $('#facility_table').DataTable();
    } );
</script>
@endsection
