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

Breadcrumbs::for('article', function ($trail, $event) {
    $trail->parent('articles');
    $trail->push($event->title, route('articles.show', $event->slug));
});

Breadcrumbs::for('search', function ($trail) {
    $trail->parent('home');
    $trail->push('Поиск', route('search'));
});

Breadcrumbs::for('user.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Пользователи', route('user.index'));
});

Breadcrumbs::for('user.show', function ($trail, $user) {
    $trail->parent('user.index');
    $trail->push($user->name, route('user.show', $user->id));
});

Breadcrumbs::for('city.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Города', route('city.index'));
});

Breadcrumbs::for('city.show', function ($trail, $city) {
    $trail->parent('city.index');
    $trail->push($city->name, route('city.show', $city->id));
});
