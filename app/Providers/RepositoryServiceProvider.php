<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{
    CategoryRepositoryInterface,
    ProductRepositoryInterface
};
use App\Repositories\Core\Eloquent\EloquentCategoryRepository;
use App\Repositories\Core\Eloquent\EloquentProductRepository;
use App\Repositories\Core\QueryBuilder\QueryBuilderCategoryRepository;
use App\Repositories\Core\QueryBuilder\QueryBuilderProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            QueryBuilderProductRepository::class,
            // EloquentProductRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            QueryBuilderCategoryRepository::class
            // EloquentCategoryRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
