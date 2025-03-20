@if ($profiles->isEmpty())
    <!-- No profiles found message -->
    <tr>
        <td colspan="8" class="text-center">
            <h4 class="text-muted">No profiles found</h4>
        </td>
    </tr>
@else
    @foreach ($profiles as $profile)
        <tr>
            <td>
                <div class="checkbox-wrapper">
                    <input type="checkbox" class="selectId" data-id="{{ $profile->id }}"
                        id="checkbox-{{ $profile->id }}" />
                    <label for="checkbox-{{ $profile->id }}" class="checkbox-label"
                        style="width: 100%">{{ $profile->id }}</label>
                </div>
            </td>
            <td><a href="{{ route('profiles.show', $profile->id) }}">{{ $profile->first_name }}
                    {{ $profile->last_name }}</a></td>


            <td>{{ $profile->city }}</td>
            <td>{{ $profile->experiences->experience }}
            </td>
            <td> {{ $profile->qualifications->qualification }}
            </td>
            <td>{{ $profile->skills->skill }}
            </td>
            <td>
                @if (!empty($profile->cv) && $profile->cv != 'NA')
                    <a href="{{ asset('storage/cv/' . $profile->cv) }}" class="btn btn-dark btn-sm"
                        download="CV_{{ $profile->first_name }}.pdf">CV</a>
                @else
                    NA
                @endif

            </td>
            <td>
                <a href="{{ route('profiles.edit', $profile->id) }}" class="btn btn-info btn-sm">
                    Edit
                </a>
                <button class="btn btn-danger btn-sm deleteProfile" data-id="{{ $profile->id }}">Delete</button>
            </td>
        </tr>
    @endforeach
@endif

<script>
    document.querySelectorAll('.selectId').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let selectedProfiles = [];
            document.querySelectorAll('.selectId:checked').forEach(function(checkbox) {
                selectedProfiles.push(checkbox.dataset.id);
            });
            document.querySelector('#selected_ids').value = (selectedProfiles.length > 0) ?
                selectedProfiles.join(',') : '';
        });
    });
</script>
