<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class TeamController extends Controller
{
    public function index(): Response
    {
        $teams = Team::orderBy('order', 'asc')->latest()->get();

        return Inertia::render('Admin/Teams/Index', [
            'teams' => $teams,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Teams/Form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'facebook'    => 'nullable|string|max:255|url',
            'twitter'     => 'nullable|string|max:255|url',
            'linkedin'    => 'nullable|string|max:255|url',
            'instagram'   => 'nullable|string|max:255|url',
            'status'      => 'boolean',
            'order'       => 'nullable|integer',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('team', 'public');
        }

        Team::create($data);

        return redirect()->route('admin.teams.index')->with('success', 'Team member added successfully.');
    }

    public function edit(Team $team): Response
    {
        return Inertia::render('Admin/Teams/Form', [
            'team' => $team,
        ]);
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'facebook'    => 'nullable|string|max:255|url',
            'twitter'     => 'nullable|string|max:255|url',
            'linkedin'    => 'nullable|string|max:255|url',
            'instagram'   => 'nullable|string|max:255|url',
            'status'      => 'boolean',
            'order'       => 'nullable|integer',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);

        if ($request->hasFile('image')) {
            if ($team->image) {
                Storage::disk('public')->delete($team->image);
            }
            $data['image'] = $request->file('image')->store('team', 'public');
        } else {
            unset($data['image']);
        }

        $team->update($data);

        return redirect()->route('admin.teams.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(Team $team): RedirectResponse
    {
        if ($team->image) {
            Storage::disk('public')->delete($team->image);
        }

        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Team member deleted successfully.');
    }

    public function toggleStatus(Team $team)
    {
        $team->update(['status' => !$team->status]);
        return back()->with('success', 'Status updated successfully.');
    }
}
