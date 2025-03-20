<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 350px;">
        <div class="modal-content" style="width: 350px; height: 200px;">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label" style="font-size: 1rem;">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="font-size: 0.875rem;">
                <p>{{ $message }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>