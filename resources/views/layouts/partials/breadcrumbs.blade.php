<div class="d-flex justify-content-between mb-4 align-items-center">
    <div class="overflow-hidden position-relative">
        <div class="pe-3">
            <h2 class="fs-6 mb-0">{{ $Page ?? 'Page Title' }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    @foreach ($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item text-dark">
                            @if (isset($breadcrumb['url']))
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                            @else
                                {{ $breadcrumb['title'] }} <!-- Current page -->
                            @endif
                        </li>
                        @if (!$loop->last)
                            <span class="breadcrumb-divider">•</span>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
    @if (isset($id))
        <button id="{{ $id }}" class="btn btn-info btn-sm rounded-3 shadow-sm d-flex align-items-center gap-1"> <i class="ti ti-plus" style="font-size: 16px;"></i>
            Create </button>
    @endif
    @if (isset($html))
        {!! $html !!}
    @endif

</div>

<style>
    .breadcrumb-item {
        float: left;
        padding-right: var(--bs-breadcrumb-item-padding-x);
        color: var(--bs-breadcrumb-divider-color);
    }

    .breadcrumb-divider {
        padding-right: 8px;
        /* Adjust as needed */
        color: var(--bs-breadcrumb-divider-color);
    }

    .breadcrumb-item+.breadcrumb-item::before {
        float: left;
        padding-right: var(--bs-breadcrumb-item-padding-x);
        color: var(--bs-breadcrumb-divider-color);
        content: var(--bs-breadcrumb-divider, "•");
    }
</style>
