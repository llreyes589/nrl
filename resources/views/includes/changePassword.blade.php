<form action="{{route('facility.profile.updatePassword')}}" method="POST" style="display: none;" id="changePasswordForm">
    @csrf
    @method("PUT")
    <div class="mb-3">
        <p for="sdtl">New Password</p>
        <div class="input-group">
            <input type="password" name="new_password" placeholder="Enter new password" class="form-control ">
            <div class="input-group-append">
                <span class="input-group-text" style="cursor: pointer;" id="sdtl" onclick="handleShowPassword()"><i class="fas fa-eye" id="showPasswordIcon"></i></span>
            </div>

        </div>

    </div>
    <button class="btn btn-success btn-sm" type="submit">Update</button>
    <button class="btn btn-danger btn-sm" type="button" onclick="handleCloseGModal()">Cancel</button>

</form>