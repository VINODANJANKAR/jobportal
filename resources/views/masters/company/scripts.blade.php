<script>
    $(document).ready(function() {
        // Open modal for creating new Company
        $('#addNewCompany').click(function() {
            $('#sideModalLabel').text('Add Company');
            $('#companyForm')[0].reset();
            $('#company_id').val('');
            $('#sideModal').modal('show');
        });

        // Handle form submission for Create/Update via AJAX
        $('#companyForm').on('submit', function(e) {
            e.preventDefault();

            let isValid = true;

            // Clear previous error messages
            $('.text-danger').text('');

            // Check if Company Name is empty
            if ($('#name').val().trim() === '') {
                isValid = false;
                $('#nameError').text('Company Name is required');
            }

            if (isValid) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let formData = $(this).serialize();
                let actionUrl = '/master/company';
                let method = 'POST';

                if ($('#company_id').val()) {
                    actionUrl = '/master/company/' + $('#company_id').val();
                    method = 'PUT';
                }

                $.ajax({
                    url: actionUrl,
                    method: method,
                    data: formData,
                    success: function(response) {
                        $('#sideModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message
                        });
                        fetchCompany(); // Reload the table with new data
                    },
                    error: function(xhr) {
                        // Handle validation errors from the server if any
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                $('#' + field + 'Error').text(messages.join(', '));
                            });
                        }
                    }
                });
            }
        });

        // Fetch the latest company list
        function fetchCompany() {
            $.ajax({
                url: '{{ route('company.index') }}',
                method: 'GET',
                success: function(response) {
                    $('#companyTable').html(response.companies);
                    $('#paginationLinks').html(response.pagination);
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        }

        // Open edit modal for editing existing company
        $(document).on('click', '.editCompany', function() {
            var companyId = $(this).data('id');
            $.get('/master/company/' + companyId + '/edit', function(data) {
                $('#sideModalLabel').text('Edit Company');
                $('#companyForm')[0].reset();
                $('#company_id').val(data.company.id);
                $('#name').val(data.company.name);
                $('#sideModal').modal('show');
            });
        });


        // Handle delete action with modal confirmation
        let deletecompanyId = null;

        $('body').on('click', '.deleteCompany', function() {
            deletecompanyId = $(this).data('id');
            $('#deleteConfirmationModal').modal('show');
        });

        $('#confirmDelete').click(function() {
            $.ajax({
                url: '/master/company/' + deletecompanyId,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#deleteConfirmationModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message
                    });
                    fetchCompany(); // Reload the table with updated data
                },
                error: function(xhr, status, error) {
                    $('#deleteConfirmationModal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while deleting the User.'
                    });
                }
            });
        });


        // Open the modal for adding a location
        $(document).on('click', '.addLocationBtn', function() {
            var companyId = $(this).data('id');
            $('#companyId').val(companyId); // Set the company_id to the hidden field
            $('#addLocationModal').modal('show');
            $('#addLocationForm')[0].reset();
        });

        $('#addLocationForm').on('submit', function(event) {
            event.preventDefault(); 
            var formData = $(this).serialize();
            var companyId = $('#companyId').val();
            if (!companyId) {
                alert("Company ID is missing");
                return;
            }
            $.ajax({
                url: '/master/company-location', // Correct route for storing a location
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    company_id: companyId, // Add company_id here
                    location: $('#location').val(), // Add location field value
                    location_map: $('#location_map').val(), // Add location field value
                    city: $('#city').val(), // Add city field value
                    address: $('#address').val() // Add address field value
                },
                success: function(response) {
                    $('#addLocationModal').modal('hide'); // Close the modal
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                    fetchCompany(); // Reload the table with new data
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('There was an error adding the location.');
                }
            });

        });

    });
</script>
