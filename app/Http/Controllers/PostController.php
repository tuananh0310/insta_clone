<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    private $post;
    public function __construct(Post $post)
    {
        $this->middleware('auth');
        $this->post = $post;
    }

    /**
     * index post
     */
    public function index()
    {
        $data =  $this->post->indexPost();
        $posts = $data['posts'];
        $suggest_users = $data['suggest_users'];
        return view('posts.index', compact('posts','suggest_users'));
    }

    /**
     * create post
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * @param PostStoreRequest $request
     * store post
     */
    public function store(PostStoreRequest $request)
    {
        $this->post->storePost($request);
        return redirect(route('profile.index',auth()->user()->username));
    }

    /**
     * @param Post $post
     * delete post
     * @throws AuthorizationException
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return Redirect::back();
    }

    /**
     * explore post
     */
    public function explore()
    {
        $posts = $this->post->explorePost();
        return view('posts.explore', compact('posts'));
    }

    /**
     * @param Post $post
     * show post
     */
    public function show(Post $post)
    {
        $posts = $this->post->showPost($post);
        return view('posts.show',compact('post','posts'));
    }

    /**
     * @param Request $request
     * @param $post
     * update like
     */
    public function updatelikes(Request $request, $post)
    {
        $this->post->updateLikePost($request,$post);
    }
}
