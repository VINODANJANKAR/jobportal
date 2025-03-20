<div class="row">
    <div class="col-md-6">
        <div class="form-group d-flex align-items-center">
            <label for="name" class="mr-3 mb-0" style="min-width: 120px;">Employee Name</label>
            <input type="text" id="name" name="name"
                class="form-control rounded-0 text-dark border-light form-control-sm @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name ?? '') }}" @if ($show) readonly @endif>
        </div>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="form-group d-flex align-items-center">
            <label for="designation" class="mr-3 mb-0" style="min-width: 120px;">Designation</label>
            <input type="text" id="designation" name="designation"
                class="form-control rounded-0 text-dark border-light form-control-sm @error('designation') is-invalid @enderror"
                value="{{ old('designation', $user->designation ?? '') }}"
                @if ($show) readonly @endif>
        </div>
        @error('designation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group d-flex align-items-center">
            <label for="email" class="mr-3 mb-0" style="min-width: 120px;">Email</label>
            <input type="email" id="email" name="email"
                class="form-control rounded-0 text-dark border-light form-control-sm @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email ?? '') }}" @if ($show) readonly @endif>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <div class="form-group d-flex align-items-center">
            <label for="password" class="mr-3 mb-0" style="min-width: 120px;">Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password"
                    class="form-control rounded-0 text-dark border-light form-control-sm @error('password') is-invalid @enderror"
                    value="{{ old('password') }}" @if ($show) readonly @endif>
                <div class="input-group-append">
                    <span id="toggle-password">
                        <i class="fa fa-eye text-dark border-light"></i>
                    </span>
                </div>
            </div>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group d-flex align-items-center">
            <label for="password_confirmation" class="mr-3 mb-0" style="min-width: 120px;">Confirm Password</label>
            <div class="input-group">
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="form-control rounded-0 text-dark border-light form-control-sm @error('password_confirmation') is-invalid @enderror"
                    value="{{ old('password_confirmation') }}" @if ($show) readonly @endif>
                <div class="input-group-append">
                    <span id="toggle-password-confirmation">
                        <i class="fa fa-eye text-dark border-light"></i>
                    </span>
                </div>
            </div>
        </div>
        @error('password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Add this script at the bottom of your page or inside a script tag -->
<script>
    // Toggle password visibility for the 'password' field
    document.getElementById('toggle-password').addEventListener('click', function() {
        var passwordField = document.getElementById('password');
        var passwordIcon = this.querySelector('i');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    });

    // Toggle password visibility for the 'password_confirmation' field
    document.getElementById('toggle-password-confirmation').addEventListener('click', function() {
        var passwordConfirmationField = document.getElementById('password_confirmation');
        var confirmationIcon = this.querySelector('i');

        if (passwordConfirmationField.type === 'password') {
            passwordConfirmationField.type = 'text';
            confirmationIcon.classList.remove('fa-eye');
            confirmationIcon.classList.add('fa-eye-slash');
        } else {
            passwordConfirmationField.type = 'password';
            confirmationIcon.classList.remove('fa-eye-slash');
            confirmationIcon.classList.add('fa-eye');
        }
    });
</script>

<style>
    .form-group {
        margin-bottom: 1rem;
        /* Increased space between form groups */
    }

    .form-control {
        color: black;
        /* Set input text color to black */
        font-size: 0.75rem;
        /* Small font size */
        padding: 0.375rem;
        /* Add padding for better spacing */
    }

    .invalid-feedback {
        font-size: 0.75rem;
        /* Smaller error text */
    }

    .input-group-append i {
        border-radius:  0 0.3rem 0.3rem 0;
        border: 1px solid #dddddd;
        height: 100%;
        display: inline-flex;
        align-items: center;
        border-left: 0px;
        padding-inline: 10px;
        /* Reduced padding inside the icon container */
    }

    /* Customize eye icon for better visibility */
    .fa-eye,
    .fa-eye-slash {
        font-size: 1rem;
        /* Smaller font size for icons */
    }
</style>
