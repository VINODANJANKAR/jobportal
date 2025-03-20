<form id="{{ $id }}" data-url="{{ $url }}" data-table-name="{{ $tableName }}">
    <div class="input-group text-dark">
        <input type="text" id="search" class="form-control form-control-sm text-dark border-light" placeholder="Search..."
            value="{{ request()->get('search') }}">
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            let searchQuery = $(this).val();
            let url = $('#' + '{{ $id }}').data(
                'url');
            let tableName = $('#' + '{{ $id }}').data(
                'table-name');
            let pageSize = $('#page-select').val();

            $.ajax({
                url: url,
                type: "GET",
                data: {
                    search: searchQuery,
                    page_size: pageSize,
                },
                success: function(response) {
                    let firstKey = Object.keys(response)[
                        0];
                    console.log(firstKey);

                    $('#' + tableName).html(response[
                        firstKey]);

                    $('#paginationLinks').html(response
                    .paginationHtml); // Update pagination
                    console.log(response
                    .paginationHtml, 'response paginationHtml');
                    

                },
                error: function(xhr, status, error) {
                    console.log("Error during AJAX request:", error);
                }
            });
        });
    });
</script>
