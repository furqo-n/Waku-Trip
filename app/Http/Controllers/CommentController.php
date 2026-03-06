<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, $slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        $validated = $request->validated();

        $comment = new Comment();
        $comment->content = $validated['content'];
        $comment->user_id = Auth::id();
        $comment->news_id = $news->id;
        $comment->parent_id = $validated['parent_id'] ?? null;
        $comment->save();

        return back()->with('success', 'Comment posted successfully!');
    }
}
