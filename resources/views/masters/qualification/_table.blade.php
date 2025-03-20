@if ($qualifications->isEmpty())
    <tr>
        <td colspan="3" class="text-center">
            <h4 class="text-muted">No records found</h4>
        </td>
    </tr>
@else
    @foreach ($qualifications as $Q)
        <tr>
            <td>
                <div class="checkbox-wrapper">
                    <input type="checkbox" class="selectId" data-id="{{ $Q->id }}"
                        id="checkbox-{{ $Q->id }}" />
                    <label for="checkbox-{{ $Q->id }}" class="checkbox-label"
                        style="width: 100%">{{ $Q->id }}</label>
                </div>
            </td>
            <td><a href="{{ route('qualifications.show', $Q->id) }}">{{ $Q->qualification }}</a></td>
            <td>
                <a href="{{ route('qualifications.edit', $Q->id) }}" class="btn btn-info btn-sm">
                    Edit
                </a>
                <button class="btn btn-danger btn-sm deleteQualification" data-id="{{ $Q->id }}">Delete</button>
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
