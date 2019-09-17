<?php

namespace App\Providers;

use Domain\Services\Rss\RssReader;
use Domain\Services\Rss\RssReaderInterface;
use Domain\Source\Repository\SourceRepository;
use Domain\Source\Repository\SourceRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SourceRepositoryInterface::class,
            SourceRepository::class
        );

        $this->app->bind(
            RssReaderInterface::class,
            RssReader::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
