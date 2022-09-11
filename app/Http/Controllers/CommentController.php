<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        Comment::create([
           'body' => $request->body,
            'post_id' => $request->post_id,
            'user_id' => auth()->user()->id,
            'parent_id' => $request->parent_id
        ]);
            return redirect()->route('post.show', compact('post'));
    }
}
