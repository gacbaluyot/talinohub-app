<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Repository Bindings
        $this->app->bind(
            \App\Repositories\Contracts\CourseRepositoryInterface::class,
            \App\Repositories\CourseRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\LessonRepositoryInterface::class,
            \App\Repositories\LessonRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\EnrollmentRepositoryInterface::class,
            \App\Repositories\EnrollmentRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\RatingRepositoryInterface::class,
            \App\Repositories\RatingRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\CommentRepositoryInterface::class,
            \App\Repositories\CommentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
