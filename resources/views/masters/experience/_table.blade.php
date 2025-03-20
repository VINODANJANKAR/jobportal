@if ($experiences->isEmpty())
    <tr>
        <td colspan="3" class="text-center">
            <h4 class="text-muted">No records found</h4>
        </td>
    </tr>
@else
    @foreach ($experiences as $experience)
        <tr>
            <td>
                <div class="checkbox-wrapper">
                    <input type="checkbox" class="selectId" data-id="{{ $experience->id }}"
                        id="checkbox-{{ $experience->id }}" />
                    <label for="checkbox-{{ $experience->id }}" class="checkbox-label"
                        style="width: 100%">{{ $experience->id }}</label>
                </div>
            </td>
            <td><a href="{{ route('experiences.show', $experience->id) }}">{{ $experience->experience }}</a></td>
            <td>
                <a href="{{ route('experiences.edit', $experience->id) }}" class="btn btn-info btn-sm">
                    Edit
                </a>
                <button class="btn btn-danger btn-sm deleteExperience" data-id="{{ $experience->id }}">Delete</button>
            </td>
        </tr>
    @endforeach
@endif

<script>
    document.querySelectorAll('.selectId').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let selectedExperiences = [];
            document.querySelectorAll('.selectId:checked').forEach(function(checkbox) {
                selectedExperiences.push(checkbox.dataset.id);
            });
            document.querySelector('#selected_ids').value = (selectedExperiences.length > 0) ?
                selectedExperiences.join(',') : '';
        });
    });
</script>
