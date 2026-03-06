<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<style>
    /* Custom Blog Styles */
    .blog-header-overlay {
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.6) 100%);
    }

    /* Editorial Typography */
    .article-body {
        font-family: 'Merriweather', 'Georgia', serif;
        font-size: 1.125rem;
        line-height: 1.8;
        color: #2c3e50;
    }

    .article-body p {
        margin-bottom: 1.5rem;
    }

    /* Automatic Drop Cap for first paragraph */
    .article-body>p:first-of-type::first-letter {
        float: left;
        font-size: 4.5rem;
        line-height: 0.85;
        font-weight: 700;
        margin-right: 0.75rem;
        color: #D32F2F;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .blockquote-custom {
        border-left: 4px solid #D32F2F;
        padding-left: 1.5rem;
        margin: 2.5rem 0;
        font-style: italic;
        font-family: 'Merriweather', serif;
        font-size: 1.25rem;
        color: #555;
        background: #fff9f9;
        padding: 1.5rem;
        border-radius: 0 10px 10px 0;
    }

    /* ... keep other styles ... */
</style>

<body>
    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->

    <!-- Hero Section -->
    <header class="position-relative d-flex align-items-end justify-content-center pb-5"
        style="min-height: 600px; margin-top: -80px;">
        <!-- Background -->
        <div class="position-absolute w-100 h-100 top-0 start-0">
            <div class="w-100 h-100"
                style="background-image: url('{{ $news->getFirstMediaUrl('primary_image', app_setting('default_news_image', 'https://images.unsplash.com/photo-1590250645602-af3f5f5a6f75?q=80&w=2070&auto=format&fit=crop')) }}'); background-size: cover; background-position: center;">
            </div>
            <div class="position-absolute w-100 h-100 top-0 start-0 blog-header-overlay"></div>
        </div>

        <!-- Content -->
        <div class="container position-relative z-2 text-center text-white pb-5">
            <span class="bg-japan-red px-3 py-1 rounded-pill fw-bold text-uppercase small mb-3 d-inline-block"
                style="letter-spacing: 0.1em;">
                Cultural History
            </span>
            <h1 class="display-3 fw-black mb-3 text-shadow" style="font-family: 'Bricolage Grotesque', sans-serif;">
                {{ $news->title }}
            </h1>
            <div
                class="d-flex justify-content-center align-items-center gap-3 small opacity-90 text-uppercase fw-bold ls-tight">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ app_setting('default_avatar', 'https://ui-avatars.com/api/?name='.urlencode($news->author).'&background=random') }}"
                        alt="Author" class="rounded-circle border border-white" style="width: 30px; height: 30px;">
                    <span>By {{ $news->author }}</span>
                </div>
                <span>•</span>
                <span>{{ $news->published_at?->format('F d, Y') ?? 'Recently' }}</span>
                <span>•</span>
                <span>{{ ceil(str_word_count(strip_tags($news->content)) / 200) }} min read</span>
            </div>
        </div>
    </header>

    <div class="container py-5">
        <div class="row g-5">
            <!-- Sidebar Left: Social Share (Desktop only) -->
            <div class="col-lg-1 d-none d-lg-block">
                <div class="d-flex flex-column gap-3 align-items-center social-share-sticky">
                    <a href="#" class="social-btn shadow-sm"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-btn shadow-sm"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn shadow-sm"><i class="fab fa-pinterest-p"></i></a>
                    <a href="#" class="social-btn shadow-sm"><i class="fas fa-link"></i></a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-7">
                <article class="article-body">
                    @if($news->excerpt)
                        <p class="lead fw-bold mb-4">{{ $news->excerpt }}</p>
                    @endif

                    {!! $news->content !!}

                    <!-- Tags (Static for now or loop if you have tags) -->
                    <div class="d-flex gap-2 mt-5 border-top pt-4">
                        <a href="#" class="btn btn-light rounded-pill px-3 py-1 small text-secondary border">#Japan</a>
                        <a href="#" class="btn btn-light rounded-pill px-3 py-1 small text-secondary border">#Travel</a>
                    </div>
                </article>

                <!-- Read Next -->
                @if(isset($next) && $next)
                    <div class="mt-5 p-4 bg-light rounded-4 border">
                        <span class="text-uppercase fw-bold text-secondary small mb-3 d-block">Read Next</span>
                        <div class="d-flex flex-column flex-md-row gap-4 align-items-center">
                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 120px; height: 120px;">
                                <img src="{{ $next->getFirstMediaUrl('primary_image', app_setting('default_news_image', 'https://images.unsplash.com/photo-1558980836-e8275997235a?q=80&w=2070&auto=format&fit=crop')) }}"
                                    class="w-100 h-100 object-fit-cover">
                            </div>
                            <div>
                                <h4 class="fw-bold mb-2 font-head">{{ $next->title }}</h4>
                                <p class="small text-secondary mb-2">
                                    {{ Str::limit($next->excerpt ?? strip_tags($next->content), 100) }}
                                </p>
                                <a href="{{ route('news.show', $next->slug) }}"
                                    class="text-japan-red fw-bold small text-uppercase text-decoration-none">Read
                                    Article <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                @endif


                <!-- Comments -->
                <div class="mt-5 pt-5 border-top">
                    <h3 class="fw-bold font-head mb-4">Comments ({{ $news->comments()->count() }})</h3>

                    <!-- Comment Form -->
                    @auth
                        <div class="d-flex gap-3 mb-5">
                            <img src="{{ app_setting('default_avatar', 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random') }}" 
                                class="rounded-circle comment-avatar shadow-sm" style="width: 48px; height: 48px;" alt="User">
                            <div class="flex-grow-1">
                                <form action="{{ route('comments.store', $news->slug) }}" method="POST">
                                    @csrf
                                    <textarea name="content" class="form-control border-0 bg-light rounded-3 p-3 mb-3" rows="3"
                                        placeholder="Share your thoughts..." required></textarea>
                                    <div class="text-end">
                                        <button type="submit"
                                            class="btn btn-japan rounded-pill px-4 py-2 fw-bold small text-uppercase shadow-sm">Post
                                            Comment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light border mb-5 d-flex align-items-center justify-content-between rounded-4 p-4">
                            <div class="d-flex align-items-center gap-3">
                                <span class="material-symbols-outlined fs-2 text-secondary">forum</span>
                                <div>
                                    <h6 class="fw-bold mb-0">Join the conversation</h6>
                                    <small class="text-secondary">Please login to share your thoughts.</small>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-dark rounded-pill px-4 fw-bold small text-uppercase">Login</a>
                        </div>
                    @endauth

                    <!-- Comment List -->
                    <div class="d-flex flex-column gap-4">
                        @forelse($news->rootComments as $comment)
                            <div class="d-flex gap-3">
                                <img src="{{ app_setting('default_avatar', 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=random') }}" 
                                    class="rounded-circle comment-avatar flex-shrink-0" style="width: 40px; height: 40px;" alt="{{ $comment->user->name }}">
                                <div class="w-100">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="fw-bold mb-0">{{ $comment->user->name }}</h6>
                                        @if($comment->user->name === $news->author)
                                            <span class="badge bg-secondary text-uppercase" style="font-size: 8px;">Author</span>
                                        @endif
                                        <small class="text-secondary">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="small text-secondary mb-1">{{ $comment->content }}</p>
                                    
                                    @auth
                                        <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('d-none')" 
                                            class="btn btn-link p-0 small text-muted fw-bold text-decoration-none" style="font-size: 12px;">• Reply</button>
                                        
                                        <!-- Reply Form -->
                                        <div id="reply-form-{{ $comment->id }}" class="mt-3 d-none">
                                            <form action="{{ route('comments.store', $news->slug) }}" method="POST" class="d-flex gap-2">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <input type="text" name="content" class="form-control form-control-sm bg-light border-0 rounded-pill px-3" placeholder="Write a reply..." required>
                                                <button type="submit" class="btn btn-dark btn-sm rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                                    <span class="material-symbols-outlined fs-6" style="font-size: 16px;">send</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endauth

                                    <!-- Nested Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="mt-3 ps-3 border-start">
                                            @foreach($comment->replies as $reply)
                                                <div class="d-flex gap-3 mb-3">
                                                    <img src="{{ app_setting('default_avatar', 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name).'&background=random') }}" 
                                                        class="rounded-circle comment-avatar flex-shrink-0" style="width: 32px; height: 32px;" alt="{{ $reply->user->name }}">
                                                    <div>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <h6 class="fw-bold mb-0 small">{{ $reply->user->name }}</h6>
                                                            @if($reply->user->name === $news->author)
                                                                <span class="badge bg-secondary text-uppercase" style="font-size: 7px;">Author</span>
                                                            @endif
                                                            <small class="text-secondary" style="font-size: 10px;">{{ $reply->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <p class="small text-secondary mb-0">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <span class="material-symbols-outlined fs-1 text-secondary opacity-25 mb-3">chat_bubble_outline</span>
                                <p class="text-secondary opacity-75">No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar Right -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    <!-- Experience This Card -->
                    @if(isset($sidebarPackage) && $sidebarPackage)
                        <div class="card border-0 shadow rounded-4 overflow-hidden mb-4 sidebar-tour-card">
                            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-japan-red">explore</span>
                                <h5 class="fw-bold m-0 font-head">Experience This</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="position-relative mb-3 rounded-3 overflow-hidden" style="height: 200px;">
                                    <div class="w-100 h-100" 
                                         style="background-image: url('{{ $sidebarPackage->getFirstMediaUrl('primary_image', app_setting('default_tour_image', 'https://via.placeholder.com/600x400?text=Tour+Package')) }}'); background-size: cover; background-position: center;"></div>
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <span class="badge bg-white text-dark shadow-sm">{{ $sidebarPackage->duration_days }} Days</span>
                                    </div>
                                </div>

                                <h4 class="fw-bold mb-2 h5"><a href="{{ route('tour.show', $sidebarPackage->slug) }}" class="text-decoration-none text-dark">{{ $sidebarPackage->title }}</a></h4>
                                
                                @php
                                    $avgRating = $sidebarPackage->reviews->avg('rating') ?? 0;
                                    $reviewCount = $sidebarPackage->reviews->count();
                                @endphp
                                
                                <div class="mb-2 text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($avgRating))
                                            <i class="fas fa-star"></i>
                                        @elseif($i == ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="text-secondary ms-1">({{ $reviewCount }} reviews)</span>
                                </div>
                                
                                <p class="small text-secondary mb-3">
                                    {{ Str::limit(strip_tags($sidebarPackage->description), 80) }}
                                </p>

                                <div class="mb-3 d-flex align-items-end justify-content-between">
                                    <div>
                                        <span class="d-block small text-secondary">From</span>
                                        <span class="h5 fw-black text-danger mb-0">{{ convert_currency($sidebarPackage->base_price) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('tour.show', $sidebarPackage->slug) }}" class="btn btn-japan w-100 rounded-pill fw-bold text-uppercase shadow-sm">
                                    Check Availability <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter -->
                    <div class="bg-white border rounded-4 p-4 text-center shadow-sm">
                        <div class="mb-3">
                            <span class="material-symbols-outlined fs-2 text-secondary">mail</span>
                        </div>
                        <h5 class="fw-bold mb-2 font-head">Travel Japan Like a Local</h5>
                        <p class="small text-secondary mb-4">Get secret insights and hidden gems delivered to your
                            inbox.</p>
                        <form>
                            <div class="mb-3">
                                <input type="email" class="form-control bg-light border-0 py-2"
                                    placeholder="Your email address">
                            </div>
                            <button
                                class="btn btn-dark w-100 rounded-1 text-uppercase fw-bold small py-2">Subscribe</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--================ Footer Area start =================-->
    @include('partials.footer')
    <!--=============== Footer Area end =================-->

</body>

</html>