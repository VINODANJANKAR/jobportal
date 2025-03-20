<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectedSkills = document.getElementById('selectedSkills');
        const dropdownButton = document.getElementById('dropdownMenuButton');

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('dropdown-item')) {
                e.preventDefault();
                const skill = e.target.getAttribute('data-value');
                e.target.classList.toggle('active');

                if (!isSkillSelected(skill)) {
                    addSkillBadge(skill);
                } else {
                    removeSkillBadge(skill);
                }
                updateDropdownButtonText();
            }
        });

        function isSkillSelected(skill) {
            return Array.from(selectedSkills.getElementsByClassName('badge')).some(badge => badge.textContent
                .includes(skill));
        }

        function addSkillBadge(skill) {
            const badge = document.createElement('span');
            badge.classList.add('badge', 'bg-primary', 'me-1');
            badge.textContent = skill;

            const removeButton = document.createElement('span');
            removeButton.classList.add('ms-2', 'text-white', 'cursor-pointer');
            removeButton.textContent = '×';
            removeButton.addEventListener('click', () => {
                badge.remove();
                toggleDropdownItem(skill, false);
                updateDropdownButtonText();
            });

            badge.appendChild(removeButton);
            selectedSkills.appendChild(badge);
        }

        function removeSkillBadge(skill) {
            Array.from(selectedSkills.getElementsByClassName('badge')).forEach(badge => {
                if (badge.textContent.includes(skill)) {
                    badge.remove();
                }
            });
            toggleDropdownItem(skill, false);
        }

        function toggleDropdownItem(skill, isActive) {
            document.querySelectorAll('.dropdown-item').forEach(item => {
                if (item.getAttribute('data-value') === skill) {
                    item.classList.toggle('active', isActive);
                }
            });
        }

        function updateDropdownButtonText() {
            const selectedSkillsArray = Array.from(selectedSkills.getElementsByClassName('badge')).map(badge =>
                badge.textContent.replace('×', '').trim());
            dropdownButton.textContent = selectedSkillsArray.join(', ') || 'Select Skills';
        }

        $(document).ready(function() {
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let actionUrl = '/profiles';
                let method = 'POST';
                var jobId = $('#profile_id').val();

                if (jobId != null && jobId != '') {
                    actionUrl = '/profiles/' + $('#profile_id').val();
                    method = 'POST';
                    formData.append('_method', 'PUT');
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    }
                });

                $.ajax({
                    url: actionUrl,
                    method: method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message
                        });
                        window.location.href =
                            "{{ route('profiles.index') }}";
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                var input = $('[name="' + key +
                                    '"]');
                                input.addClass('is-invalid');
                                input.after(
                                    '<div class="invalid-feedback">' +
                                    value[
                                        0] + '</div>');
                            });
                        } else {
                            console.log(xhr);
                            alert(
                                'An error occurred. Please try again.');
                        }
                    }
                });
            });

            $('input, select, textarea').on('focus', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });


            function fetchProfiles() {
                $.ajax({
                    url: '{{ route('profiles.index') }}',
                    method: 'GET',
                    success: function(response) {
                        $('#profileTable').html(response.profiles);
                        $('#paginationLinks').html(response.pagination);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: ' + error);
                    }
                });
            }

            let deleteProfileId = null;

            $('body').on('click', '.deleteProfile', function() {
                deleteProfileId = $(this).data('id');
                $('#deleteConfirmationModal').modal('show');
            });

            $('#confirmDelete').click(function() {
                $.ajax({
                    url: '/profiles/' + deleteProfileId,
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
                        fetchProfiles();
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
        });
    });
</script>
