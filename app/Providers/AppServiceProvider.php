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
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.tailwind');
        \Illuminate\Pagination\Paginator::defaultSimpleView('vendor.pagination.tailwind');

        \Illuminate\Support\Facades\View::composer('layouts.partials.sidebar', function ($view) {
            try {
                $unevaluatedCount = \App\Models\MatchGame::where('status', 'Final')->count();
                $view->with('unevaluatedCount', $unevaluatedCount);

                // Pertandingan yang input rally-nya belum selesai (Draft tanpa pemenang 2 set yang sah)
                $draftMatches = \App\Models\MatchGame::where('status', 'Draft')->with('rallies')->get();
                $noRallyCount = $draftMatches->filter(function($match) {
                    return !$match->isRallyInputComplete();
                })->count();
                $view->with('noRallyCount', $noRallyCount);
            } catch (\Exception $e) {
                $view->with('unevaluatedCount', 0);
                $view->with('noRallyCount', 0);
            }
        });
    }
}
