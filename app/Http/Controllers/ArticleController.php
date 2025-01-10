<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Repositories\ReadOnlyRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        protected ReadOnlyRepositoryInterface $repo,
    ) {}

    public function index(ArticleRequest $request): View
    {
        $articles = $this->repo->index();
        return view('welcome', compact('articles'));
    }

    public function show(string $slug): View
    {
        $article = $this->repo->show($slug);
        return view('welcome', compact('article'));
    }
}
