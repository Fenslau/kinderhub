<?php

namespace App\Repositories;

use App\Models\Announcement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class AnnouncementRepository implements ReadOnlyRepositoryInterface
{

    public function index(array $params = array()): LengthAwarePaginator
    {
        $announcements = Announcement::query()
            ->orderBy('is_global', 'desc')
            ->orderBy('created_at', 'desc');
        if (!empty($params['category'])) {
            $announcements->whereHas('careCategory', function ($query) use ($params) {
                $query->where('title', $params['category']);
            });
        }
        if (!empty($params['sub_category'])) {
            $announcements->whereJsonContains('sub_category', $params['sub_category']);
        }
        $announcements = $announcements->paginate(config('constants.defines.announcements_per_page'));
        return $announcements;
    }

    public function show(string|Model $model): Announcement
    {
        if (is_string($model)) {
            $announcement = Announcement::where('slug', $model)->firstOrFail();
        } elseif ($model instanceof Announcement) {
            $announcement = $model;
        }
        return $announcement;
    }
}
