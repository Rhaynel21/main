<?php
// app/Providers/BadgeServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\BadgeHelper;

class BadgeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register custom Blade directives
        
        // Inline badges next to username
        Blade::directive('inlineBadges', function ($expression) {
            return "<?php echo App\\Helpers\\BadgeHelper::renderInlineBadges($expression); ?>";
        });
        
        // Full badges section with counters
        Blade::directive('badgesSection', function ($expression) {
            return "<?php echo App\\Helpers\\BadgeHelper::renderBadgesSection($expression); ?>";
        });
    }
}