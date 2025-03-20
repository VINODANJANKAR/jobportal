@extends('layouts.auth')

@section('content')
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-4 col-xxl-4 p-4">
                        <div class="card mb-0">
                            <div class="card-body p-4">
                                <!-- Logo -->
                                <a href="{{ url('/') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('images/logos/TJobs_dark.png') }}" width="180" alt="Logo">
                                </a>

                                <!-- Display Errors -->
                                @if ($errors->has('email'))
                                    <div class="alert alert-danger">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif

                                <!-- Login Form -->
                                <form method="POST" action="{{ route('login') }}" class="p-2">
                                    @csrf

                                    <!-- Email Input -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Username (Email)</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Password Input -->
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required autocomplete="current-password">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Remember Me Checkbox -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                Remember this device
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Login Button -->
                                    <button type="submit" class="btn btn-primary w-100 fs-4 mb-4 rounded-2">Sign In</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
