@extends('layouts.frontend')

@section('title', ($blog->meta_title ?: $blog->title) . ' – Hotel Beach Way Blog')
@section('meta_description', $blog->meta_description ?: ($blog->excerpt ?: 'Read ' . $blog->title . ' on the Hotel Beach Way blog.'))

@if($blog->meta_keywords)
    @section('meta_keywords', $blog->meta_keywords)
@endif

@section('og_title', ($blog->meta_title ?: $blog->title) . ' – Hotel Beach Way Blog')
@section('og_description', $blog->meta_description ?: ($blog->excerpt ?: 'Read ' . $blog->title . ' on the Hotel Beach Way blog.'))
@if($blog->og_image)
@section('og_image', asset('storage/' . $blog->og_image))
@elseif($blog->feature_image)
@section('og_image', asset('storage/' . $blog->feature_image))
@endif

@section('content')

  <!-- ===========
       PAGE BREADCRUMB HERO
  =========== -->
  <section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
      <h1 class="page-hero-title">Blog Details</h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <a href="{{ route('blog.front') }}">Blog</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="bc-current">{{ Str::limit($blog->title, 40) }}</span>
      </nav>
    </div>
    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
      </svg>
    </div>
  </section>

  <!-- ===========
       BLOG DETAILS SECTION
  =========== -->
  <section class="bds-section">
    <div class="bds-inner">

      <!-- ── Main Content ─────────────────────────── -->
      <div class="bds-content" data-reveal="up">

        <!-- Hero Image -->
        @if($blog->feature_image)
        <div class="bds-hero-img">
          <img src="{{ asset('storage/' . $blog->feature_image) }}"
               alt="{{ $blog->title }}"
               loading="eager">
        </div>
        @endif

        <!-- Post Meta -->
        <div class="bds-meta">
          <span class="bds-meta-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            By: <strong>{{ $blog->author_name ?: 'Admin' }}</strong>
          </span>
          <span class="bds-meta-sep">|</span>
          <span class="bds-meta-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ $blog->published_at?->format('F d, Y') }}
          </span>
          @if($blog->category)
          <span class="bds-meta-sep">|</span>
          <span class="bds-meta-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
            <a href="{{ route('blog.front', ['category' => $blog->category->slug]) }}">{{ $blog->category->name }}</a>
          </span>
          @endif
          <span class="bds-meta-sep">|</span>
          <span class="bds-meta-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            {{ $blog->approvedComments->count() }} {{ Str::plural('Comment', $blog->approvedComments->count()) }}
          </span>
        </div>

        <!-- Post Title -->
        <h1 class="bds-title">{{ $blog->title }}</h1>

        <!-- Body Content -->
        <div class="bds-body-content">
          {!! $blog->content !!}
        </div>

        <!-- Tags & Share Row -->
        @if(!empty($blog->tags) || true)
        <div class="bds-footer-row">
          <div class="bds-tags-wrap">
            <span class="bds-tags-label">Related Tags</span>
            <div class="bds-tags">
              @forelse($blog->tags ?? [] as $tag)
                <a href="{{ route('blog.front', ['tag' => $tag]) }}" class="bds-tag">{{ $tag }}</a>
              @empty
                <span class="bds-tag" style="opacity:0.5;cursor:default;">No tags</span>
              @endforelse
            </div>
          </div>
          <div class="bds-share-wrap">
            <span class="bds-share-label">Social Share</span>
            <div class="bds-share-icons">
              @php $shareUrl = urlencode(request()->fullUrl()); $shareTitle = urlencode($blog->title); @endphp
              <a href="https://www.instagram.com/" target="_blank" rel="noopener" aria-label="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
              </a>
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
              </a>
              <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" aria-label="Twitter/X">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </a>
              <a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $shareTitle }}" target="_blank" rel="noopener" aria-label="Pinterest">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>
              </a>
            </div>
          </div>
        </div>
        @endif

        <!-- Prev / Next Navigation -->
        <div class="bds-post-nav">
          @if($prev)
          <a href="{{ route('blog.detail', $prev->slug) }}" class="bds-post-nav-item bds-post-prev">
            <span class="bds-nav-label">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
              Previous Post
            </span>
            <span class="bds-nav-title">{{ Str::limit($prev->title, 50) }}</span>
          </a>
          @else
          <div class="bds-post-nav-item bds-post-prev bds-post-nav--empty"></div>
          @endif

          @if($next)
          <a href="{{ route('blog.detail', $next->slug) }}" class="bds-post-nav-item bds-post-next">
            <span class="bds-nav-label">
              Next Post
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
            <span class="bds-nav-title">{{ Str::limit($next->title, 50) }}</span>
          </a>
          @else
          <div class="bds-post-nav-item bds-post-next bds-post-nav--empty"></div>
          @endif
        </div>

        <!-- Author Box -->
        @if($blog->author_name)
        <div class="bds-author-box">
          <div class="bds-author-img">
            @if($blog->author_avatar)
              <img src="{{ asset('storage/' . $blog->author_avatar) }}" alt="{{ $blog->author_name }}">
            @else
              <img src="{{ asset('assets/images/author-placeholder.jpg') }}" alt="{{ $blog->author_name }}">
            @endif
          </div>
          <div class="bds-author-info">
            <h3 class="bds-author-name">{{ $blog->author_name }}</h3>
            <div class="bds-author-underline"></div>
            @if($blog->author_bio)
            <p class="bds-author-bio">{{ $blog->author_bio }}</p>
            @endif
            <div class="bds-author-social">
              <a href="#" aria-label="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
              </a>
              <a href="#" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
              </a>
              <a href="#" aria-label="Twitter/X">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </a>
            </div>
          </div>
        </div>
        @endif

        <!-- Comments -->
        <div class="bds-comments">
          <h2 class="bds-comments-title">
            <span>({{ $blog->approvedComments->count() }})</span> {{ Str::plural('Comment', $blog->approvedComments->count()) }}
          </h2>

          @forelse($blog->approvedComments as $comment)
          <div class="bds-comment">
            <div class="bds-comment-avatar">
              <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=0D3D2E&color=fff&size=60"
                   alt="{{ $comment->name }}"
                   loading="lazy">
            </div>
            <div class="bds-comment-body">
              <div class="bds-comment-header">
                <strong class="bds-comment-author">{{ $comment->name }}</strong>
                <span class="bds-comment-sep">–</span>
                <span class="bds-comment-time">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  {{ $comment->created_at->diffForHumans() }}
                </span>
              </div>
              <p class="bds-comment-text">{{ $comment->message }}</p>
              <button class="bds-reply-btn" data-reply-to="{{ $comment->id }}" data-reply-name="{{ $comment->name }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 00-4-4H4"/></svg>
                Reply
              </button>
            </div>
          </div>

          {{-- Nested replies --}}
          @foreach($comment->replies as $reply)
          <div class="bds-comment bds-comment--reply">
            <div class="bds-comment-avatar">
              <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->name) }}&background=0F288D&color=fff&size=60"
                   alt="{{ $reply->name }}"
                   loading="lazy">
            </div>
            <div class="bds-comment-body">
              <div class="bds-comment-header">
                <strong class="bds-comment-author">{{ $reply->name }}</strong>
                <span class="bds-comment-sep">–</span>
                <span class="bds-comment-time">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  {{ $reply->created_at->diffForHumans() }}
                </span>
              </div>
              <p class="bds-comment-text">{{ $reply->message }}</p>
            </div>
          </div>
          @endforeach

          @empty
          <p class="bds-no-comments">Be the first to leave a comment!</p>
          @endforelse

        </div><!-- /bds-comments -->

        <!-- Leave a Comment Form -->
        <div class="bds-comment-form">
          <h2 class="bds-form-title">Leave a Comment</h2>
          <div class="bds-form-underline"></div>

          @if(session('comment_success'))
            <div class="bds-form-alert bds-form-alert--success">
              {{ session('comment_success') }}
            </div>
          @endif

          <form action="{{ route('blog.comment') }}" method="POST" id="commentForm">
            @csrf
            <input type="hidden" name="blog_id" value="{{ $blog->id }}">
            <input type="hidden" name="parent_id" id="parentIdInput" value="">

            {{-- Reply indicator --}}
            <div id="replyIndicator" class="bds-reply-indicator" style="display:none;">
              Replying to <strong id="replyingToName"></strong>
              <button type="button" id="cancelReply" class="bds-cancel-reply">&times; Cancel</button>
            </div>

            <div class="bds-form-row">
              <div class="bds-form-group">
                <label for="commentName">Name <span>*</span></label>
                <input type="text" id="commentName" name="name" placeholder="Your full name"
                       value="{{ old('name') }}" required />
                @error('name')<span class="bds-field-error">{{ $message }}</span>@enderror
              </div>
              <div class="bds-form-group">
                <label for="commentEmail">Email <span>*</span></label>
                <input type="email" id="commentEmail" name="email" placeholder="Your email address"
                       value="{{ old('email') }}" required />
                @error('email')<span class="bds-field-error">{{ $message }}</span>@enderror
              </div>
            </div>

            <div class="bds-form-group">
              <label for="commentMessage">Message <span>*</span></label>
              <textarea id="commentMessage" name="message" rows="5"
                        placeholder="Write your comment here..." required>{{ old('message') }}</textarea>
              @error('message')<span class="bds-field-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="bds-form-submit">Post Comment</button>
          </form>
        </div>

      </div><!-- /bds-content -->

      <!-- ── Sidebar ────────────────────────────────── -->
      <aside class="bds-sidebar">

        <!-- Search Widget -->
        <div class="bds-widget">
          <h3 class="bds-widget-title">Search Here</h3>
          <div class="bds-widget-underline"></div>
          <form action="{{ route('blog.front') }}" method="GET">
            <div class="bds-search-wrap">
              <input type="search" name="search" placeholder="Search Here...."
                     value="{{ request('search') }}">
              <button type="submit" aria-label="Search">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              </button>
            </div>
          </form>
        </div>

        <!-- Categories Widget -->
        @if($categories->isNotEmpty())
        <div class="bds-widget">
          <h3 class="bds-widget-title">Categories List</h3>
          <div class="bds-widget-underline"></div>
          <ul class="bds-cat-list">
            @foreach($categories as $cat)
            <li class="bds-cat-item {{ $blog->category_id === $cat->id ? 'bds-cat-item--active' : '' }}">
              <a href="{{ route('blog.front', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
              <span class="bds-cat-count">({{ str_pad($cat->blogs_count, 2, '0', STR_PAD_LEFT) }})</span>
            </li>
            @endforeach
          </ul>
        </div>
        @endif

        <!-- Recent Posts Widget -->
        @if($recentPosts->isNotEmpty())
        <div class="bds-widget">
          <h3 class="bds-widget-title">Recent Post</h3>
          <div class="bds-widget-underline"></div>
          <div class="bds-recent-list">
            @foreach($recentPosts as $recent)
            <a href="{{ route('blog.detail', $recent->slug) }}" class="bds-recent-item">
              <div class="bds-recent-thumb">
                @if($recent->feature_image)
                  <img src="{{ asset('storage/' . $recent->feature_image) }}"
                       alt="{{ $recent->title }}"
                       loading="lazy">
                @else
                  <img src="{{ asset('assets/images/blog-placeholder.jpg') }}"
                       alt="{{ $recent->title }}"
                       loading="lazy">
                @endif
              </div>
              <div class="bds-recent-info">
                <h4 class="bds-recent-title">{{ Str::limit($recent->title, 45) }}</h4>
                <span class="bds-recent-date">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  {{ $recent->published_at?->format('M d, Y') }}
                </span>
              </div>
            </a>
            @endforeach
          </div>
        </div>
        @endif

        <!-- Popular Tags Widget -->
        @if(!empty($popularTags))
        <div class="bds-widget">
          <h3 class="bds-widget-title">Popular Tags</h3>
          <div class="bds-widget-underline"></div>
          <div class="bds-pop-tags">
            @foreach($popularTags as $tag)
              <a href="{{ route('blog.front', ['tag' => $tag]) }}" class="bds-pop-tag">{{ $tag }}</a>
            @endforeach
          </div>
        </div>
        @endif

      </aside><!-- /bds-sidebar -->

    </div><!-- /bds-inner -->
  </section><!-- /bds-section -->

@endsection

@push('scripts')
<script>
  // Reply button: populate hidden parent_id and show indicator
  document.querySelectorAll('.bds-reply-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id   = btn.dataset.replyTo;
      const name = btn.dataset.replyName;
      document.getElementById('parentIdInput').value = id;
      document.getElementById('replyingToName').textContent = name;
      document.getElementById('replyIndicator').style.display = 'flex';
      document.getElementById('commentForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  document.getElementById('cancelReply')?.addEventListener('click', () => {
    document.getElementById('parentIdInput').value = '';
    document.getElementById('replyIndicator').style.display = 'none';
  });
</script>
@endpush
