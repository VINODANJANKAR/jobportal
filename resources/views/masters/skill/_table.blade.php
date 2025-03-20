@if ($skill->isEmpty())
    <tr>
        <td colspan="3" class="text-center">
            <h4 class="text-muted">No records found</h4>
        </td>
    </tr>
@else
    @foreach ($skill as $s)
        <tr>
            <td>
                <div class="checkbox-wrapper">
                    <input type="checkbox" class="selectId" data-id="{{ $s->id }}"
                        id="checkbox-{{ $s->id }}" />
                    <label for="checkbox-{{ $s->id }}" class="checkbox-label"
                        style="width: 100%">{{ $s->id }}</label>
                </div>
            </td>
            <td><a href="{{ route('skills.show', $s->id) }}">{{ $s->skill }}</a></td>
            <td>
                <a href="{{ route('skills.edit', $s->id) }}" class="btn btn-info btn-sm">
                    Edit
                </a>
                <button class="btn btn-danger btn-sm deleteSkill" data-id="{{ $s->id }}">Delete</button>
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
