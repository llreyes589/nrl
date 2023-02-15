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
<div class="row d-flex justify-content-end">
    <div class="col-md-4 col-sm-12">
        <form id="searchForm">
            <div class="form-group">
                <label for="filter_by">Filter by</label>
                <select id="filter_by" class="custom-select" name="filter" required>
                    <option value="">--Select here--</option>
                    <option value="R">Region</option>
                    <option value="A">Name (Alphabetical)</option>
                </select>
                <p></p>
                <select id="region" class="custom-select" name="region" style="display:none;">
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
                <button class="btn btn-info" type="button" id="show_all" style="display:none;">Show All</button>
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
            </table>
        </div>
    </div>

</div>

@endsection

@section('javascript')
<script>
    function hasKey(fac_id, cert) {
        if (cert != null) {
            if (cert.approved_by != null) {

                if (cert.issued_by != null) {
                    return `<form action="/verifier/facilities/` + fac_id + `/certificate/` + cert.id + `/emailFacility" method="post">
                                    @csrf
                                    <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-paper-plane"></i> Resend cert</button>
                                </form> `
                }
                return `<form action="/verifier/facilities/` + fac_id + `/certificate/` + cert.id + `/emailFacility" method="post">
                                    @csrf
                                    <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-certificate"></i> Issue Cert</button>
                                </form> `
            } else {
                return ''
            }
        } else {
            return ''
        }
    }

    function renderCertStatus(cert) {
        // console.log("{{ auth()->user()->can('prepare')}}" != 1)
        if (cert != null) {
            if ("{{ auth()->user()->can('prepare')}}" == 1) {
                if (cert.prepared_by) {
                    return `<span class="text-success">Prepared Certificate</span>`
                } else {
                    return `<span class="text-success">For Preparation</span>`
                }
            } else if ("{{ auth()->user()->can('verify')}} " == 1) {
                if (cert.prepared_by) {
                    if (cert.verified_by) {
                        return `<span class="text-success">Verified</span>`
                    } else {
                        return `<span class="text-success">For Verification</span>`
                    }
                } else {
                    return `<span class="text-success">Prepared Certificate</span>`
                }
            } else if ("{{ auth()->user()->can('approve')}}" == 1) {
                if (cert.prepared_by) {
                    if (cert.verified_by) {
                        if (cert.approved_by) {
                            return `<span class="text-success">Approved</span>`
                        } else {
                            return `<span class="text-success">For Approval</span>`
                        }
                    } else {
                        return `<span class="text-success">For Verification</span>`
                    }
                } else {
                    return `<span class="text-success">Cert preparation</span>`
                }
            }
        } else {
            return `<span class="text-success">Cert preparation</span>`
        }


    }
    $(document).ready(function() {
        let fac_data = {}
        var table = $('#facility_table').DataTable({
            responsive: true,
            ajax: {
                url: '/api/facilities',
                dataSrc: 'facilities',
                "data": function(data) {
                    // Read values
                    var filter = $('#filter_by').val();
                    var region = $('#region').val();
                    var alphabet = $('#alphabet').val();

                    // Append to data
                    data.filter = filter;
                    data.region = region;
                    data.alphabet = alphabet;
                    //    return  $.extend(d, fac_data);
                }
            },
            columns: [{
                    data: 'accreditation_no'
                },
                {
                    data: 'name'
                },
                {
                    data: 'region_details.name'
                },
                {
                    data: 'id',
                    render: (id, type, row, meta) => {
                        // renderCertStatus(row)
                        // return row.certificate
                        return renderCertStatus(row.certificate)
                    }
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'id',
                    render: (id, type, row, meta) => {
                        return `
                            <a class="btn btn-success btn-sm" href="facilities/` + id + `">
                                <i class="fa fa-search"></i> View
                            </a>
                            ` + hasKey(id, row.certificate) + `
                                                                   
                            `
                    }
                },
            ]
        });

        $('#filter_by').change(function({
            target
        }) {
            if (target.value === 'R') {
                $('#region').show()
                $('#show_all').show()
                $('#alphabet').hide()
            } else if (target.value === 'A') {
                $('#show_all').show()
                $('#region').hide()
                $('#alphabet').show()
            } else {
                $('#show_all').hide()
                $('#region').hide()
                $('#alphabet').hide()

            }
        })


        $('#alphabet').change(function() {
            table.ajax.reload();
        });

        $('#region').change(function() {
            table.ajax.reload();
        });

        $('#show_all').click(function() {
            window.location.reload();

        })
    });
</script>
@endsection