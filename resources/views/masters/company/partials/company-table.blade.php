@if ($companies->isEmpty())
    <!-- No records found message -->
    <tr>
        <td colspan="3" class="text-center">
            <h4 class="text-muted">No records found</h4>
        </td>
    </tr>
@else
    @foreach ($companies as $company)
        <tr>
            <td>
                <div class="checkbox-wrapper">
                    <input type="checkbox" class="selectId" data-id="{{ $company->id }}"
                        id="checkbox-{{ $company->id }}" />
                    <label for="checkbox-{{ $company->id }}" class="checkbox-label">{{ $company->name }}</label>
                </div>
            <td>
                <a href="{{ route('company-location.show', $company->id) }}" class="">
                    {{ $company->locations_count }} Locations
                </a>
            </td>
            <td>
                {{-- <div class="d-flex justify-content-evenly align-items-center"> --}}
                <button class="btn btn-warning btn-sm addLocationBtn" data-id="{{ $company->id }}">Add
                    Location</button>
                <button class="btn btn-info btn-sm editCompany" data-id="{{ $company->id }}">Edit</button>
                <button class="btn btn-danger btn-sm deleteCompany" data-id="{{ $company->id }}">Delete</button>
                <div>
            {{-- </td> --}}
        </tr>
    @endforeach
@endif

<style>

</style>
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
