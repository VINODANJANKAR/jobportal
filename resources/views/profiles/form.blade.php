@extends('layouts.app')
@php
    $title = 'Profile of Candidates - ' . config('app.name');
    $PageTitle = isset($profile)
        ? ($show
            ? 'View Profile of Candidates (Profile Id - ' . $profile->id . ')'
            : 'Edit Profile of Candidates (Profile Id - ' . $profile->id . ')')
        : 'Create Profile of Candidates';

    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Profile of Candidates', 'url' => route('profiles.index')],
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
                        route('profiles.edit', $profile->id) .
                        '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="ti ti-pencil" style="font-size: 16px;"></i></a> </div>'
                    : null,
            ]))

        <form id="profileForm" enctype="multipart/form-data">
            <input type="hidden" id="profile_id" name="id" value="{{ $profile->id ?? null }}">
            <div class="p-4 mb-4"
                style=" box-shadow: -6px 1px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #fff; overflow: hidden;">
                @include('profiles.partials.form', [
                    'show' => $show,
                ])
                <div class="form-group mb-3 text-end">
                    <a href="{{ route('profiles.index') }}" class="btn btn-danger btn-md">Cancel</a>
                    @if (!$show)
                        <button type="submit" class="btn btn-primary btn-md"
                            style="width: 150px">{{ isset($profile) ? 'Update' : 'Create' }}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    @include('profiles.partials.script')
@endpush
