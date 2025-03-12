@foreach ($announcement->sub_category as $subCategory)
<a href="{{ route('announcements.index', ['sub_category' => $subCategory]) }}"
    class="btn btn-outline-info btn-sm py-0 small">
    {{ $subCategory }}
</a>
@endforeach