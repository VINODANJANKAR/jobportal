@if ($jobPosts->isEmpty())
    <!-- No profiles found message -->
    <tr>
        <td colspan="10" class="text-center">
            <h4 class="text-muted">No jobPosts found</h4>
        </td>
    </tr>
@else
    @foreach ($jobPosts as $jobPost)
        <tr>
            <td><a href="{{ route('job-posts.show', $jobPost->id) }}">{{ $jobPost->post_id }}</a></td>

            <td>{{ $jobPost->post_date ?? '' }}</td>
            <td>{{ $jobPost->users->name ?? '' }}</td>
            <td>{{ $jobPost->valid_up_to ?? '' }}</td>
            <td>{{ $jobPost->post_title }}</td>
            <td>{{ $jobPost->company_name ?? '' }}</td>
            <td>
                @if (!empty($jobPost->upload_image))
                    <a href="{{ asset('storage/jobPost/' . $jobPost->upload_image) }}" data-fancybox="gallery"
                        data-caption="{{ $jobPost->post_title }}">
                        <img src="{{ asset('storage/jobPost/' . $jobPost->upload_image) }}" alt="Job Image" width="50"
                            height="50" style="cursor: pointer;">
                    </a>
                @else
                    NA
                @endif

            </td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="statusSwitch{{ $jobPost->id }}"
                        {{ now()->lessThanOrEqualTo($jobPost->valid_up_to) ? 'checked' : '' }}
                        onchange="updateStatus({{ $jobPost->id }}, this.checked)">
                    <label class="form-check-label" for="statusSwitch{{ $jobPost->id }}">
                        {{ now()->lessThanOrEqualTo($jobPost->valid_up_to) ? 'Active' : 'Expired' }}
                    </label>
                </div>
            </td>
            <td>
                @if (auth()->user()->role == 'admin')
                    @if ($jobPost->status != 'approved')
                        <button type="button" class="btn btn-info btn-sm approve-btn"
                            data-job-id="{{ $jobPost->id }}" data-status="{{ $jobPost->status }}">
                            Approve
                        </button>
                    @else
                        <span style="color: blue">{{ $jobPost->status }}</span>
                    @endif
                @elseif(auth()->user()->role == 'user')
            @if ($jobPost->status == 'pending')
            <span style="color:red">{{ $jobPost->status }}</span>
                @else
                <span  style="color: blue ">{{ $jobPost->status }}</span>
                    
                @endif
                @endif
            </td>
            <td>
                <form action="{{ route('job-posts.repost', $jobPost->id) }}" method="POST"
                    style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Repost</button>
                </form>
                @if (auth()->user()->id == $jobPost->post_by_id || auth()->user()->role == 'admin')
                    <a href="{{ route('job-posts.edit', $jobPost->id) }}" class="btn btn-info btn-sm">
                        Edit
                    </a>
                    <button class="btn btn-danger btn-sm deleteJobPost" data-id="{{ $jobPost->id }}">Delete</button>
                @endif
            </td>
        </tr>
    @endforeach
@endif
