<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
    {
        $blogs = Blog::with('category')
            ->withCount([
                'comments as total_comments',
                'comments as pending_comments' => fn($q) => $q->where('is_approved', false)->whereNull('parent_id'),
            ])
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($b) => [
                'id'               => $b->id,
                'title'            => $b->title,
                'slug'             => $b->slug,
                'status'           => $b->status,
                'is_featured'      => $b->is_featured,
                'category'         => $b->category?->name,
                'published_at'     => $b->published_at?->format('Y-m-d'),
                'feature_image'    => $b->feature_image,
                'sort_order'       => $b->sort_order,
                'is_active'        => $b->status === 'published',
                'total_comments'   => $b->total_comments,
                'pending_comments' => $b->pending_comments,
            ]);

        return Inertia::render('Admin/Blog/Index', [
            'blogs' => $blogs,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Blog/Create', [
            'categories' => BlogCategory::active()->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);

        foreach (['feature_image', 'og_image', 'author_avatar'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('blog', 'public');
            }
        }

        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = $this->parseTags($data['tags']);
        }

        // Auto-generate keywords if none provided
        if (empty($data['meta_keywords'])) {
            $data['meta_keywords'] = $this->autoKeywords(
                $data['title'] ?? '',
                $data['excerpt'] ?? '',
                is_string($data['content'] ?? null) ? strip_tags($data['content']) : ''
            );
        }

        $blog = Blog::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post created successfully.');
    }

    public function edit(Blog $blog): Response
    {
        return Inertia::render('Admin/Blog/Edit', [
            'blog'       => $blog,
            'categories' => BlogCategory::active()->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title'], $blog->id);

        foreach (['feature_image', 'og_image', 'author_avatar'] as $field) {
            if ($request->hasFile($field)) {
                if ($blog->$field) {
                    Storage::disk('public')->delete($blog->$field);
                }
                $data[$field] = $request->file($field)->store('blog', 'public');
            } else {
                unset($data[$field]);
            }
        }

        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = $this->parseTags($data['tags']);
        }

        // Auto-generate keywords if none provided
        if (empty($data['meta_keywords'])) {
            $data['meta_keywords'] = $this->autoKeywords(
                $data['title'] ?? '',
                $data['excerpt'] ?? '',
                is_string($data['content'] ?? null) ? strip_tags($data['content']) : ''
            );
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        foreach (['feature_image', 'og_image', 'author_avatar'] as $field) {
            if ($blog->$field) {
                Storage::disk('public')->delete($blog->$field);
            }
        }

        $blog->delete();

        return back()->with('success', 'Post deleted.');
    }

    public function toggleStatus(Blog $blog): RedirectResponse
    {
        $newStatus = $blog->status === 'published' ? 'draft' : 'published';
        $blog->update([
            'status'       => $newStatus,
            'published_at' => $newStatus === 'published' ? ($blog->published_at ?? now()) : $blog->published_at,
        ]);

        return back()->with('success', 'Status updated.');
    }

    public function allComments(Request $request): Response
    {
        $filter = $request->get('filter', 'all'); // all | pending | approved

        $query = BlogComment::with('blog:id,title,slug')
            ->whereNull('parent_id')
            ->withCount('replies')
            ->orderByDesc('created_at');

        if ($filter === 'pending') {
            $query->where('is_approved', false);
        } elseif ($filter === 'approved') {
            $query->where('is_approved', true);
        }

        $comments = $query->get();

        return Inertia::render('Admin/Blog/AllComments', [
            'comments'      => $comments,
            'filter'        => $filter,
            'pendingCount'  => BlogComment::whereNull('parent_id')->where('is_approved', false)->count(),
            'approvedCount' => BlogComment::whereNull('parent_id')->where('is_approved', true)->count(),
            'totalCount'    => BlogComment::whereNull('parent_id')->count(),
        ]);
    }

    public function comments(Blog $blog): Response
    {
        $comments = BlogComment::where('blog_id', $blog->id)
            ->with('replies')
            ->whereNull('parent_id')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Admin/Blog/Comments', [
            'blog'     => $blog->only('id', 'title', 'slug'),
            'comments' => $comments,
        ]);
    }

    public function approveComment(BlogComment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => !$comment->is_approved]);

        return back()->with('success', 'Comment status updated.');
    }

    public function deleteComment(BlogComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'            => 'required|string',
            'excerpt'          => 'nullable|string',
            'content'          => 'nullable|string',
            'category_id'      => 'nullable|exists:blog_categories,id',
            'tags'             => 'nullable|string',
            'feature_image'    => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'og_image'         => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'author_name'      => 'nullable|string',
            'author_bio'       => 'nullable|string',
            'author_avatar'    => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'status'           => 'required|in:draft,published',
            'published_at'     => 'nullable|date',
            'is_featured'      => 'boolean',
            'sort_order'       => 'integer|min:0',
            'meta_title'       => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
        ]);
    }

    private function autoKeywords(string ...$texts): string
    {
        static $stop = [
            'a','an','the','and','or','but','in','on','at','to','for','of','with',
            'by','from','as','is','was','are','were','be','been','being','have',
            'has','had','do','does','did','will','would','could','should','may',
            'might','can','this','that','these','those','it','its','we','our',
            'you','your','hotel','beach','way','cox','bazar',
        ];
        $text  = implode(' ', $texts);
        $words = preg_split('/\W+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
        $words = array_filter($words, fn($w) => mb_strlen($w) > 3 && !in_array($w, $stop, true));
        return implode(', ', array_slice(array_unique(array_values($words)), 0, 12));
    }

    private function parseTags(string $tags): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $tags))));
    }

    private function uniqueSlug(string $title, int $excludeId = 0): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (Blog::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
