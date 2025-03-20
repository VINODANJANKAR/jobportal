@extends('layouts.app')
@php
    $title = 'Job Postings - ' . config('app.name');
    $PageTitle = isset($jobPost)
        ? ($show
            ? 'View Job Post (Post Id - ' . $jobPost->post_id . ')'
            : 'Edit Job Post (Post Id - ' . $jobPost->post_id . ')')
        : 'Create Job Post';

    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Job Postings', 'url' => route('job-posts.index')],
        ['title' => $PageTitle, 'url' => null], // Current page without URL
    ];
@endphp
@section('content')
    <div class="container">
        @include(
            'layouts.partials.breadcrumbs',
            array_filter([
                'breadcrumbs' => $breadcrumbs,
                'Page' => $PageTitle,
                'html' => $show
                    ? '<div class="d-flex gap-2"><a href="' .
                        route('job-posts.edit', $jobPost->id) .
                        '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="ti ti-pencil" style="font-size: 16px;"></i>
                                        </a><form action="' .
                        route('job-posts.repost', $jobPost->id) .
                        '" method="POST" style="display:inline-block;">
                                        ' .
                        csrf_field() .
                        '<button type="submit" class="btn btn-secondary btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: none;">
                                        <i class="ti ti-refresh" style="font-size: 16px;"></i></button></form></div>'
                    : null,
            ]))




        <form id="jobPostForm" enctype="multipart/form-data">
            <input type="hidden" id="job_id" name="id" value="{{ $jobPost->id ?? null }}">
            <div class="p-4 mb-4"
                style=" box-shadow: -6px 1px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #fff; overflow: hidden;">
                <input type="hidden" name="post_id" id="post_id"
                    class="form-control @error('post_id') is-invalid @enderror"
                    value="{{ old('post_id', $jobPost->post_id ?? '') }}">
                @include('jobposting.partials.form-fields', [
                    'section' => 'job_posting_details',
                    'show' => $show,
                ])
                <div id="contact_information">
                    @include('jobposting.partials.form-fields', [
                        'section' => 'contact_information',
                        'show' => $show,
                    ])
                </div>
                <div id="company_requirements">
                    @include('jobposting.partials.form-fields', [
                        'section' => 'company_requirements',
                        'show' => $show,
                    ])
                </div>
                <div class="form-group mb-3 text-end">
                    <a href="{{ route('job-posts.index') }}" class="btn btn-danger btn-md">Cancel</a>
                    @if (!$show)
                        <button type="submit" class="btn btn-primary btn-md"
                            style="width: 150px">{{ isset($jobPost) ? 'Update' : 'Create' }}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('jobposting.script')
@endpush
