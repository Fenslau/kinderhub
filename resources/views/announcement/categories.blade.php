<a class="mx-1 align-middle link-info"
    href="{{ route('announcements.index', ['care_category' => $announcement->careCategory->title]) }}">
    <span
        @if($announcement->careCategory->description)
        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $announcement->careCategory->description }}"
        @endif>
        {{ $announcement->careCategory->title }}
    </span>
</a>

@php
$multiCareSubcategories = \App\Models\CareCategory::whereIn('id', $announcement->multi_care_subcategory ?? [])->get()->keyBy('id');
@endphp
@foreach ($multiCareSubcategories as $multiCareSubcategory)
<a href="{{ route('announcements.index', ['multi_care_subcategory' => $multiCareSubcategory->title]) }}"
    class="btn btn-outline-info btn-sm py-0 small">
    <span
        @if($multiCareSubcategory->description)
        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $multiCareSubcategory->description }}"
        @endif>
        {{ $multiCareSubcategory->title }}
    </span>
</a>
@endforeach