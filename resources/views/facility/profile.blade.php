@extends('layouts.main')

@section('title')
NRL - Profile
@endsection

@section('content')

@if(Session::has('message'))
<div class=" alert {{session('classname')}}">
    {{session('message')}}
</div>
@endif
<form action="{{route('facility.profile.update')}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-3 col-sm-12">
            <div class="card  text-center mb-1">
                <div class="card-body">
                    <!-- <img class="img-thumbnail rounded-circle" src="https://via.placeholder.com/150" alt="">
                    <hr> -->
                    <h4>{{$user->name}}</h4>
                    <p><small>{{$user->username}}</small></p>
                    <button class="btn btn-info btn-sm" type="button" onclick="handleChangePassword('{{$user->id}}')">Change password</button>
                </div>
            </div>

            <div class="card ">
                <div class="card-body">
                    <div class="form-group">
                        <label for="accreditation_no">Accreditation No.:</label>
                        <textarea class="form-control" readonly>{{$user->profile->accreditation_no}}</textarea>
                    </div>
                    <div class="card">
                    <div class="card-body bg-light">
                    <div class="form-group">
                            <label for="lto">LTO File</label>
                            <input id="lto" class="form-control-file" type="file" name="lto">
                        </div>
                        @if($user->profile->lto_file)
                        <hr>
                        <a href="/storage/{{$user->profile->lto_file}}" target="_blank">View LTO File</a>
                        @endif
                    </div>
                    </div>
                    <div class="form-group">
                        
                    </div>
                    <div class="form-group">
                        <label for="region">Region:</label>
                        <textarea class="form-control" readonly>{{$user->profile->region_details->name}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="updated_at">Last Update at:</label>
                        <textarea class="form-control" readonly>{{$user->profile->updated_at}}</textarea>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-8 col-sm-12">
            <h4>Analyst details</h4>


            <div class="row">
                <div class="col-lg col-sm-12">
                    <div class="form-group">
                        <label for="analyst_name">Name:</label>
                        <input type="text" class="form-control" name="analyst_name" value="{{$user->profile->analyst_name}}">
                    </div>
                </div>
                <div class="col-lg col-sm-12">
                    <div class="form-group">
                        <label for="analyst_certificate_no">Certificate Number:</label>
                        <input type="text" class="form-control" name="analyst_certificate_no" value="{{$user->profile->analyst_certificate_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg col-sm-12">
                    @if($user->profile->analyst_certificate_file)
                    <hr>
                    <a href="/storage/{{$user->profile->analyst_certificate_file}}" target="_blank">Uploaded Certificate File</a>
                    <hr>
                    @endif
                    <div class="form-group">
                        <label for="certificate_file">Update Certificate File</label>
                        <input id="certificate_file" class="form-control-file" type="file" name="certificate_file">
                    </div>

                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg col-sm-12">
                    <div class="form-group">
                        <label for="hol">Head of Laboratory:</label>
                        <input type="text" class="form-control" name="head_of_lab" value="{{$user->profile->head_of_lab}}" />

                    </div>
                </div>
                <div class="col-lg col-sm-12">
                    <div class="form-group">
                        <label for="lab_email">Lab email:</label>
                        <input type="email" class="form-control" name="lab_email" value="{{$user->profile->lab_email}}" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg col-sm-12">
                    <div class="form-group">
                        <label for="contact_no">Contact Number:</label>
                        <input type="text" class="form-control" name="contact_no" value="{{$user->profile->contact_no}}" />

                    </div>
                </div>
                <div class="col-lg col-sm-12">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="{{$user->profile->email}}" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg col-sm-12">
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" class="form-control" name="address" rows="1">{{$user->profile->address}}</textarea>
                    </div>
                </div>
                <div class="col-lg col-sm-12">
                    <div class="form-group">
                        <label for="city">City:</label>
                        <textarea id="city" class="form-control" name="city" rows="1">{{$user->profile->city}}</textarea>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Update</button>
                
                
        </div>
    </div>
</form>
@endsection

@section('javascript')
<script>
    const changePasswordForm = $('#changePasswordForm')
    const new_password = $('[name=new_password]')
    const showPasswordIcon = $('#showPasswordIcon')

    function handleChangePassword(id) {

        gmodal.modal()
        gmodalDialog.addClass('modal-md')
        gmodalTitle.text('Change password')
        changePasswordForm.show()
    }

    function handleShowPassword() {
        let type
        if (new_password.attr('type') === 'password') {
            type = 'text'
            showPasswordIcon.removeClass('fa-eye').addClass('fa-eye-slash')
        } else {
            type = 'password'
            showPasswordIcon.removeClass('fa-eye-slash').addClass('fa-eye')
        }
        new_password.attr('type', type)
    }
</script>
@endsection