@extends('layouts.app')

@php
    $title = 'Company - ' . config('app.name');
    $PageTitle = 'Company Master List';
    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Company', 'url' => null], // Current page without URL
    ];
@endphp

@section('content')
    <div class="container my-5">
        @include('layouts.partials.breadcrumbs', [
            'breadcrumbs' => $breadcrumbs,
            'Page' => $PageTitle,
            'id' => 'addNewCompany',
        ])

        <div class="row">
            <!-- First Column: Dropdown for page selection -->
            <div class="col-md-4">
                @include('components.select-page', [
                    'id' => 'select', // The ID of the div containing the page select
                    'url' => route('company.index'), // The URL for the AJAX request
                    'tableName' => 'companyTable', // The ID of the table to be updated
                ])
            </div>

            <div class="col-md-8 d-flex justify-content-end gap-2">
                @include('components.search-bar', [
                    'id' => 'search-form',
                    'url' => route('company.index'),
                    'tableName' => 'companyTable',
                ])
                @include('components.import-excel', ['action' => route('company.import')])
                @if (count($companies) > 0)
                    @include('components.export-excel', ['action' => route('company.export')])
                @endif
            </div>
        </div>

        @component('components.user-table', [
            'checkbox' => count($companies) > 0? true : false,
            'headers' => ['Campany Name', 'Locations', 'Action'], // Add other headers as needed
        ])
            <tbody id="companyTable">
                @include('masters.company.partials.company-table', ['companies' => $companies])
            </tbody>
        @endcomponent

        <div class="d-flex justify-content-end" id="paginationLinks">
            {{ $companies->links('pagination::bootstrap-5') }}
        </div>
    </div>

    @component('components.modal', ['id' => 'sideModal', 'title' => 'Add Company'])
        @include('masters.company.partials.company-form')
    @endcomponent

    @component('components.modal', ['id' => 'addLocationModal', 'title' => 'Add Company Location'])
        @include('masters.company.partials.location-form')
    @endcomponent

    @component('components.delete-confirmation-modal', [
        'id' => 'deleteConfirmationModal',
        'title' => 'Confirm Delete',
        'message' => 'Are you sure you want to delete this Company?',
    ])
    @endcomponent
@endsection

@push('scripts')
    @include('masters.company.scripts') <!-- Include your existing JS scripts -->
@endpush
