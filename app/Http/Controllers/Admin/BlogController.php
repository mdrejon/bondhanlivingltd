<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        $blogs = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Blogs/Index', [
            'blogs' => $blogs,
            'filters' => $request->only('search')
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Blogs/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['author'] = $validated['author'] ?? 'Bondhon';
        $validated['category'] = $validated['category'] ?? 'INDUSTRY';
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Admin/Blogs/Edit', [
            'blog' => $blog
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'boolean',
        ]);

        if ($request->title !== $blog->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        }

        if ($request->hasFile('image')) {
            if ($blog->image && !str_starts_with($blog->image, 'assets/')) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image && !str_starts_with($blog->image, 'assets/')) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }

    public function toggleStatus(Request $request, Blog $blog)
    {
        $blog->update([
            'status' => !$blog->status
        ]);
        return back()->with('success', 'Blog status updated successfully.');
    }
}
