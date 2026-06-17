<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\PendingRequestCount;


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
    public function boot(\Illuminate\Contracts\Events\Dispatcher $events): void
    {
        // Define the 'admin' gate
        Gate::define('admin', function ($user) {
        return $user->is_admin;
        });


        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
        // 1. Get your dynamic count
        $pendingrequests = PendingRequestCount::count();

        // 2. Find the existing item by its "text" or "key" value and update it
        $event->menu->add([
                'text' => 'Leave Requests',      // Name in navbar
                'url'  => 'manager/requests', // Link
                'icon' => 'fas fa-fw fa-bell',    // AdminLTE Icon
                'label' => $pendingrequests,        // The query count
                'label_color' => 'warning',        // Badge color (e.g., danger, success, info)
                'icon_color' => 'cyan',
                'can'  => 'admin', // This locks the item
            ]);
    });


    }
}
