<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\PropertyFeatureRepository\PropertyFeatureRepositoryInterface;
use App\Repositories\PropertyFeatureRepository\PropertyFeatureRepository;

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
            PropertyFeatureRepositoryInterface::class,
            PropertyFeatureRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
