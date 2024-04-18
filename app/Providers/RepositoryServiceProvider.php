<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\User\UserRepositoryInterface;

use App\Repositories\Core\Eloquent\{
    EloquentCategoryRepository,
    EloquentProductRepository,
    EloquentUserRepository,
};

use App\Repositories\Core\QueryBuilder\{
    QueryBuilderCategoryRepository,
    QueryBuilderProductRepository,
    QueryBuilderUserRepository,
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            // QueryBuilderProductRepository::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            // QueryBuilderCategoryRepository::class,
            EloquentCategoryRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            // QueryBuilderUserRepository::class,
            EloquentUserRepository::class
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
