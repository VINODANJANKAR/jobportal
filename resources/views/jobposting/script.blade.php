<script>
    function toggleImageUpload() {
        var postType = document.getElementById('post_type').value;
        var imageUpload = document.getElementById('image_upload');
        if (postType === 'Image') {
            imageUpload.style.display = 'block';
            document.getElementById('contact_information').style.display = 'none';
            // document.getElementById('company_requirements').style.display = 'none';

        } else {
            imageUpload.style.display = 'none';
            document.getElementById('contact_information').style.display = 'block';
            // document.getElementById('company_requirements').style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleImageUpload();
        ClassicEditor
            .create(document.querySelector('#job_details'), {
                toolbar: [
                    'heading', 'bold', 'underline', 'bulletedList', 'numberedList', '|', 'undo', 'redo'
                ],
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        }
                    ]
                }
            })
            .then(editor => {
            //     const jobDetails = document.querySelector('#job_details');
            //     if (!jobDetails.value.trim()) {
            //         editor.setData(`
            //     <p><strong>Job Description:</strong> [Job Description] </p>
            //     <p><strong>Experience:</strong> [Experience] </p>
            //     <p><strong>Salary:</strong> [Salary] </p>
            //     <p><strong>facilities:</strong> [facilities] </p>
            //     <p><strong>Skills Required:</strong> [Skills Required] </p>
            // `);
            //     }
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#contact_details'), {
                toolbar: [
                    'heading', 'bold', 'underline', 'bulletedList', 'numberedList', '|', 'undo', 'redo'
                ],
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        }
                    ]
                }
            })
            .then(editor => {
            //     const contactDetails = document.querySelector('#contact_details');
            //     if (!contactDetails.value.trim()) {
            //         editor.setData(`
            //     <p>📧 Email: [Your Email]</p>
            //     <p>📞 Phone: [Your Contact Number]</p>
            //     <p>🔗 LinkedIn: [Your LinkedIn Profile]</p>
            //     <p>🌐 Portfolio/GitHub: [Your Website or GitHub Profile]</p>
            // `);
            //     }
            })
            .catch(error => {
                console.error(error);
            });


    });
    $(document).ready(function() {
        $('#jobPostForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let actionUrl = '/job-posts';
            let method = 'POST';
            var jobId = $('#job_id').val();

            if (jobId != null && jobId != '') {
                actionUrl = '/job-posts/' + $('#job_id').val();
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
                    window.location.href = "{{ route('job-posts.index') }}";
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

    });
</script>
