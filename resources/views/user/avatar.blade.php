@php
$styleString = 'style="max-height: ' . ($maxHeight ?? 1.5) . 'rem"';
@endphp
@empty($user->profile?->image)
<span class="text-muted align-middle ps-2"><i class="fa fa-user fa-{{ $xHeight ?? 1 }}x"></i></span>
@else
<img class="rounded-circle d-inline-block"
    {!! $styleString !!}
    src="{{ filter_var($user->profile->image, FILTER_VALIDATE_URL) 
             ? $user->profile->image 
             : Storage::url($user->profile->image) }}"
    alt="{{ $user->name }}">
@endempty