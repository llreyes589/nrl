<form id="deleteFacilityModalBody" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <p>Are you sure want to delete this Facility? This will permanently delete this Facility. Proceed?</p>
    <button class="btn btn-danger btn-sm" type="submit">Yes</button>
    <button class="btn btn-secondary btn-sm" type="button" onclick="handleCloseGModal()">No</button>
</form>