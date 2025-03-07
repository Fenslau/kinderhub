<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements ReadOnlyRepositoryInterface
{
    public function index(array $params = array()): LengthAwarePaginator
    {
        $users = User::query()
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->paginate(config('constants.defines.users_per_page'));
        return $users;
    }

    public function show(string|Model $model): User
    {
        $user = $model->load('articles.comments.comments.comments', 'profile')
            ->loadCount('comments');
        return $user;
    }
}
