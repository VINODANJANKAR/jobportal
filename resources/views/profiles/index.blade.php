@extends('layouts.app')
@php
    $title = 'Profile of Candidates - ' . config('app.name');
    $PageTitle = 'Profile of Candidates';
    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Profile of Candidates', 'url' => null], // Current page without URL
    ];
@endphp
@section('content')
    <div class="container p-0">
        {{-- @include('layouts.partials.breadcrumbs', [
            'breadcrumbs' => $breadcrumbs,
            'Page' => $PageTitle,
            'id' => 'addNewProfile',
        ]) --}}
        @include('layouts.partials.breadcrumbs', [
            'breadcrumbs' => $breadcrumbs,
            'Page' => $PageTitle,
            'html' =>
                '<a href="' .
                route('profiles.create') .
                '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center gap-1"><i class="ti ti-plus" style="font-size: 16px;"></i>Create</a>',
        ])
        

        <div class="row m-0 align-items-center mb-3">
            <!-- First Column: Dropdown for page selection -->
            <div class="col-md-4 ps-0">
                @include('components.select-page', [
                    'id' => 'select', // The ID of the div containing the page select
                    'url' => route('profiles.index'), // The URL for the AJAX request
                    'tableName' => 'profileTable', // The ID of the table to be updated
                ])
            </div>

            <div class="col-md-8 d-flex justify-content-end gap-2 pe-0">
                @include('components.search-bar', [
                    'id' => 'search-form',
                    'url' => route('profiles.index'),
                    'tableName' => 'profileTable',
                ])
                @include('components.import-excel', ['action' => route('profiles.import')])
                @if (count($profiles) > 0)
                    @include('components.export-excel', ['action' => route('profiles.export')])
                @endif

            </div>
        </div>

        @component('components.user-table', [
            'checkbox' => count($profiles) > 0? true : false,
            'headers' => ['Profile Id','Candidates Name', 'City','Experience', 'Qualification', 'Skills', 'CV', 'Actions'], // Add other headers as needed
        ])
            <tbody id="profileTable">
                @include('profiles._table', ['profiles' => $profiles])
            </tbody>
        @endcomponent

        <div class="d-flex justify-content-end mt-2" id="paginationLinks">
            {{ $profiles->links('pagination::bootstrap-5') }}
        </div>


    </div>

    <!-- Delete Confirmation Modal -->
    @component('components.delete-confirmation-modal', [
        'id' => 'deleteConfirmationModal',
        'title' => 'Confirm Delete',
        'message' => 'Are you sure you want to delete this Candidate Profile?',
    ])
    @endcomponent

    <style>
        .modal-dialog-right {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            max-width: 400px;
            min-width: 350px;
            height: 100%;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .modal.fade .modal-dialog {
            transform: translateX(100%);
        }

        .modal.show .modal-dialog {
            transform: translateX(0);
        }
    </style>
@endsection

@push('scripts')
    @include('profiles.partials.script')
@endpush
