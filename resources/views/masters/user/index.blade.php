@extends('layouts.app')
@php
    $title = 'User - ' . config('app.name');
    $PageTitle = 'User Master List';
    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'User', 'url' => null], // Current page without URL
    ];
@endphp
@section('content')
    <div class="container p-0" id="userPage">
        @include('layouts.partials.breadcrumbs', [
            'breadcrumbs' => $breadcrumbs,
            'Page' => $PageTitle,
            'html' =>
                '<a href="' .
                route('user.create') .
                '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center gap-1"><i class="ti ti-plus" style="font-size: 16px;"></i>Create</a>',
        ])

        <div class="row m-0 align-items-center mb-3">
            <div class="col-md-4 ps-0">
                @include('components.select-page', [
                    'id' => 'select', // The ID of the div containing the page select
                    'url' => route('user.index'), // The URL for the AJAX request
                    'tableName' => 'userTable', // The ID of the table to be updated
                ])
            </div>

            <div class="col-md-8 d-flex justify-content-end gap-2 pe-0">
                @include('components.search-bar', [
                    'id' => 'search-form',
                    'url' => route('user.index'),
                    'tableName' => 'userTable',
                ])
                @include('components.import-excel', ['action' => route('user.import')])
                @if (count($user) > 0)
                    @include('components.export-excel', ['action' => route('user.export')])
                @endif

            </div>
        </div>

        @component('components.user-table', [
            'checkbox' => count($user) > 0 ? true : false,
            'headers' => ['User Id','Employee Name', 'Email', 'Designation', 'Status', 'Action'], // Add other headers as needed
        ])
            <tbody id="userTable">
                @include('masters.user._table', ['user' => $user])
            </tbody>
        @endcomponent

        <div class="d-flex justify-content-end mt-2" id="paginationLinks">
            {{ $user->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- @component('components.modal', ['id' => 'sideModal', 'title' => 'Add User'])
        @include('components.user-form')
    @endcomponent --}}

    @component('components.delete-confirmation-modal', [
        'id' => 'deleteConfirmationModal',
        'title' => 'Confirm Delete',
        'message' => 'Are you sure you want to delete this user?',
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
    @include('masters.user.script')
@endpush
