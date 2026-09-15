<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PackageBookingController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/PackageBookings/Index');
    }

    public function show($id)
    {
        return Inertia::render('Admin/PackageBookings/Show', ['bookingId' => $id]);
    }
}
