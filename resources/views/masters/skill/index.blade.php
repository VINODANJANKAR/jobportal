@extends('layouts.app')
@php
    $title = 'Skills - ' . config('app.name');
    $PageTitle = 'Skills Master List';
    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Skills', 'url' => null], // Current page without URL
    ];
@endphp
@section('content')
    <div class="container p-0">
        @include('layouts.partials.breadcrumbs', [
            'breadcrumbs' => $breadcrumbs,
            'Page' => $PageTitle,
            'html' =>
                '<a href="' .
                route('skills.create') .
                '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center gap-1"><i class="ti ti-plus" style="font-size: 16px;"></i>Create</a>',
        ])

        <div class="row m-0 align-items-center mb-3">
            <div class="col-md-4 ps-0">
                @include('components.select-page', [
                    'id' => 'select', // The ID of the div containing the page select
                    'url' => route('skills.index'), // The URL for the AJAX request
                    'tableName' => 'SkillTable', // The ID of the table to be updated
                ])
            </div>

            <div class="col-md-8 d-flex justify-content-end gap-2 pe-0">
                @include('components.search-bar', [
                    'id' => 'search-form',
                    'url' => route('skills.index'),
                    'tableName' => 'SkillTable',
                ])
                {{-- @include('components.import-excel', ['action' => route('skills.import')]) --}}
                {{-- @if (count($skill) > 0)
                    @include('components.export-excel', ['action' => route('skills.export')])
                @endif --}}

            </div>
        </div>

        @component('components.user-table', [
            'checkbox' => count($skill) > 0 ? true : false,
            'headers' => ['Skill Id','Skill','Action'], // Add other headers as needed
        ])
            <tbody id="SkillTable">
                @include('masters.skill._table', ['skill' => $skill])
            </tbody>
        @endcomponent

        <div class="d-flex justify-content-end mt-2" id="paginationLinks">
            {{ $skill->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @component('components.delete-confirmation-modal', [
        'id' => 'deleteConfirmationModal',
        'title' => 'Confirm Delete',
        'message' => 'Are you sure you want to delete this skill?',
    ])
    @endcomponent

    <style>
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@push('scripts')
    @include('masters.skill.script')
@endpush
