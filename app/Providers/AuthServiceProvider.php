<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Trip;
use App\Models\User;
use App\Models\UserFavorite;
use App\Policies\TripPolicy;
use App\Policies\UserFavoritePolicy;
use App\Policies\UserPolicy;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('update-profile', function (User $user) {
            return true;
        });
    }
}
