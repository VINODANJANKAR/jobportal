@extends('layouts.app')
@php
    $title = 'Company Location - ' . config('app.name');
    $PageTitle = $company->name . ' - Locations List';
    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'Company', 'url' => route('company.index')],
        ['title' => 'Locations', 'url' => null], // Current page without URL
    ];
@endphp
@section('content')
    <div class="container my-5">
        @include('layouts.partials.breadcrumbs', [
            'breadcrumbs' => $breadcrumbs,
            'Page' => $PageTitle,
        ])

        <div class="row">
            <div class="col-md-4">
                @include('components.select-page', [
                    'id' => 'select', // The ID of the div containing the page select
                    'url' => route('profiles.show', $company->id), // The URL for the AJAX request
                    'tableName' => 'locationTable', // The ID of the table to be updated
                ])
            </div>

            <div class="col-md-8 d-flex justify-content-end gap-2">
                @include('components.search-bar', [
                    'id' => 'search-form',
                    'url' => route('profiles.show', $company->id),
                    'tableName' => 'locationTable',
                ])
                {{-- @include('components.import-excel', ['action' => route('company.import')]) --}}
            </div>
        </div>

        @component('components.user-table', [
            'checkbox' => false,

            'headers' => ['Location Name', 'City', 'Address', 'Actions'], // Add other headers as needed
        ])
            <tbody id="locationTable">
                @include('masters.company.partials.location-table')
            </tbody>
        @endcomponent

        <div class="d-flex justify-content-end" id="paginationLinks">
            {{ $locations->links('pagination::bootstrap-5') }}
        </div>

    </div>

    @component('components.modal', ['id' => 'addLocationModal', 'title' => 'Edit Company Location'])
        @include('masters.company.partials.location-form')
    @endcomponent

    @component('components.delete-confirmation-modal', [
        'id' => 'deleteLocationModal',
        'title' => 'Confirm Delete',
        'message' => 'Are you sure you want to delete this location?',
    ])
    @endcomponent
@endsection

@push('scripts')
    @include('masters.company.location.script')
@endpush
