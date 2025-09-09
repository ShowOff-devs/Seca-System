<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAssets = \App\Models\Asset::count();
        $assetsPerLab = \App\Models\Laboratory::withCount('assets')->get();
        $borrowedItems = \App\Models\BorrowedItem::with(['asset', 'laboratory'])->latest()->take(10)->get();
        $activeAssets = \App\Models\Asset::where('status', 'active')->count();
        $inactiveAssets = \App\Models\Asset::where('status', 'inactive')->count();

        $menuItems = [
            [
                'name' => 'Dashboard',
                'icon' => 'mdi mdi-speedometer',
                'route' => route('dashboard'),
            ],
            [
                'name' => 'Assets',
                'icon' => 'mdi mdi-package-variant',
                'route' => route('inventory.index'),
            ],
            [
                'name' => 'Laboratories',
                'icon' => 'mdi mdi-office-building',
                'route' => route('laboratories.index'),
            ],
            [
                'name' => 'Tracking',
                'icon' => 'mdi mdi-radar',
                'route' => route('tracking.index'),
            ],
            [
                'name' => 'Borrowed Items',
                'icon' => 'mdi mdi-cart-arrow-down',
                'route' => route('borrowed_items.index'),
            ],
            [
                'name' => 'Active Logs',
                'icon' => 'mdi mdi-history',
                'route' => route('logs.index'),
            ],
            [
                'name' => 'User Management',
                'icon' => 'mdi mdi-account',
                'route' => route('users.index'),
            ],
        ];

        return view('dashboard.index', compact(
            'totalAssets', 'assetsPerLab', 'borrowedItems', 'activeAssets', 'inactiveAssets', 'menuItems'
        ));
    }
}