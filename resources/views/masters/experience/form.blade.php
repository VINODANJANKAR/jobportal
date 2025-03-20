@extends('layouts.app')
@php
    $title = 'Experience - ' . config('app.name');
    $PageTitle = isset($experience)
        ? ($show
            ? 'View Experience (Experience Id -' . $experience->id . ')'
            : 'Edit Experience (Experience Id - ' . $experience->id . ')')
        : 'Create Experience';

    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Experience', 'url' => route('experiences.index')], // Changed "skill" to "experience"
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
                        route('experiences.edit', $experience->id) . 
                        '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="ti ti-pencil" style="font-size: 16px;"></i></a> </div>'
                    : null,
            ])
        )

        <form id="ExperienceForm" enctype="multipart/form-data">
            <input type="hidden" id="experience_id" name="id" value="{{ $experience->id ?? null }}">
            <div class="p-4 mb-4"
                style="box-shadow: -6px 1px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #fff; overflow: hidden;">
                @include('masters.experience.partials.experience-form', [ // Changed to experience-form
                    'show' => $show,
                ])
                <div class="form-group mb-3 text-end">
                    <a href="{{ route('experiences.index') }}" class="btn btn-danger btn-md">Cancel</a>
                    @if (!$show)
                        <button type="submit" class="btn btn-primary btn-md"
                            style="width: 150px">{{ isset($experience) ? 'Update' : 'Create' }}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    @include('masters.experience.script') <!-- Changed to experience-related script -->
@endpush
