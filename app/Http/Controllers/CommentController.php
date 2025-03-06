<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        protected CommentRepositoryInterface $repo,
    ) {}

    public function store(CommentRequest $request)
    {
        $this->authorize('create', Comment::class);

        $parent = null;
        if (isset($request->article)) {
            $parent = Article::find($request->article);
        }
        if (isset($request->comment)) {
            $parent = Comment::find($request->comment);
        }
        if (!empty($parent)) {
            $comment = $this->repo->store(
                $request->validated(),
                $parent,
                $request->user()
            );
            $comment->refresh();

            if ($comment->isActive()) {
                $successMessage = 'Комментарий добавлен (<a class="alert-link" href="' . $comment->link . '"> Перейти</a>)';
            } else {
                $successMessage = 'Комментарий добавлен, он будет виден пользователям после модерации';
            }
            return back()->with('success', $successMessage);
        } else {
            return back()->with('error', 'Произошла ошибка при добавлении комментария');
        }
    }
}
