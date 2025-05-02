<?php

namespace App\Repositories;

use App\Models\Announcement;
use App\Models\CareCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class AnnouncementRepository implements ReadOnlyRepositoryInterface
{

    public function index(array $params = array()): LengthAwarePaginator
    {
        $announcements = Announcement::query()
            ->orderBy('is_global', 'desc')
            ->orderBy('created_at', 'desc');
        if (!empty($params['care_category'])) {
            $announcements->whereHas('careCategory', function ($query) use ($params) {
                $query->where('title', $params['care_category']);
            });
        }
        if (!empty($params['care_subcategory'])) {
            $announcements->whereHas('careSubCategory', function ($query) use ($params) {
                $query->where('title', $params['care_subcategory']);
            });
        }
        if (!empty($params['multi_care_subcategory'])) {
            $announcements->whereJsonContains(
                'multi_care_subcategory',
                (string)CareCategory::where('title', $params['multi_care_subcategory'])->first()?->id
            );
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
