<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Главная', route('home'));
});


Breadcrumbs::for('articles', function ($trail) {
    $trail->parent('home');
    $trail->push('Статьи', route('articles.index'));
});

Breadcrumbs::for('article', function ($trail, $article) {
    $trail->parent('articles');
    $trail->push($article->title, route('articles.show', $article->slug));
});

Breadcrumbs::for('announcements', function ($trail) {
    $trail->parent('home');
    $trail->push('Объявления', route('announcements.index'));
});

Breadcrumbs::for('announcement', function ($trail, $announcement) {
    $trail->parent('announcements');
    $trail->push($announcement->title, route('announcements.show', $announcement->slug));
});

Breadcrumbs::for('search', function ($trail) {
    $trail->parent('home');
    $trail->push('Поиск', route('search'));
});

Breadcrumbs::for('users', function ($trail) {
    $trail->parent('home');
    $trail->push('Пользователи', route('users.index'));
});

Breadcrumbs::for('user', function ($trail, $user) {
    $trail->parent('users');
    $trail->push($user->name, route('users.show', $user->id));
});

Breadcrumbs::for('cities', function ($trail) {
    $trail->parent('home');
    $trail->push('Города', route('cities.index'));
});

Breadcrumbs::for('city', function ($trail, $city) {
    $trail->parent('cities');
    $trail->push($city->name, route('cities.show', $city->id));
});
