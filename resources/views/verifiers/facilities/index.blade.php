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

    @if(isset($req))
    {{$req['filter']}}
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Facilities</h1>
    </div>
    <div class="row d-flex justify-content-end">
        <div class="col-md-4 col-sm-12">
            <form method="get" action="{{route('verifiers.facilities.index')}}">
                @csrf
                <div class="form-group">
                    <label for="filter_by">Filter by</label>
                    <select id="filter_by" class="custom-select" name="filter" value="{{request()->filter}}" required>
                        <option value="">--Select here--</option>
                        <option value="R">Region</option>
                        <option value="A">Name (Alphabetical)</option>
                    </select>
                    <p></p>
                    <select id="region" class="custom-select" name="region" value="{{request()->region}}" style="display:none;">
                        @foreach($regions as $region)
                            <option value="{{$region->id}}">{{$region->name}}</option>
                        @endforeach
                    </select>
                    <select id="alphabet" class="custom-select" name="alphabet" value="{{request()->alphabet}}" style="display:none;">
                        @foreach(range('A', 'Z') as $alphabet)
                            <option value="{{$alphabet}}">{{$alphabet}}</option>
                        @endforeach
                    </select>
                    <p></p>
                    <button class="btn btn-primary" type="submit">Submit</button>
                    @if(isset(request()->filter))
                    <a href="{{route('verifiers.facilities.index')}}" class="btn btn-info" type="button">Show All</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col">
            
            <div class="table-responsive">

                <table class="table table-light" id="facility_table">
                    <thead class="thead-light">
                        <tr>
                            <th>Accreditation Number</th>
                            <th>Name</th>
                            <th>Region</th>
                            <th>Cert Status</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facilities as $facility)
                            <tr>
                                <td>{{$facility->accreditation_no}}</td>
                                <td>{{$facility->name}}</td>
                                <td>{{$facility->region_details->name}}</td>
                                <td>
                                    <p>
                                        @if(isset($facility->certificate))
                                            @role('encoder')
                                                @if(isset($facility->certificate->prepared_by))
                                                    <span class="text-success">Prepared Certificate</span>
                                                @else
                                                    <span class="text-info">For Preparation</span>
                                                @endif
                                            @endrole
                                            @role('verifier')
                                                @if(isset($facility->certificate->prepared_by))
                                                    @if(isset($facility->certificate->verified_by))
                                                        <span class="text-success">Verified</span>
                                                    @else
                                                        <span class="text-info">For Verification</span>
                                                    @endif
                                                @else
                                                    <span class="text-success">Prepared Certificate</span>
                                                @endif
                                            @endrole
                                            @role('head')
                                                @if(!isset($facility->certificate->prepared_by))
                                                    <span class="text-success">For Verification</span>
                                                @else
                                                    
                                                    @if(isset($facility->certificate->verified_by))
                                                        
                                                        @if(isset($facility->certificate->approved_by))
                                                            <span class="text-success">Approved</span>
                                                        @else
                                                            <span class="text-success">For Approval</span>
                                                        @endif
                                                    @else
                                                        <span class="text-success">For Verification</span>
                                                    @endif
                                                @endif
                                                
                                            @endrole
                                        @else
                                            <span class="text-info">Cert preparation</span>
                                        @endif
                                    </p>
                                </td>
                                <td>{{$facility->created_at}}</td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{route('verifiers.facilities.show', $facility->id)}}"><i class="fa fa-search"></i> View</a>
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
        var table = $('#facility_table').DataTable({
            responsive: true
        });

        $('#filter_by').change(function({target}){
            if(target.value === 'R'){
                $('#region').show()
                $('#alphabet').hide()
            }else if(target.value === 'A'){
                $('#region').hide()
                $('#alphabet').show()
            }else{
                $('#region').hide()
                $('#alphabet').hide()

            }
            console.log(target.value)
        })
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const filter = urlParams.get('filter')
        if(filter){
            $('#filter_by').val(filter)
            if(filter === 'R'){
                $('#region').show()
                $('#alphabet').hide()
            }else if(filter === 'A'){
                $('#region').hide()
                $('#alphabet').show()
            }else{
                $('#region').hide()
                $('#alphabet').hide()

            }
        }
    } );
</script>
@endsection
