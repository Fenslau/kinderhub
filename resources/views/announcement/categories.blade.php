<a class="mx-1 align-middle link-info"
    href="{{ route('announcements.index', ['category' => $announcement->careCategory->title]) }}">
    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $announcement->careCategory->description }}">
        {{ $announcement->careCategory->title }}:
    </span>
</a>
@foreach ($announcement->sub_category as $subCategory)
<a href="{{ route('announcements.index', ['sub_category' => $subCategory]) }}"
    class="btn btn-outline-info btn-sm py-0 small">
    {{ $subCategory }}
</a>
@endforeach