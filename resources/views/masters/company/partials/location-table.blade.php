@if ($locations->isEmpty())
    <tr>
        <td colspan="3" class="text-center">
            <h4 class="text-muted">No records found</h4>
        </td>
    </tr>
@else
    @foreach ($locations as $location)
        <tr>
            <td>{{ $location->location }}</td>
            <td>{{ $location->city }}</td>
            <td>{{ $location->address }}</td>
            <td>
                    <button class="btn btn-warning btn-sm editLocation" data-id="{{ $location->id }}"
                        data-location="{{ $location->location }}" data-city="{{ $location->city }}"
                        data-address="{{ $location->address }}">
                        Edit
                    </button>
                    <!-- Delete Button -->
                    <button class="btn btn-danger btn-sm deleteLocation" data-id="{{ $location->id }}">
                        Delete
                    </button>
            </td>
        </tr>
    @endforeach
@endif
