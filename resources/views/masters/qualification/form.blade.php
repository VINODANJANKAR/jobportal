@extends('layouts.app')
@php
    $title = 'Qualification - ' . config('app.name');
    $PageTitle = isset($qualification)
        ? ($show
            ? 'View Qualification (Qualification Id -' . $qualification->id . ')'
            : 'Edit Qualification (Qualification Id - ' . $qualification->id . ')')
        : 'Create Qualification';

    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Qualification', 'url' => route('qualifications.index')], // Changed "user" to "Qualification"
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
                        route('qualifications.edit', $qualification->id) . 
                        '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="ti ti-pencil" style="font-size: 16px;"></i></a> </div>'
                    : null,
            ])
        )

        <form id="qualificationForm" enctype="multipart/form-data">
            <input type="hidden" id="qualification_id" name="id" value="{{ $qualification->id ?? null }}">
            <div class="p-4 mb-4"
                style="box-shadow: -6px 1px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #fff; overflow: hidden;">
                @include('masters.qualification.partials.qualification-form', [ // Changed the included form to "qualification-form"
                    'show' => $show,
                ])
                <div class="form-group mb-3 text-end">
                    <a href="{{ route('qualifications.index') }}" class="btn btn-danger btn-md">Cancel</a>
                    @if (!$show)
                        <button type="submit" class="btn btn-primary btn-md"
                            style="width: 150px">{{ isset($qualification) ? 'Update' : 'Create' }}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    @include('masters.qualification.script') <!-- Changed to skill-related script -->
@endpush
