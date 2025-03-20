<div class="row">
    <div class="col-md-6">
        <div class="form-group d-flex align-items-center">
            <label for="experience" class="mr-3 mb-0" style="min-width: 120px;">Experience</label>
            <input type="text" id="experience" name="experience"
                class="form-control rounded-0 text-dark border-secondary form-control-sm @error('experience') is-invalid @enderror"
                value="{{ old('experience', $experience->experience ?? '') }}" @if ($show) readonly @endif>
        </div>
        @error('experience')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

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
</style>
