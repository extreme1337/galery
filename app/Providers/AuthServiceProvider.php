<?php

namespace App\Providers;

use App\Enums\Role;
use App\Models\User;
use App\Models\Image;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        // Gate::define('update-image', [PolicyForImage::class, 'update']);

        // Gate::define('delete-image', [PolicyForImage::class, 'delete']);

        Gate::before(function ($user, $ability) {
            if ($user->role === Role::Admin) {
                return true;
            }
        });
    }
}
