<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use App\Repositories\ReadOnlyRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(
        protected ReadOnlyRepositoryInterface $repo,
    ) {}

    public function index(AnnouncementRequest $request): View
    {
        $announcements = $this->repo->index($request->all());
        return view('announcement.index', compact('announcements'));
    }

    public function show(string $slug): View
    {
        $announcement = $this->repo->show($slug);
        return view('announcement.show', compact('announcement'));
    }
}
