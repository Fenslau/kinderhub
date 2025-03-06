@foreach ($article->tags as $tag)
<a href="{{ route('articles.index', ['tag' => $tag->name]) }}"
    class="btn btn-outline-info btn-sm py-0 small">
    {{ $tag->name }}
</a>
@endforeach