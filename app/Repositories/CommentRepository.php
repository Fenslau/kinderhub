<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentRepository implements CommentRepositoryInterface
{
    public function store(array $data, Model $parent, User $user): Comment
    {
        return DB::transaction(function () use ($data, $parent, $user) {
            $comment = $parent->comments()->create($data);
            $comment->user()->associate($user);
            if ($this->needsModeration($user)) {
                $comment->is_active = false;
            }
            $comment->save();
            return $comment;
        });
    }

    public function needsModeration($user): bool
    {
        return $user->comments()->count() < config('constants.defines.comments_moderated');
    }
}
