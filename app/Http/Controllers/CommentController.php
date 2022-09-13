<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $comment;
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * store comment
     */
    public function store(CommentStoreRequest $request)
    {
        $post = $this->comment->storeComment($request);
        return redirect()->route('post.show', compact('post'));
    }
}
