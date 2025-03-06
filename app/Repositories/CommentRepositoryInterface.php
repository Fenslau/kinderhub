<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface CommentRepositoryInterface
{
    public function store(array $data, Model $parent, User $user): Comment;
    public function needsModeration($user): bool;
}
