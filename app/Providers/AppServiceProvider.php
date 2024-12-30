<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('google', \SocialiteProviders\Google\Provider::class);
            $event->extendSocialite('vkontakte', \SocialiteProviders\VKontakte\Provider::class);
            $event->extendSocialite('odnoklassniki', \SocialiteProviders\Odnoklassniki\Provider::class);
            $event->extendSocialite('mailru', \SocialiteProviders\Mailru\Provider::class);
            $event->extendSocialite('yandex', \SocialiteProviders\Yandex\Provider::class);
        });
    }
}
