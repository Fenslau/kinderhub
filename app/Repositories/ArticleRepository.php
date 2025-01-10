<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ArticleRepository implements ReadOnlyRepositoryInterface
{

    public function index(array $params = array()): LengthAwarePaginator
    {
        $articles = Article::query()
            ->orderBy('is_global', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate();
        return $articles;
    }

    public function show(string|Model $model): Article
    {
        if (is_string($model)) {
            $article = Article::where('slug', $model)->firstOrFail();
        } elseif ($model instanceof Article) {
            $article = $model;
        }
        return $article;
    }
}
