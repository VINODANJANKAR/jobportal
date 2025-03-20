@php
    use Carbon\Carbon;
    $today = Carbon::now()->toDateString(); // Get today's date (YYYY-MM-DD)
    $endOfMonth = Carbon::now()->addDays(30)->toDateString(); // Get last date of the current month
@endphp

@if ($section == 'job_posting_details')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="post_date" class="mr-3 mb-0" style="min-width: 120px;">Post Date</label>
                <div class="w-100">
                    <input type="date" name="post_date"
                        class="form-control rounded-0 text-dark border-light @error('post_date') is-invalid @enderror"
                        value="{{ old('post_date', $jobPost->post_date ?? $today) }}"
                        @if ($show) readonly @endif>
                    @error('post_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="valid_up_to" class="mr-3 mb-0" style="min-width: 120px;">Valid Up To</label>
                <div class="w-100">
                    <input type="date" name="valid_up_to"
                        class="form-control rounded-0 text-dark border-light @error('valid_up_to') is-invalid @enderror"
                        value="{{ old('valid_up_to', $jobPost->valid_up_to ?? $endOfMonth) }}"
                        @if ($show) readonly @endif>
                    @error('valid_up_to')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="post_type" class="mr-3 mb-0" style="min-width: 120px;">Post Type</label>
                <div class="w-100">
                    <select name="post_type" id="post_type"
                        class="form-control form-select rounded-0 text-dark border-light @error('post_type') is-invalid @enderror"
                        onchange="toggleImageUpload()" @if ($show) disabled @endif>
                        <option value="Regular"
                            {{ old('post_type', $jobPost->post_type ?? '') == 'Regular' ? 'selected' : '' }}>
                            Regular</option>
                        <option value="Image"
                            {{ old('post_type', $jobPost->post_type ?? '') == 'Image' ? 'selected' : '' }}>Image
                        </option>
                    </select>
                    @error('post_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="job_type" class="mr-3 mb-0" style="min-width: 120px;">Job Type</label>
                <div class="w-100">
                    <select name="job_type" id="job_type"
                        class="form-control form-select rounded-0 text-dark border-light @error('job_type') is-invalid @enderror"
                        @if ($show) disabled @endif>
                        <option value="On-Roll"
                            {{ old('job_type', $jobPost->job_type ?? '') == 'On-Roll' ? 'selected' : '' }}>On-Roll
                        </option>
                        <option value="Contractual"
                            {{ old('job_type', $jobPost->job_type ?? '') == 'Contractual' ? 'selected' : '' }}>
                            Contractual
                        </option>
                        <option value="Temporary"
                            {{ old('job_type', $jobPost->job_type ?? '') == 'Temporary' ? 'selected' : '' }}>Temporary
                        </option>
                    </select>
                    @error('job_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" id="image_upload" style="display: none;">
            <div class="form-group d-flex align-items-center">
                <label for="upload_image" class="mr-3 mb-0" style="min-width: 120px;">Upload Image</label>
                <div class="w-100">
                    @if (!$show)
                        <input type="file" name="upload_image"
                            class="form-control rounded-0 text-dark border-light @error('upload_image') is-invalid @enderror"
                            accept="image/*">
                    @endif
                    @if ($show)
                        @if (isset($jobPost->upload_image) && $jobPost->upload_image)
                            <a href="{{ asset('storage/jobPost/' . $jobPost->upload_image) }}" data-fancybox="gallery"
                                data-caption="{{ $jobPost->post_title }}">
                                <img src="{{ asset('storage/jobPost/' . $jobPost->upload_image) }}" alt="Job Image"
                                    width="100" height="100" style="cursor: pointer;" class="img-thumbnail">
                            </a>
                        @endif
                    @endif
                    @error('upload_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    @if (isset($jobPost->upload_image) && $jobPost->upload_image && !$show)
        <div class="row">
            <div class="col-md-6">
                <div class="form-group d-flex align-items-center">
                    <label class="mr-3 mb-0" style="min-width: 120px;">Uploaded Image</label>
                    <a href="{{ asset('storage/jobPost/' . $jobPost->upload_image) }}" data-fancybox="gallery"
                        data-caption="{{ $jobPost->post_title }}">
                        <img src="{{ asset('storage/jobPost/' . $jobPost->upload_image) }}" alt="Job Image"
                            width="100" height="100" style="cursor: pointer;" class="img-thumbnail">
                    </a>
                </div>
            </div>
        </div>
    @endif

@endif

@if ($section == 'contact_information')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="company_name" class="mr-3 mb-0" style="min-width: 120px;">Company Name</label>
                <div class="w-100">
                    <input type="text" name="company_name"
                        class="form-control form-select rounded-0 text-dark border-light @error('company_name') is-invalid @enderror"
                        value="{{ old('company_name', $jobPost->company_name ?? '') }}"
                        @if ($show) readonly @endif>
                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="position" class="mr-3 mb-0" style="min-width: 120px;">Position</label>
                <div class="w-100">
                    <input type="text" name="position" id="position"
                        class="form-control rounded-0 text-dark border-light @error('position') is-invalid @enderror"
                        value="{{ old('position', $jobPost->position ?? '') }}"
                        @if ($show) readonly @endif>
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="skill_id" class="mr-3 mb-0" style="min-width: 120px;">Skills</label>
                <div class="w-100">
                    <select name="skill_id" id="skill_id"
                        class="form-control form-select rounded-0 text-dark border-light @error('skill_id') is-invalid @enderror"
                        @if ($show) disabled @endif>
                        @foreach ($skills as $skill)
                            <option value="{{ $skill->id }}"
                                {{ old('skill_id', $jobPost->skill_id ?? '') == $skill->id ? 'selected' : '' }}>
                                {{ $skill->skill }}</option>
                        @endforeach
                    </select>
                    @error('skill_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="experience_id" class="mr-3 mb-0" style="min-width: 120px;">Experiences</label>
                <div class="w-100">
                    <select name="experience_id" id="experience_id"
                        class="form-control form-select rounded-0 text-dark border-light @error('experience_id') is-invalid @enderror"
                        @if ($show) disabled @endif>
                        @foreach ($experiences as $experience)
                            <option value="{{ $experience->id }}"
                                {{ old('experience_id', $jobPost->experience_id ?? '') == $experience->id ? 'selected' : '' }}>
                                {{ $experience->experience }}</option>
                        @endforeach
                    </select>
                    @error('experience_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="location" class="mr-3 mb-0" style="min-width: 120px;">Location</label>
                <div class="w-100">
                    <input type="text" name="location"
                        class="form-control rounded-0 text-dark border-light @error('location') is-invalid @enderror"
                        value="{{ old('location', $jobPost->location ?? '') }}"
                        @if ($show) readonly @endif>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group d-flex align-items-center">
                <label for="job_description" class="mr-3 mb-0" style="min-width: 120px;">Job Description</label>
                <div class="w-100">
                    @if (!$show)
                        <textarea name="job_description" id="job_details"
                            class="form-control rounded-0 text-dark border-light @error('job_description') is-invalid @enderror"
                            @if ($show) readonly @endif>{{ old('job_description', $jobPost->job_description ?? '') }}</textarea>
                        @error('job_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @else
                        <div class="rounded border p-3 m-1 bg-white shadow-sm" style="border-color: #ccc !important;">
                            {!! $jobPost->job_description !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@if ($section == 'company_requirements')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="contact_person" class="mr-3 mb-0" style="min-width: 120px;">Contact Person</label>
                <div class="w-100">
                    <input type="text" name="contact_person"
                        class="form-control rounded-0 text-dark border-light @error('contact_person') is-invalid @enderror"
                        value="{{ old('contact_person', $jobPost->contact_person ?? '') }}"
                        @if ($show) readonly @endif>
                    @error('contact_person')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="contact_email" class="mr-3 mb-0" style="min-width: 120px;">Contact Email</label>
                <div class="w-100">
                    <input type="text" name="contact_email" id="contact_email"
                        class="form-control rounded-0 text-dark border-light @error('contact_email') is-invalid @enderror"
                        value="{{ old('contact_email', $jobPost->contact_email ?? '') }}"
                        @if ($show) readonly @endif>
                    @error('contact_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <label for="contact_phone" class="mr-3 mb-0" style="min-width: 120px;">Contact Phone</label>
                <div class="w-100">
                    <input type="text" name="contact_phone"
                        class="form-control rounded-0 text-dark border-light @error('contact_phone') is-invalid @enderror"
                        value="{{ old('contact_phone', $jobPost->contact_phone ?? '') }}"
                        @if ($show) readonly @endif>
                    @error('contact_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
@endif

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

    .ck-content ul {
        padding-left: 2rem;
    }
</style>

@push('styles')
    <style>
        .ck-editor__editable {
            line-height: 1.5em;
            /* Controls the space between lines */
            min-height: calc(1.5em * 5);
            /* Height for 5 rows of text */
        }

        .ck-balloon-panel_visible {
            display: none !important;
        }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
@endpush
