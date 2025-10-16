<?php

namespace App\Providers;

// Import your custom model and policy
use App\Models\Countries\Pages;
use App\Models\Settings\SiteSetting;
use App\Policies\PagePolicy;
// IMPORTANT: Change the base class it extends
use App\Policies\SiteSettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Add your mapping here
        Pages::class => PagePolicy::class,
        SiteSetting::class => SiteSettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // This method can remain empty for now
    }
}
