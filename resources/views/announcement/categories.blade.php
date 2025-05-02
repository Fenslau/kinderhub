<a class="mx-1 align-middle link-info"
    href="{{ route('announcements.index', ['care_category' => $announcement->careCategory->title]) }}">
    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $announcement->careCategory->description }}">
        {{ $announcement->careCategory->title }}
    </span>
</a>
@if($announcement->careSubcategory()->count())
<a class="mx-1 align-middle link-info"
    href="{{ route('announcements.index', ['care_subcategory' => $announcement->careSubcategory->title]) }}">
    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $announcement->careSubcategory->description }}">
        {{ $announcement->careSubcategory->title }}
    </span>
</a>
@endif
@foreach ($announcement->multi_care_subcategory ?? [] as $multiCareSubcategory)
<a href="{{ route('announcements.index', ['multi_care_subcategory' => \App\Models\CareCategory::where('id', $multiCareSubcategory)->first()?->title]) }}"
    class="btn btn-outline-info btn-sm py-0 small">
    {{ \App\Models\CareCategory::where('id', $multiCareSubcategory)->first()?->title }}
</a>
@endforeach