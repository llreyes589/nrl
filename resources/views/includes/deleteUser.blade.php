<form id="deleteUserModalBody" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <p>Are you sure want to delete this User? This will permanently delete this user. Proceed?</p>
    <button class="btn btn-danger btn-sm" type="submit">Yes</button>
    <button class="btn btn-secondary btn-sm" type="button" onclick="handleCloseGModal()">No</button>
</form>