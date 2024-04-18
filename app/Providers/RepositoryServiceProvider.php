<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;

use App\Repositories\Core\Eloquent\{
    EloquentUserRepository,
    EloquentCategoryRepository,
    EloquentProductRepository,
};

use App\Repositories\Core\QueryBuilder\{
    QueryBuilderUserRepository,
    QueryBuilderCategoryRepository,
    QueryBuilderProductRepository,
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            // QueryBuilderUserRepository::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            // QueryBuilderCategoryRepository::class,
            EloquentCategoryRepository::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            QueryBuilderProductRepository::class,
            // EloquentProductRepository::class
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
