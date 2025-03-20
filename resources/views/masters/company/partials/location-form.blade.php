<form id="addLocationForm" class="p-1 mt-4" style="max-width: 450px; margin: auto;">
    <input type="hidden" name="company_id" id="companyId">
    <input type="hidden" name="location_id" id="locationId">

    <div class="p-4 mb-4"
        style="box-shadow: -6px 1px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #fff; overflow: hidden;">
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control form-control-sm" id="location" name="location" required>
        </div>
        <div class="mb-3">
            <label for="location_map" class="form-label">Location Map</label>
            <input type="text" class="form-control form-control-sm" id="location_map" name="location_map" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control form-control-sm" id="city" name="city" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control form-control-sm" id="address" name="address" rows="3" required></textarea>
        </div>
    </div>
    <div class="mb-3 text-end">
        <button type="button" class="btn btn-danger btn-md" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-md" id="saveLocationBtn" style="width: 150px">Save</button>
    </div>
</form>
