<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PackageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Packages/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Packages/Create');
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Packages/Edit', ['packageId' => $id]);
    }
}
