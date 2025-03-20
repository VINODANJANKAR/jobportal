<script>
$(document).ready(function () {
    $('#ExperienceForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let experienceId = $('#experience_id').val();
        let actionUrl = '/master/experiences' + (experienceId ? '/' + experienceId : '');
        let method = experienceId ? 'POST' : 'POST';
        
        if (experienceId) {
            formData.append('_method', 'PUT');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: actionUrl,
            method: method,
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message
                }).then(() => {
                    window.location.href = "{{ route('experiences.index') }}";
                });
            },
            error: function (xhr) {
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.'
                    });
                }
            }
        });
    });

    $('input, select, textarea').on('focus', function () {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    function fetchExperiences() {
        $.ajax({
            url: '{{ route('experiences.index') }}',
            method: 'GET',
            success: function (response) {
                $('#ExperiencesTable').html(response.experiences);
                $('#paginationLinks').html(response.paginationHtml);
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            }
        });
    }

    let deleteExperienceId = null;

    $('body').on('click', '.deleteExperience', function () {
        deleteExperienceId = $(this).data('id');
        $('#deleteConfirmationModal').modal('show');
    });

    $('#confirmDelete').click(function () {
        $.ajax({
            url: '/master/experiences/' + deleteExperienceId,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#deleteConfirmationModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: response.message
                });
                fetchExperiences();
            },
            error: function (xhr) {
                $('#deleteConfirmationModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the experience.'
                });
            }
        });
    });
});

</script>
