@extends('layouts.app')
@php
    $title = 'Skill - ' . config('app.name');
    $PageTitle = isset($skill)
        ? ($show
            ? 'View Skill (Skill Id -' . $skill->id . ')'
            : 'Edit Skill (Skill Id - ' . $skill->id . ')')
        : 'Create Skill';

    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Skill', 'url' => route('skills.index')], // Changed "user" to "skill"
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
                        route('skills.edit', $skill->id) . 
                        '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="ti ti-pencil" style="font-size: 16px;"></i></a> </div>'
                    : null,
            ])
        )

        <form id="skillForm" enctype="multipart/form-data">
            <input type="hidden" id="skill_id" name="id" value="{{ $skill->id ?? null }}">
            <div class="p-4 mb-4"
                style="box-shadow: -6px 1px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #fff; overflow: hidden;">
                @include('masters.skill.partials.skill-form', [ // Changed the included form to "skill-form"
                    'show' => $show,
                ])
                <div class="form-group mb-3 text-end">
                    <a href="{{ route('skills.index') }}" class="btn btn-danger btn-md">Cancel</a>
                    @if (!$show)
                        <button type="submit" class="btn btn-primary btn-md"
                            style="width: 150px">{{ isset($skill) ? 'Update' : 'Create' }}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    @include('masters.skill.script') <!-- Changed to skill-related script -->
@endpush
