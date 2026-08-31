<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\PendingRequestCount;
use Carbon\Carbon;
use Cmixin\BusinessTime;


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

        // Define the 'accounting' gate
        Gate::define('accounting', function ($user) {
        return $user->accounting;
        });


        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            // 1. Get your dynamic count
            $pendingrequests = PendingRequestCount::count();
            $labelcolor = 'success';
           


            if($pendingrequests > 0) {
                $labelcolor = 'danger';
                $data = $pendingrequests;
            } else {
                $labelcolor = false;
                $data = false;
            }

            // 2. Find the existing item by its "text" or "key" value and update it
            $event->menu->addAfter('attendance-dashboard', [
                'text' => 'Leave Requests',      // Name in navbar
                'url'  => 'manager/requests', // Link
                'icon' => 'fas fa-fw fa-bell',    // AdminLTE Icon
                'label' => $data,        // The query count
                'label_color' => $labelcolor,        // Badge color (e.g., danger, success, info)
                'icon_color' => 'cyan',
                'can'  => 'admin', // This locks the item
            ]);
        });


        // Enable and define regular operating hours
        BusinessTime::enable(Carbon::class, [
            'monday'    => ['08:00-12:00', '13:00-17:00'],
            'tuesday'   => ['08:00-12:00', '13:00-17:00'],
            'wednesday' => ['08:00-12:00', '13:00-17:00'],
            'thursday'  => ['08:00-12:00', '13:00-17:00'],
            'friday'    => ['08:00-12:00', '13:00-17:00'], 
            'saturday'  => [], // Closed
            'sunday'    => [], // Closed
        ]);



    }
}
