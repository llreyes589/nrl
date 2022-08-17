@extends('layouts.main')

@section('title')
NRL - Proficiency Testing Applications List
@endsection

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> PT Applications</h1>


</div>
@if(Session::has('message'))
<div class=" alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif

<!-- Content Row -->
<div class="row">
    <div class="col">
        <div class="table-responsive">
            <table class="table table-light" id="pt_table">
                <thead class="thead-light">
                    <tr>
                        <th>Facility</th>
                        <th>SDTL</th>
                        <th>Cycle</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>{{$app->user->name}}</td>
                        <td>{{$app->pt->sdtl}}</td>
                        <td>{{$app->pt->cycle}}</td>
                        <td>
                            <!-- specimen sent -->
                            @if($app->specimen_sent)
                            <span class="badge badge-pill badge-info">Specimen sent</span>
                            @endif
                            <!-- verified payment -->
                            @if($app->verified_payment === 1)
                            <span class="badge badge-pill badge-success">Payment verified</span>
                            @endif
                        </td>
                        <td>{{$app->created_at}}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if($app->receipt_path )
                                    @if($app->verified_payment != 1)
                                    <form action="{{route('proficiency-testing.applicants.verifyPayment', ['id' => $app->pt->id, 'application_id' => $app->id])}}" method="post">
                                        @csrf
                                        @method('PUT')

                                        <button class="dropdown-item btn btn-primary" type="submit"><i class="fa fa-check fa-sm"></i> Verify payment</button>
                                    </form>
                                    @endif
                                    <a href="/storage/{{$app->receipt_path}}" target="_blank" class="dropdown-item btn btn-success"><i class="fas fa-receipt fa-sm"></i> View Receipt</a>
                                    @if($app->specimen_sent <=0) <form action="{{route('proficiency-testing.applicants.sendSpecimen', ['id' => $app->pt->id, 'application_id' => $app->id])}}" method="post">
                                        @csrf
                                        @method('PUT')

                                        <button class="dropdown-item btn btn-info" type="submit"><i class="fa fa-paper-plane fa-sm"></i> Send Specimen</button>
                                        </form>
                                        @endif
                                        @endif

                                </div>
                            </div>

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
    $(function() {
        $('#pt_table').DataTable()
    })
</script>
@endsection