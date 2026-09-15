<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_roles' => \App\Models\Role::count(),
            'total_sliders' => \App\Models\Slider::count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentRoomBookings' => [] // Kept to satisfy Vue prop
        ]);
    }
}
