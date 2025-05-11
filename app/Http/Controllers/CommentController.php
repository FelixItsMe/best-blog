<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Post $post)
    {
        Comment::create(
            $request->safe()->only(['parent_id', 'content']) +
            [
                'post_id' => $post->id,
                'user_id' => Auth::user()->id,
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        //
    }
}
