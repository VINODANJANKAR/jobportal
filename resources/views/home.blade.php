@extends('layouts.app')
@php
    $title = 'Dashboard - ' . config('app.name');
    // $description = 'Browse the latest job posts on ' . config('app.name');
    // $keywords = 'jobs, job posts, careers';
    // $image = asset('images/job-posts.jpg');
@endphp
@section('content')
    <style>
        .one:hover {
            background-color: #7fb9f742;
            box-shadow: 4px 8px 12px 4px rgb(36 38 39 / 20%);
            border: 1px solid #083564;
        }

        .two:hover {
            background-color: #7ff7a942;
            box-shadow: 4px 8px 12px 4px rgb(36 38 39 / 20%);
            border: 1px solid #086414;
        }

        .three:hover {
            background-color: #f7cb7f42;
            box-shadow: 4px 8px 12px 4px rgb(36 38 39 / 20%);
            border: 1px solid #643608;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="row align-items-center mt-3 mb-5">
                <div class="col-12 col-md-10">
                    <h4 class="fw-bold">Dashboard</h4>
                </div>
                <div class="col-12 col-md-2">
                    <!-- Modify the form to submit the time period selection -->
                    <form method="GET" action="{{ url('/') }}">
                        <select name="time_period" class="form-select  text-dark border-secondary" aria-label="Select time period"
                            onchange="this.form.submit()">
                            <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 days</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <!-- Job Posts Card -->
        <div class="col-lg-4 col-sm-6">
            <div class="card one overflow-hidden">
                <div class="card-body p-4" style="min-height: 190px;">
                    <h5 class="card-title mb-10 fw-semibold">Job Posts</h5>
                    <div class="row align-items-center">
                        <div class="col-7">
                            <h4 class="fw-semibold mb-3">{{ $totalJobPosts }}</h4>
                            <div class="d-flex align-items-center mb-2">
                                <span
                                    class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-left text-success"></i>
                                </span>
                                <p class="text-dark me-2 fs-3 mb-0">+{{ $newJobPosts }}</p>
                                <p class="fs-3 mb-0">last {{ $days }} days</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                    <span class="fs-2">{{ $activeJobPosts }} Active</span>
                                </div>
                                <div>
                                    <span class="round-8 bg-danger rounded-circle me-2 d-inline-block"></span>
                                    <span class="fs-2">{{ $expiredJobPosts }} Expired</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="text-white bg-primary rounded-circle p-7 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-briefcase fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profiles Card -->
        <div class="col-lg-4 col-sm-6">
            <div class="card three">
                <div class="card-body" style="min-height: 190px;">
                    <h5 class="card-title mb-10 fw-semibold">Total Profiles Of Candidates</h5>
                    <div class="row align-items-start">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3">{{ $totalProfiles }}</h4>
                            <div class="d-flex align-items-center mb-2">
                                <span
                                    class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-left text-success"></i>
                                </span>
                                <p class="text-dark me-2 fs-3 mb-0">+{{ $newProfiles }}</p>
                                <p class="fs-3 mb-0">last {{ $days }} days</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="text-white bg-warning rounded-circle p-7 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#time_period').on('change', function() {
                var timePeriod = $(this).val(); // Get the selected time period
                $.ajax({
                    url: '{{ url('/') }}', // Make a GET request to the current route
                    method: 'GET',
                    data: {
                        time_period: timePeriod
                    }, // Send the time period as data
                    success: function(response) {
                        // Update the content dynamically with the returned data
                        $('#total_job_posts').text(response.totalJobPosts);
                        $('#new_job_posts').text('+' + response.newJobPosts);
                        $('#last_time_period').text('last ' + response.days + ' days');
                        $('#active_job_posts').text(response.activeJobPosts + ' Active');
                        $('#expired_job_posts').text(response.expiredJobPosts + ' Expired');

                        // For companies
                        $('#total_companies').text(response.totalCompanies);
                        $('#new_companies').text('+' + response.newCompany);
                        $('#companies_time_period').text('last ' + response.days + ' days');

                        // For profiles
                        $('#total_profiles').text(response.totalProfiles);
                        $('#new_profiles').text('+' + response.newProfileOfCadidates);
                        $('#profiles_time_period').text('last ' + response.days + ' days');
                    }
                });
            });
        });
    </script>
@endsection
