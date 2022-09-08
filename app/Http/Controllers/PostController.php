<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users_id = auth()->user()->following()->pluck('profiles.user_id');

        $suggest_users = User::all()->reject(function ($user) {
            $users_id = auth()->user()->following()->pluck('profiles.user_id')->toArray();
            return $user->id == Auth::id() || in_array($user->id, $users_id);
        });

        $users_id = $users_id->push(auth()->user()->id);

        $posts = Post::whereIn('user_id', $users_id)->with('user')->latest()->paginate(10)->getCollection();

        return view('posts.index', compact('posts', 'suggest_users'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'caption' => ['required', 'string'],
            'image' => ['required', 'image']
        ]);

        $imagePath = request('image')->store('/uploads', 'public');

        $image = Image::make(public_path("storage/{$imagePath}"))->widen(600, function ($constraint) {
            $constraint->upsize();
        });
        $image->save();

        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath
        ]);
        return redirect()->route('profile.index', ['user' => auth()->user()]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return Redirect::back();
    }

    public function explore()
    {
        $posts = Post::all()->except(Auth::id())->shuffle();

        return view('posts.explore', compact('posts'));
    }

    public function show(Post $post)
    {
        $posts = $post->user->posts->except('$post->id');
        return view('posts.show',compact('post','posts'));
    }
}
