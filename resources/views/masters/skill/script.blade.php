<script>
    $(document).ready(function() {
        $('#skillForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let actionUrl = '/master/skills';
            let method = 'POST';

            if ($('#skill_id').val()) {
                actionUrl = '/master/skills/' + $('#skill_id').val();
                method = 'POST';
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
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                    window.location.href = "{{ route('skills.index') }}";
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value[
                                0] + '</div>');
                        });
                    } else {
                        console.log(xhr);
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

        $('input, select, textarea').on('focus', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });

        // Fetch the latest skill list
        function fetchSkill() {
            $.ajax({
                url: '{{ route('skills.index') }}',
                method: 'GET',
                success: function(response) {
                    $('#SkillTable').html(response.skill);
                    $('#paginationLinks').html(response.paginationHtml);
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        }

        // Handle delete action with modal confirmation
        let deleteSkillId = null;

        $('body').on('click', '.deleteSkill', function() {
            deleteSkillId = $(this).data('id');
            $('#deleteConfirmationModal').modal('show');
        });

        $('#confirmDelete').click(function() {
            $.ajax({
                url: '/master/skills/' + deleteSkillId,
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
                    fetchSkill(); // Reload the table with updated data
                },
                error: function(xhr, status, error) {
                    $('#deleteConfirmationModal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while deleting the skill.'
                    });
                }
            });
        });
    });
</script>
