@extends('layouts.app')
@php
    $title = 'User - ' . config('app.name');
    $PageTitle = isset($user)
        ? ($show
            ? 'View User (User Id -' . $user->id . ')'
            : 'Edit User (User Id - ' . $user->id . ')')
        : 'Create User';

    $breadcrumbs = [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => 'User', 'url' => route('user.index')],
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
                        route('user.edit', $user->id) .
                        '" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="ti ti-pencil" style="font-size: 16px;"></i></a> </div>'
                    : null,
            ]))

        <form id="userForm" enctype="multipart/form-data">
            <input type="hidden" id="user_id" name="id" value="{{ $user->id ?? null }}">
            <div class="p-4 mb-4"
                style=" box-shadow: -6px 1px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #fff; overflow: hidden;">
                @include('components.user-form', [
                    'show' => $show,
                ])
                <div class="form-group mb-3 text-end">
                    <a href="{{ route('user.index') }}" class="btn btn-danger btn-md">Cancel</a>
                    @if (!$show)
                        <button type="submit" class="btn btn-primary btn-md"
                            style="width: 150px">{{ isset($user) ? 'Update' : 'Create' }}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    @include('masters.user.script')
@endpush
