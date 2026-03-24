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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Carbon\Carbon::setLocale('id');

        \Illuminate\Support\Facades\Blade::directive('formatDate', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::format($expression); ?>";
        });

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $appSetting = \App\Models\Setting::first();
                \Illuminate\Support\Facades\View::share('appSetting', $appSetting);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('appSetting', null);
        }
    }
}
