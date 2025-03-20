<div class="d-flex align-items-center text-dark" id="{{ $id }}" data-url="{{ $url }}"
    data-table-name="{{ $tableName }}">
    <label for="page-select" class="me-2">Select Pages:</label>
    <select id="page-select" class="form-select  text-dark border-light form-select-sm" style="width: 100px;">
        <option value="10" {{ request('page_size') == '10' ? 'selected' : '' }}>10</option>
        <option value="20" {{ request('page_size') == '20' ? 'selected' : '' }}>20</option>
        <option value="30" {{ request('page_size') == '30' ? 'selected' : '' }}>30</option>
        <option value="50" {{ request('page_size') == '50' ? 'selected' : '' }}>50</option>
        <option value="100" {{ request('page_size') == '100' ? 'selected' : '' }}>100</option>
    </select>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateSelectAll() {
            const selectAllCheckbox = document.getElementById("select-all");
            const checkboxes = document.querySelectorAll(".selectId");
            
            selectAllCheckbox.addEventListener("change", function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        }
        updateSelectAll();
        $('#page-select').on('change', function() {
            let selectedValue = $(this).val(); // Get the selected page size
            let url = $('#' + '{{ $id }}').data('url'); // Get the URL dynamically
            let tableName = $('#' + '{{ $id }}').data('table-name'); // Get the table name dynamically
            document.querySelector('#selected_page').value = document.querySelector('#page-select').value
            
            $.ajax({
                url: url, // Ensure this route is correct
                type: 'GET',
                data: {
                    page_size: selectedValue, // Pass the page size selected by the user
                    page: 1 // Always reset to page 1 when changing the page size
                },
                success: function(response) {
                    // Dynamically update the table and pagination HTML
                    let firstKey = Object.keys(response)[0];
                    $('#' + tableName).html(response[firstKey]); // Update the table with new data
                    $('#paginationLinks').html(response.paginationHtml); // Update pagination
                    
                    // After new content is loaded, update the "Select All" functionality
                    updateSelectAll();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    });
</script>

