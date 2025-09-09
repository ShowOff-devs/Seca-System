<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        // Share dynamic menu items and user role with the sidebar view
        View::composer('layouts.sidebar', function ($view) {
            $menuItems = [
                [
                    'name' => 'Dashboard',
                    'icon' => 'mdi mdi-speedometer',
                    'route' => route('dashboard'),
                    'roles' => ['admin', 'sub-admin'], // Both roles can access
                ],
                [
                    'name' => 'Assets',
                    'icon' => 'mdi mdi-package-variant',
                    'route' => route('inventory.index'),
                    'roles' => ['admin', 'sub-admin'],
                ],
                [
                    'name' => 'Laboratories',
                    'icon' => 'mdi mdi-office-building',
                    'route' => route('laboratories.index'),
                    'roles' => ['admin', 'sub-admin'],
                ],
                [
                    'name' => 'Tracking',
                    'icon' => 'mdi mdi-radar',
                    'route' => route('tracking.index'),
                    'roles' => ['admin', 'sub-admin'],
                ],
                [
                    'name' => 'Borrowed Items',
                    'icon' => 'mdi mdi-cart-arrow-down',
                    'route' => route('borrowed_items.index'),
                    'roles' => ['admin', 'sub-admin'],
                ],
                [
                    'name' => 'Reports',
                    'icon' => 'mdi mdi-file-chart',
                    'route' => route('reports.index'),
                    'roles' => ['admin', 'sub-admin'],
                ],
                [
                    'name' => 'Active Logs',
                    'icon' => 'mdi mdi-history',
                    'route' => route('logs.index'),
                    'roles' => ['admin'], // Only admin role
                ],
                [
                    'name' => 'User Management',
                    'icon' => 'mdi mdi-account',
                    'route' => route('users.index'),
                    'roles' => ['admin'], // Only admin role
                ],
            ];

            // Fetch the logged-in user's role, default to null if not logged in
            $userRole = Auth::check() ? Auth::user()->role : null;

            // Pass the menu items and role to the view
            $view->with([
                'menuItems' => $menuItems,
                'userRole' => $userRole,
            ]);
        });
    }
}
