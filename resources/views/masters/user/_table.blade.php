@if ($user->isEmpty())
    <tr>
        <td colspan="6" class="text-center">
            <h4 class="text-muted">No records found</h4>
        </td>
    </tr>
@else
    @foreach ($user as $u)
        <tr>
            <td>
                <div class="checkbox-wrapper">
                    <input type="checkbox" class="selectId" data-id="{{ $u->id }}"
                        id="checkbox-{{ $u->id }}" />
                    <label for="checkbox-{{ $u->id }}" class="checkbox-label"
                        style="width: 100%">{{ $u->id }}</label>
                </div>
            </td>
            <td><a href="{{ route('user.show', $u->id) }}">{{ $u->name }}</a></td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->designation }}</td>
            <td>
                <label class="status-toggle">
                    <input type="checkbox" data-id="{{ $u->id }}" data-status="{{ $u->status }}"
                        {{ $u->status == 'active' ? 'checked' : '' }}>
                    <span class="slider"></span>
                    <span class="status-text">{{ $u->status == 'active' ? 'Active' : 'In-Active' }}</span>
                </label>
            </td>
            <td>
                <a href="{{ route('user.edit', $u->id) }}" class="btn btn-info btn-sm">
                    Edit
                </a>
                <button class="btn btn-danger btn-sm deleteUser" data-id="{{ $u->id }}">Delete</button>
            </td>
        </tr>
    @endforeach
@endif

<style>
    /* Style the toggle switch container */
    .status-toggle {
        display: flex;
        /* Use flexbox to align text and toggle horizontally */
        align-items: center;
        /* Align the items vertically in the center */
        gap: 10px;
        /* Add space between the toggle switch and the text */
        cursor: pointer;
        /* Make the whole label clickable */
        width: 130px;
    }

    /* Style the toggle switch */
    .status-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* Style for the slider */
    .status-toggle .slider {
        position: relative;
        width: 34px;
        height: 20px;
        background-color: red;
        /* Default color (inactive state) */
        border-radius: 34px;
        transition: 0.4s;
    }

    /* Style for the slider circle */
    .status-toggle .slider:before {
        content: "";
        position: absolute;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: white;
        left: 4px;
        bottom: 4px;
        transition: 0.4s;
    }

    /* When the checkbox is checked, change the slider color */
    .status-toggle input:checked+.slider {
        background-color: green;
        /* Active state color */
    }

    /* Move the slider circle to the right when checked */
    .status-toggle input:checked+.slider:before {
        transform: translateX(14px);
    }

    /* Style for the status text */
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
    document.querySelectorAll('.status-toggle input').forEach(function(input) {
        input.addEventListener('change', function() {
            var userId = input.dataset.id;
            var currentStatus = input.dataset.status;

            // Toggle the status
            var newStatus = currentStatus === 'active' ? 'deactive' : 'active';

            // Perform Ajax request to update the status
            fetch('/update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the status text dynamically
                        const statusText = input.closest('.status-toggle').querySelector(
                            '.status-text');
                        statusText.textContent = newStatus === 'active' ? 'Active' : 'In-Active';
                        // Update the input's data-status attribute
                        input.dataset.status = newStatus;
                    } else {
                        alert('Failed to update status');
                        input.checked = !input.checked; // Revert toggle
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the status.');
                    input.checked = !input.checked; // Revert toggle
                });
        });
    });
</script>
