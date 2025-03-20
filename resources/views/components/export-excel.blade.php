<form action="{{ $action }}" method="GET" id="export-form">
    @csrf
    <input type="hidden" name="selected_ids" id="selected_ids" value="">
    <input type="hidden" name="selected_page" id="selected_page" value="">
    <button type="submit" class="btn btn-primary btn-sm">Export Excel</button>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const exportForm = document.querySelector('#export-form');
        document.querySelector('#export-form').addEventListener('submit', function(e) {
            let selectedIds = [];
            console.log(document.getelementsById('page-select').value);
            document.querySelectorAll('.select-profile:checked').forEach(function(checkbox) {
                selectedIds.push(checkbox.dataset.id);
            });
            document.querySelector('#selected_page').value = document.querySelector('#page-select')
                .value
            // Set the value of the hidden field with the selected profile IDs
            document.querySelector('#selected_ids').value = selectedIds.join(',');
        });
        exportForm.addEventListener('submit', function(e) {
            $('.selectId').prop('checked', false);
        });
    });
</script>
