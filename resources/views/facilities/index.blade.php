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
                            <label for="region" class="text-uppercase">Region</label>
                            <select id="region" class="custom-select" name="region_id">
                                @foreach($regions as $region)
                                    <option value="{{$region->id}}">{{$region->name}}</option>
                                @endforeach
                            </select>
                        </div>         
                        <div class="form-group">
                            <label for="region" class="text-uppercase">Head of Laboratory</label>
                            <input class="form-control" type="text" name="head_of_lab" placeholder="Enter Head of Laboratory">
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
    <div class="row d-flex justify-content-end">
        <div class="col-md-4 col-sm-12">
            
            <form method="get" action="{{route('facilities.index')}}" id=" id="searchForm">
                @csrf
                <div class="form-group">
                    <label for="filter_by">Filter by</label>
                    <select id="filter_by" class="custom-select" name="filter_by" required>
                        <option value="">--Select here--</option>
                        <option value="R">Region</option>
                        <option value="A">Name (Alphabetical)</option>
                    </select>
                    <p></p>
                    <select id="region_select" class="custom-select" name="region" style="display:none;">
                        @foreach($regions as $region)
                            <option value="{{$region->id}}">{{$region->name}}</option>
                        @endforeach
                    </select>
                    <select id="alphabet" class="custom-select" name="alphabet" style="display:none;">
                        @foreach(range('A', 'Z') as $alphabet)
                            <option value="{{$alphabet}}">{{$alphabet}}</option>
                        @endforeach
                    </select>
                    <p></p>
                    <button class="btn btn-info" type="submit" id="show_all" style="display:none;">Show All</button>
                </div>
            </form>
        </div>
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
                <table class="table table-light" id="facility_table">
                    <thead class="thead-light">
                        <tr>
                            <th>Accreditation Number</th>
                            <th>Name</th>
                            <th>Region</th>
                            <th>Cert Status</th>
                            <th>Date Added</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        @foreach($facilities as $facility)
                            <tr>
                                <td>{{$facility->accreditation_no}}</td>
                                <td>{{$facility->name}}</td>
                                <td>{{$facility->region_details->name}}</td>
                                <td>{{$facility->created_at}}</td>
                                <td>
                                    
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody> -->
                </table>
            </div>
        </div>
        
    </div>
    
@endsection

@section('javascript')
<script>
    function selectFacility(id){
        $.get( '/api/facilities/'+id, function( data ) {
            for (let [key, value] of Object.entries(data)) {
                $('input[name="'+key+'"]').val(value);
                $('select[name="'+key+'"]').val(value);
            }
            $('#form-modal').modal('show')
        });
    }
    function renderCertStatus(cert){
        // console.log("{{ auth()->user()->can('prepare')}}" != 1)
        if(cert != null){
            if("{{ auth()->user()->can('prepare')}}" == 1){
                if(cert.prepared_by){
                    return `<span class="text-success">Prepared Certificate</span>`
                }else{
                    return `<span class="text-success">For Preparation</span>`
                }
            }else if("{{ auth()->user()->can('verify')}} " == 1){
                if(cert.prepared_by){
                    if(cert.verified_by){
                        return `<span class="text-success">Verified</span>`
                    }else{
                        return `<span class="text-success">For Verification</span>`
                    }
                }else{
                    return `<span class="text-success">Prepared Certificate</span>`
                }
            }else if("{{ auth()->user()->can('approve')}}" == 1){
                if(cert.prepared_by){
                    if(cert.verified_by){
                        if(cert.approved_by){
                            return `<span class="text-success">Approved</span>`
                        }else{
                            return `<span class="text-success">For Approval</span>`
                        }
                    }else{
                        return `<span class="text-success">For Verification</span>`
                    }
                }else{
                    return `<span class="text-success">Cert preparation</span>`
                }
            }else{
                if(cert.prepared_by){
                    if(cert.verified_by){
                        if(cert.approved_by){
                            return `<span class="text-success">Approved</span>`
                        }else{
                            return `<span class="text-success">For Approval</span>`
                        }
                    }else{
                        return `<span class="text-success">For Verification</span>`
                    }
                }else{
                    return `<span class="text-success">Cert preparation</span>`
                }
            }
        }else{
            return `<span class="text-success">Cert preparation</span>`
        }

            
    }
    $(document).ready( function () {
        var table = $('#facility_table').DataTable({
            responsive: true,
            ajax: {
                url: '/api/facilities',
                dataSrc: 'facilities',
                "data": function ( data ) {
                    // Read values
                    var filter = $('#filter_by').val();
                    var region = $('#region_select').val();
                    var alphabet = $('#alphabet').val();

                    // Append to data
                    data.filter = filter;
                    data.region = region;
                    data.alphabet = alphabet;
                //    return  $.extend(d, fac_data);
                }
            },
            columns: [
                { data: 'accreditation_no' },
                { data: 'name' },
                { data: 'region_details.name' },
                { data: 'id', render: (id, type, row, meta)=>{
                    // return row.certificate
                    // renderCertStatus(row)
                    // return row.certificate
                    return renderCertStatus(row.certificate)
                } },
                { data: 'created_at' },
                { data: 'id', render: (id)=>{
                    return `<form action="/facilities/`+id+`" method="POST">
                                @role('verifier')
                                <a class="btn btn-success btn-sm" href="/verifier/facilities/`+id+`"><i class="fa fa-search"></i> View</a>
                                @endrole
                                <button class="btn btn-primary btn-sm" type="button" onclick="selectFacility(`+id+`)">EDIT</button>
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger">DEL</button>
                            </form>`
                } },
            ]
        });
        
        $('#filter_by').change(function({target}){
            if(target.value === 'R'){
                $('#region_select').show()
                $('#show_all').show()
                $('#alphabet').hide()
            }else if(target.value === 'A'){
                $('#show_all').show()
                $('#region_select').hide()
                $('#alphabet').show()
            }else{
                $('#show_all').hide()
                $('#region_select').hide()
                $('#alphabet').hide()

            }
        })
        $('#alphabet').change(function(){
            table.ajax.reload();
        });

        $('#region_select').change(function(){
            table.ajax.reload();
        });

        $('#show_all').click(function(){
            window.location.reload();
            
        })
    } );
</script>
@endsection
