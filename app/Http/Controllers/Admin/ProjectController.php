<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    public function index(): Response
    {
        $projects = Project::latest()->get();

        return Inertia::render('Admin/Projects/Index', [
            'projects' => $projects,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Projects/Form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'status'      => 'required|in:running,completed,upcoming',
            'client'      => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|mimes:jpeg,jpg,png,webp',
            // For simplicity, we skip multi-image upload here, or it can be implemented later
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project): Response
    {
        return Inertia::render('Admin/Projects/Form', [
            'project' => $project,
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'status'      => 'required|in:running,completed,upcoming',
            'client'      => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
