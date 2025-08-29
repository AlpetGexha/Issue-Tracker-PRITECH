<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

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
        $this->configurateModels();
        $this->configurateCommands();
        $this->configurateURL();
        // $this->configuratePulse();
    }

    private function configuratePulse(): void
    {
        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin();
        });
    }

    private function configurateModels(): void
    {
        Model::automaticallyEagerLoadRelationships();
        // Model::unguard();
        Model::shouldBeStrict(! app()->isProduction());
    }

    private function configurateCommands(): void
    {
        DB::prohibitDestructiveCommands(
            app()->isProduction()
        );
    }

    private function configurateURL(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
