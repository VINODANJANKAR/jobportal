@extends('layouts.app')
@php
    $title = 'Job Post - ' . config('app.name');
    $PageTitle = 'Job Postings List';
    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Job Postings', 'url' => null], // Current page without URL
    ];
@endphp
@section('content')
    <div class="container p-0">
        @include('layouts.partials.breadcrumbs', [
            'breadcrumbs' => $breadcrumbs,
            'Page' => $PageTitle,
            'html' =>
                '<a href="' .
                route('job-posts.create') .
                '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center gap-1"><i class="ti ti-plus" style="font-size: 16px;"></i>Create</a>',
        ])
        <div class="row m-0 align-items-center mb-3">
            <!-- First Column: Dropdown for page selection -->
            <div class="col-md-4 ps-0">
                @include('components.select-page', [
                    'id' => 'select', // The ID of the div containing the page select
                    'url' => route('job-posts.index'), // The URL for the AJAX request
                    'tableName' => 'jobPostTable', // The ID of the table to be updated
                ])
            </div>

            <div class="col-md-8 d-flex justify-content-end gap-2 pe-0">
                @include('components.search-bar', [
                    'id' => 'search-form',
                    'url' => route('job-posts.index'),
                    'tableName' => 'jobPostTable',
                ])
            </div>
        </div>

        @component('components.user-table', [
            'checkbox' =>  false,
            'headers' => [
                'Post Id',
                'Date',
                'Post By',
                'Valid Up',
                'Title',
                'Company',
                'Image',
                'Status',
                'Approval',
                'Action',
            ], // Add other headers as needed
        ])
            <tbody id="jobPostTable">
                @include('jobposting.partials._table', ['jobPosts' => $jobPosts])
            </tbody>
        @endcomponent

        <div class="d-flex justify-content-end mt-2" id="paginationLinks">
            {{ $jobPosts->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @component('components.delete-confirmation-modal', [
        'id' => 'deleteConfirmationModal',
        'title' => 'Confirm Delete',
        'message' => 'Are you sure you want to delete this Job Post?',
    ])
    @endcomponent
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).ready(function() {
                $('body').on('click', '.deleteJobPost', function() {
                    deleteJobPostId = $(this).data('id');
                    $('#deleteConfirmationModal').modal('show');
                });

                $('#confirmDelete').click(function() {
                    $.ajax({
                        url: '/job-posts/' + deleteJobPostId,
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
                            $.ajax({
                                url: '{{ route('job-posts.index') }}',
                                method: 'GET',
                                success: function(response) {
                                    $('#jobPostTable').html(response
                                        .jobPosts);
                                    $('#paginationLinks').html(response
                                        .paginationHtml);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error: ' + error);
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            $('#deleteConfirmationModal').modal('hide');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while deleting the Job Post.'
                            });
                        }
                    });
                });

                window.updateStatus = function(jobPostId, isChecked) {
                    const status = isChecked ? 'valid' : 'expired';
                    $.ajax({
                        url: `/job-posts/${jobPostId}/update-status`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            status: status
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Status updated successfully.'
                                });
                                // Fetch the updated page or perform the appropriate action
                                if (status === 'valid') {
                                    window.location.href =
                                        `/job-posts/${jobPostId}/edit`; // Dynamically using jobPostId
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to update status.'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while updating the status.'
                            });
                        }
                    });
                }
                $(document).ready(function() {
                    // Handle the click event of the approve button
                    $('.approve-btn').click(function() {
                        var jobId = $(this).data('job-id'); // Get the job post ID
                        var button = $(this); // The clicked button

                        // Send the AJAX request to approve the job post
                        $.ajax({
                            url: '/job-posts/' + jobId +
                                '/approve', // Define your route for approving the job post
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // Include the CSRF token for security
                            },
                            success: function(response) {
                                // If the approval was successful, update the UI
                                button.replaceWith(
                                    '<span class="btn btn-success btn-sm">Approved</span>'
                                ); // Replace the button with the approved text
                            },
                            error: function(xhr, status, error) {
                                // Handle any errors that occur
                                alert(
                                    'An error occurred while approving the job post.'
                                );
                            }
                        });
                    });
                });

            });
        });
    </script>
@endpush
