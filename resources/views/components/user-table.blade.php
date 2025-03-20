<div class="table-responsive rounded-3 m-0">
    <table class="table table-bordered table-striped table-hover shadow-lg rounded-3 m-0">
        <thead class="table-theme">
            <tr>
                @foreach ($headers as $index => $header)
                    @if ($index === 0 && $checkbox)
                        <th>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="selectId me-3" id="select-all" />
                                {{ $header }}
                            </div>
                        </th>
                    @else
                        <th>{{ $header }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        {{ $slot }}
    </table>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectAllCheckbox = document.getElementById("select-all");
        const checkboxes = document.querySelectorAll(".selectId");
        selectAllCheckbox.addEventListener("change", function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });
</script>
