@extends('layouts.app')
@php
    $title = 'Experiences - ' . config('app.name');
    $PageTitle = 'Experiences Master List';
    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Experiences', 'url' => null], // Current page without URL
    ];
@endphp
@section('content')
    <div class="container p-0">
        @include('layouts.partials.breadcrumbs', [
            'breadcrumbs' => $breadcrumbs,
            'Page' => $PageTitle,
            'html' =>
                '<a href="' .
                route('experiences.create') .
                '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center gap-1"><i class="ti ti-plus" style="font-size: 16px;"></i>Create</a>',
        ])

        <div class="row m-0 align-items-center mb-3">
            <div class="col-md-4 ps-0">
                @include('components.select-page', [
                    'id' => 'select', // The ID of the div containing the page select
                    'url' => route('experiences.index'), // The URL for the AJAX request
                    'tableName' => 'ExperiencesTable', // The ID of the table to be updated
                ])
            </div>

            <div class="col-md-8 d-flex justify-content-end gap-2 pe-0">
                @include('components.search-bar', [
                    'id' => 'search-form',
                    'url' => route('experiences.index'),
                    'tableName' => 'ExperiencesTable',
                ])
            </div>
        </div>

        @component('components.user-table', [
            'checkbox' => count($experiences) > 0 ? true : false,
            'headers' => ['Experiences Id','Experience','Action'], // Add other headers as needed
        ])
            <tbody id="ExperiencesTable">
                @include('masters.experience._table', ['experiences' => $experiences])
            </tbody>
        @endcomponent

        <div class="d-flex justify-content-end mt-2" id="paginationLinks">
            {{ $experiences->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @component('components.delete-confirmation-modal', [
        'id' => 'deleteConfirmationModal',
        'title' => 'Confirm Delete',
        'message' => 'Are you sure you want to delete this experience?',
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
    @include('masters.experience.script')
@endpush
