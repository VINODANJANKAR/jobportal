@php
    use App\Http\Controllers\ProfilesController;
@endphp

@foreach (ProfilesController::FORM_FIELDS as $section => $fields)
    <div class="row">
        @foreach ($fields as $field)
            @php
                $colSize = ($field['type'] == 'textarea' || $field['type'] == 'file') ? 'col-md-12' : 'col-md-6';
                // Dynamically populate select options
                if ($field['name'] == 'skill_id') {
                    $field['options'] = $skills;
                } elseif ($field['name'] == 'qualification_id') {
                    $field['options'] = $qualifications;
                } elseif ($field['name'] == 'experience_id') {
                    $field['options'] = $experiences;
                }
            @endphp
            <div class="{{ $colSize }}">
                <div class="form-group d-flex align-items-center">
                    <label for="{{ $field['name'] }}" class="mr-3 mb-0" style="min-width: 120px;">
                        {{ $field['label'] }}
                    </label>
                    <div style="width: 100%">
                        @if ($field['type'] == 'text' || $field['type'] == 'number' || $field['type'] == 'password')
                            <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                                class="form-control rounded-0 text-dark border-light form-control-sm"
                                value="{{ old($field['name'], $profile->{$field['name']} ?? '') }}"
                                @if ($show) readonly @endif>
                        @elseif ($field['type'] == 'textarea')
                            <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control rounded-0 text-dark border-light form-control-sm"
                                @if ($show) readonly @endif>{{ old($field['name'], $profile->{$field['name']} ?? '') }}</textarea>
                        @elseif ($field['type'] == 'select')
                            <select id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                                class="form-control form-select rounded-0 text-dark border-light form-control-sm" @if ($show) disabled @endif >
                                @foreach ($field['options'] as $value => $option)
                                    <option value="{{ $value }}"
                                        {{ old($field['name'], $profile->{$field['name']} ?? '') == $value ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif ($field['type'] == 'file')
                            @if (!$show)
                                <input type="file" id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                                    class="form-control rounded-0 text-dark border-light form-control-sm">
                            @endif
                            @if (!empty($profile->{$field['name']}))
                                <a href="{{ asset('storage/' . $profile->{$field['name']}) }}" target="_blank">
                                    {{ $profile->{$field['name']} }}
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
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
