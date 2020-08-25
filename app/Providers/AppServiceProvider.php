<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\DingTalkProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('DingTalk', function () {
            return app(DingTalkProvider::class);
        });
    }
}
