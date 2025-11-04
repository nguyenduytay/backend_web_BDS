<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\ContactRepository\ContactRepository;
use App\Repositories\LocationsRepository\LocationRepository;
use App\Repositories\LocationsRepository\LocationRepositoryInterface;
use App\Repositories\RepositoryInterface;
use App\Repositories\UsersRepository\UsersRepositoryInterface;
use App\Repositories\UsersRepository\UsersRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ContactRepository\ContactRepositoryInterface;
use App\Repositories\DashboardRepository\DashboardRepository;
use App\Repositories\DashboardRepository\DashboardRepositoryInterface;
use App\Repositories\FeatureRepository\FeatureRepository;
use App\Repositories\FeatureRepository\FeatureRepositoryInterface;
use App\Repositories\PropertyFeatureRepository\PropertyFeatureRepository;
use App\Repositories\PropertyFeatureRepository\PropertyFeatureRepositoryInterface;
use App\Repositories\PropertyImageRepository\PropertyImageRepository;
use App\Repositories\PropertyImageRepository\PropertyImageRepositoryInterface;
use App\Repositories\PropertyRepository\PropertyRepository;
use App\Repositories\PropertyRepository\PropertyRepositoryInterface;
use App\Repositories\PropertyTypeRepository\PropertyTypeRepository;
use App\Repositories\PropertyTypeRepository\PropertyTypeRepositoryInterface;
use App\Repositories\FavoriteRepository\FavoriteRepository;
use App\Repositories\FavoriteRepository\FavoriteRepositoryInterface;
use App\Repositories\ReportRepository\ReportRepository;
use App\Repositories\ReportRepository\ReportRepositoryInterface;
use App\Repositories\SearchRepository\SearchRepository;
use App\Repositories\SearchRepository\SearchRepositoryInterface;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UsersRepositoryInterface::class, UsersRepository::class);
        $this->app->singleton(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->singleton(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->singleton(RepositoryInterface::class, BaseRepository::class);
        $this->app->bind(PropertyTypeRepositoryInterface::class, PropertyTypeRepository::class);
        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(PropertyImageRepositoryInterface::class, PropertyImageRepository::class);
        $this->app->bind(PropertyFeatureRepositoryInterface::class, PropertyFeatureRepository::class);
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
        $this->app->bind(SearchRepositoryInterface::class, SearchRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
    }

    // USERS MANAGEMENT
    public function index(): void 
    {
        $this->app->singleton(UsersRepositoryInterface::class, UsersRepository::class);
    }
    public function store(): void
    {
        $this->app->singleton(UsersRepositoryInterface::class, UsersRepository::class);
    }
    public function show(): void 
    {
        $this->app->singleton(UsersRepositoryInterface::class, UsersRepository::class);
    }
     public function createUpdate(): void 
    {
        $this->app->singleton(UsersRepositoryInterface::class, UsersRepository::class);
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
