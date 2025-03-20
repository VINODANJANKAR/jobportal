<script>
    $(document).ready(function() {
        var locationIdToDelete = null;
        var locationIdToEdit = null;

        // Handle edit button click
        $(document).on('click', '.editLocation', function() {
            locationIdToEdit = $(this).data('id');
            $('#locationId').val(locationIdToEdit);
            $('#location').val($(this).data('location'));
            $('#location_map').val($(this).data('location_map'));
            $('#city').val($(this).data('city'));
            $('#address').val($(this).data('address'));
            $('#addLocationModal').modal('show');
            $('#exampleModalLabel').text('Edit Company Location');
        });

        // Handle save changes for editing or adding a location
        $('#addLocationForm').submit(function(e) {
            e.preventDefault();

            var locationData = $(this).serialize(); // Get form data
            var method = locationIdToEdit ? 'PUT' : 'POST';
            var url = locationIdToEdit ? '/master/company-location/' + locationIdToEdit :
                '/master/company-location';

            $.ajax({
                url: url,
                type: method,
                data: locationData + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                success: function(response) {
                    $('#addLocationModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Location saved/updated successfully.'
                    });
                    window.location.reload(); // Reload to see the changes
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            });
        });

        // Handle delete button click
        $(document).on('click', '.deleteLocation', function() {
            locationIdToDelete = $(this).data('id'); // Store the location ID for deletion
            $('#deleteLocationModal').modal('show'); // Show confirmation modal
        });

        // Handle confirm delete action
        $('#confirmDeleteLocation').click(function() {
            if (locationIdToDelete) {
                $.ajax({
                    url: '/master/company-location/' +
                        locationIdToDelete, // URL to delete location
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    },
                    success: function(response) {
                        $('#deleteLocationModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The location has been deleted.'
                        });
                        window.location
                            .reload(); // Reload the page to update the location list
                    },
                    error: function(xhr, status, error) {
                        $('#deleteLocationModal').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            }
        });
    });
</script>