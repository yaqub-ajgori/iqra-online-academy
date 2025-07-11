<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogReaction;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class BlogController extends Controller
{
    /**
     * Display blog index page.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'category', 'tags'])
            ->published()
            ->orderBy('is_sticky', 'desc')
            ->orderBy('published_at', 'desc');

        // Apply filters
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            // Use basic search for now - can be upgraded to full-text when indexes are added
            $query->where(function ($q) use ($searchTerm) {
                $escapedTerm = addslashes($searchTerm);
                $q->where('title', 'like', "%{$escapedTerm}%")
                  ->orWhere('content', 'like', "%{$escapedTerm}%")
                  ->orWhere('excerpt', 'like', "%{$escapedTerm}%");
            });
        }

        $posts = $query->paginate(12);
        
        // Add featured image URLs and format data
        $posts->getCollection()->transform(function ($post) {
            return $this->formatPostForFrontend($post);
        });

        // Get featured posts
        $featuredPosts = BlogPost::with(['author', 'category', 'tags'])
            ->published()
            ->featured()
            ->limit(4)
            ->get()
            ->map(function ($post) {
                return $this->formatPostForFrontend($post);
            });

        // Get cached sidebar data
        $categories = Cache::remember('blog_categories_sidebar', 3600, function () {
            return BlogCategory::active()
                ->withCount(['posts' => function ($query) {
                    $query->published();
                }])
                ->orderBy('sort_order')
                ->get();
        });

        $popularTags = Cache::remember('blog_popular_tags', 3600, function () {
            return BlogTag::withCount(['posts' => function ($query) {
                    $query->published();
                }])
                ->having('posts_count', '>', 0)
                ->orderBy('posts_count', 'desc')
                ->limit(15)
                ->get();
        });

        $recentPosts = Cache::remember('blog_recent_posts', 1800, function () {
            return BlogPost::with(['author'])
                ->published()
                ->latest('published_at')
                ->limit(5)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'slug' => $post->slug,
                        'title' => strip_tags($post->title),
                        'featured_image_url' => $post->featured_image_url,
                        'published_at' => $post->published_at->toISOString(),
                    ];
                });
        });

        // Support for infinite scroll with merge
        if ($request->has('merge') && $request->boolean('merge')) {
            return Inertia::merge([
                'posts' => $posts->items(),
                'hasMorePosts' => $posts->hasMorePages(),
            ]);
        }

        return Inertia::render('Frontend/Blog/BlogIndex', [
            'posts' => $posts->items(),
            'featuredPosts' => $featuredPosts,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'recentPosts' => $recentPosts,
            'hasMorePosts' => $posts->hasMorePages(),
            'activeCategory' => $request->category,
            'filters' => [
                'search' => $request->search,
                'category' => $request->category,
                'tag' => $request->tag,
            ]
        ]);
    }

    /**
     * Display individual blog post.
     */
    public function show(Request $request, string $slug)
    {
        $post = BlogPost::with(['author', 'category', 'tags', 'comments.user', 'comments.replies.user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count
        $post->incrementViewCount();

        // Get user reactions for both authenticated and guest users
        $userReactions = ['like' => false, 'helpful' => false];
        $sessionId = $request->session()->getId();
        
        if (Auth::check()) {
            // For authenticated users
            $reactions = BlogReaction::where('blog_post_id', $post->id)
                ->where('user_id', Auth::id())
                ->pluck('type')
                ->toArray();
        } else {
            // For guest users, check by session ID
            $reactions = BlogReaction::where('blog_post_id', $post->id)
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->pluck('type')
                ->toArray();
        }
        
        $userReactions = [
            'like' => in_array('like', $reactions),
            'helpful' => in_array('helpful', $reactions)
        ];

        // Format post data
        $formattedPost = $this->formatPostForFrontend($post);
        $formattedPost['meta_title'] = $post->meta_title;
        $formattedPost['meta_description'] = $post->meta_description;
        $formattedPost['meta_keywords'] = $post->meta_keywords;
        $formattedPost['likes_count'] = $post->reactions()->where('type', 'like')->count();
        $formattedPost['helpful_count'] = $post->reactions()->where('type', 'helpful')->count();

        // Get approved comments with replies
        $comments = $post->comments()
            ->approved()
            ->whereNull('parent_id')
            ->with(['replies' => function ($query) {
                $query->approved()->with('user');
            }, 'user'])
            ->oldest()
            ->get()
            ->map(function ($comment) {
                return $this->formatCommentForFrontend($comment);
            });

        // Get related posts
        $relatedPosts = $post->relatedPosts(3)
            ->map(function ($relatedPost) {
                return [
                    'id' => $relatedPost->id,
                    'slug' => $relatedPost->slug,
                    'title' => $relatedPost->title,
                    'published_at' => $relatedPost->published_at->toISOString(),
                ];
            });

        return Inertia::render('Frontend/Blog/BlogPost', [
            'post' => $formattedPost,
            'comments' => $comments,
            'relatedPosts' => $relatedPosts,
            'userReactions' => $userReactions,
        ]);
    }

    /**
     * Display posts by category.
     */
    public function category(Request $request, string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->published()
            ->where('category_id', $category->id)
            ->orderBy('is_sticky', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $posts->getCollection()->transform(function ($post) {
            return $this->formatPostForFrontend($post);
        });

        return Inertia::render('Frontend/Blog/BlogCategory', [
            'category' => $category,
            'posts' => $posts->items(),
            'hasMorePosts' => $posts->hasMorePages(),
        ]);
    }

    /**
     * Display posts by tag.
     */
    public function tag(Request $request, string $slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->published()
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('blog_tag_id', $tag->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $posts->getCollection()->transform(function ($post) {
            return $this->formatPostForFrontend($post);
        });

        return Inertia::render('Frontend/Blog/BlogTag', [
            'tag' => $tag,
            'posts' => $posts->items(),
            'hasMorePosts' => $posts->hasMorePages(),
        ]);
    }

    /**
     * Search blog posts.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $posts->getCollection()->transform(function ($post) {
            return $this->formatPostForFrontend($post);
        });

        return Inertia::render('Frontend/Blog/BlogSearch', [
            'query' => $query,
            'posts' => $posts->items(),
            'hasMorePosts' => $posts->hasMorePages(),
            'totalResults' => $posts->total(),
        ]);
    }

    /**
     * Store a new comment.
     */
    public function storeComment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'মন্তব্য করতে হলে লগইন করুন।');
        }

        $validated = $request->validate([
            'blog_post_id' => 'required|exists:blog_posts,id',
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ]);

        // Basic spam detection
        $isSpam = $this->detectSpam($validated['content']);

        $comment = new BlogComment();
        $comment->blog_post_id = $validated['blog_post_id'];
        $comment->content = strip_tags($validated['content']);
        $comment->parent_id = $validated['parent_id'] ?? null;
        $comment->ip_address = $request->ip();
        $comment->user_agent = $request->userAgent();
        $comment->user_id = Auth::id();
        $comment->status = $isSpam ? 'spam' : 'pending';

        $comment->save();

        return back()->with('success', 'আপনার মন্তব্য সফলভাবে জমা দেওয়া হয়েছে। মডারেশনের পর প্রকাশিত হবে।');
    }

    /**
     * React to a blog post.
     */
    public function reactToPost(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,helpful',
        ]);

        $sessionId = $request->session()->getId();
        $ipAddress = $request->ip();

        // Build query conditions for finding existing reaction
        $queryConditions = [
            'blog_post_id' => $post->id,
            'type' => $validated['type'],
        ];

        if (Auth::check()) {
            // For authenticated users
            $queryConditions['user_id'] = Auth::id();
            $reactionData = [
                'blog_post_id' => $post->id,
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'ip_address' => $ipAddress,
            ];
        } else {
            // For guest users
            $queryConditions['session_id'] = $sessionId;
            $queryConditions['user_id'] = null;
            $reactionData = [
                'blog_post_id' => $post->id,
                'user_id' => null,
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
                'type' => $validated['type'],
            ];
        }

        $reaction = BlogReaction::where($queryConditions)->first();

        if ($reaction) {
            $reaction->delete();
            $action = 'removed';
        } else {
            BlogReaction::create($reactionData);
            $action = 'added';
        }

        $responseData = [
            'action' => $action,
            'type' => $validated['type'],
            'count' => $post->reactions()->where('type', $validated['type'])->count(),
            'user_reacted' => $action === 'added',
        ];

        if ($request->expectsJson()) {
            return response()->json($responseData);
        }

        return back()->with('reaction_response', $responseData);
    }

    /**
     * Display posts by author.
     */
    public function author(Request $request, string $id)
    {
        $author = User::findOrFail($id);

        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->published()
            ->where('author_id', $author->id)
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $posts->getCollection()->transform(function ($post) {
            return $this->formatPostForFrontend($post);
        });

        return Inertia::render('Frontend/Blog/BlogAuthor', [
            'author' => [
                'id' => $author->id,
                'name' => $author->name,
                'email' => $author->email,
                'avatar' => null,
            ],
            'posts' => $posts->items(),
            'hasMorePosts' => $posts->hasMorePages(),
        ]);
    }


    /**
     * Generate RSS feed.
     */
    public function feed()
    {
        $posts = BlogPost::with(['author', 'category'])
            ->published()
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()->view('blog.feed', compact('posts'))
            ->header('Content-Type', 'application/rss+xml');
    }

    /**
     * Format post data for frontend.
     */
    private function formatPostForFrontend($post)
    {
        return [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => strip_tags($post->title),
            'excerpt' => $post->excerpt ? strip_tags($post->excerpt) : null,
            'content' => $this->sanitizeHtml($post->content),
            'featured_image' => $post->featured_image,
            'featured_image_url' => $post->featured_image_url,
            'published_at' => $post->published_at->toISOString(),
            'reading_time' => $post->reading_time,
            'views_count' => $post->views_count,
            'is_featured' => $post->is_featured,
            'is_sticky' => $post->is_sticky,
            'category' => $post->category ? [
                'id' => $post->category->id,
                'name' => strip_tags($post->category->name),
                'slug' => $post->category->slug,
                'color' => $post->category->color,
            ] : null,
            'author' => $post->author ? [
                'id' => $post->author->id,
                'name' => strip_tags($post->author->name),
                'avatar' => null, // Add avatar logic if needed
            ] : null,
            'tags' => $post->tags ? $post->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => strip_tags($tag->name),
                    'slug' => $tag->slug,
                ];
            }) : [],
        ];
    }

    /**
     * Sanitize HTML content for safe display.
     */
    private function sanitizeHtml($content)
    {
        // Allow safe HTML tags only
        $allowedTags = [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li', 'blockquote', 'a', 'img', 'div', 'span',
            'table', 'thead', 'tbody', 'tr', 'td', 'th'
        ];
        
        $allowedAttributes = [
            'a' => ['href', 'title', 'target'],
            'img' => ['src', 'alt', 'title', 'width', 'height'],
            'div' => ['class'],
            'span' => ['class'],
            'h2' => ['id'],
            'h3' => ['id'],
            'h4' => ['id'],
        ];
        
        // Basic HTML purification
        $content = strip_tags($content, '<' . implode('><', $allowedTags) . '>');
        
        // Remove dangerous attributes and JavaScript
        $content = preg_replace('/on\w+="[^"]*"/i', '', $content);
        $content = preg_replace('/javascript:/i', '', $content);
        $content = preg_replace('/data:/i', '', $content);
        
        return $content;
    }

    /**
     * Basic spam detection for comments.
     */
    private function detectSpam($content)
    {
        $spamKeywords = [
            'casino', 'lottery', 'winner', 'click here', 'free money',
            'viagra', 'cialis', 'pharmacy', 'loan', 'mortgage',
            'http://', 'https://', 'www.', '.com', '.net'
        ];

        $content = strtolower($content);
        
        foreach ($spamKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return true;
            }
        }

        // Check for excessive links or repetitive content
        if (substr_count($content, 'http') > 2 || 
            preg_match('/(.)\1{10,}/', $content) ||
            str_word_count($content) < 3) {
            return true;
        }

        return false;
    }

    /**
     * Format comment data for frontend.
     */
    private function formatCommentForFrontend($comment)
    {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'author_name' => $comment->author_name,
            'author_avatar' => $comment->author_avatar,
            'created_at' => $comment->created_at->toISOString(),
            'user_id' => $comment->user_id,
            'replies' => $comment->replies ? $comment->replies->map(function ($reply) {
                return $this->formatCommentForFrontend($reply);
            }) : [],
        ];
    }
}