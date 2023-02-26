<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Policies\RouteActionRbac;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Sanctum::authenticateAccessTokensUsing(
            static function (PersonalAccessToken $accessToken, bool $is_valid) {
                return $accessToken->expired_at ? $is_valid && !$accessToken->expired_at->isPast() : $is_valid;
            }
        );

        $this->registerPolicies();
        Sanctum::ignoreMigrations();

        // Gate::define("visit-action", [RouteActionRbac::class, 'visitAction']);

        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return \sprintf('App\Policies\%sPolicy', \class_basename($modelClass));
        });
        //
    }
}
