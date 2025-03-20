<form id="companyForm" class="p-1 mt-4" style="max-width: 450px; margin: auto;">
    <input type="hidden" id="company_id" name="id">
    <div class="p-4 mb-4"
        style="min-height: 300px; box-shadow: -6px 1px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #fff; overflow: hidden;">
        <div class="mb-3">
            <label for="name" class="form-label">Company Name</label>
            <input type="text" id="name" name="name" class="form-control form-control-sm">
            <div id="nameError" class="text-danger small"></div> <!-- Error message here -->
        </div>
    </div>
    <div class="mb-3 text-end">
        <button type="button" class="btn btn-danger btn-md" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-md" id="saveCompanyBtn" style="width: 150px">Save</button>
    </div>
</form>
