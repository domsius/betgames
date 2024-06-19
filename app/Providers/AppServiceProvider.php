<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TaskServiceInterface;
use App\Services\TaskService;
use App\Repositories\TaskRepositoryInterface;
use App\Repositories\TaskRepository;
use App\Services\CategoryServiceInterface;
use App\Services\CategoryService;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Services\ProfileServiceInterface;
use App\Services\ProfileService;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        Passport::ignoreRoutes();
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
    }

    public function boot()
    {
        //
    }
}