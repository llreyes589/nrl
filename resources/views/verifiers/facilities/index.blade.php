@extends('layouts.main')

@section('title')
NRL - Facilities
@endsection

@section('content')



    @if(Session::has('message'))
    <div class="alert {{session('classname')}}">
        {{session('message')}}
    </div>
    @endif

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
                                <a class="btn btn-success btn-sm" href="{{route('verifiers.facilities.show', $facility->id)}}"><i class="fa fa-search"></i> View</a>
                                @role('head')
                                    @if(isset($facility->certificate))
                                        @if(isset($facility->certificate->approved_by))

                                        @if(isset($facility->certificate->issued_by))
                                            <form action="{{route('verifiers.facilities.emailFacility', ['id' => $facility->id, 'cert_id' => $facility->certificate->id])}}" method="post">
                                                @csrf
                                                <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-paper-plane"></i> Resend Cert</button>
                                            </form>
                                        @else
                                            <form action="{{route('verifiers.facilities.emailFacility', ['id' => $facility->id, 'cert_id' => $facility->certificate->id])}}" method="post">
                                                @csrf
                                                <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-certificate"></i> Issue Cert</button>
                                            </form>                                        
                                        @endif
                                        @endif
                                    @endif
                                @endrole
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
