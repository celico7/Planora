<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\Sprint;
use App\Observers\TaskObserver;
use App\Policies\SprintPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Task::observe(TaskObserver::class);
        Gate::policy(Sprint::class, SprintPolicy::class);
    }
}
