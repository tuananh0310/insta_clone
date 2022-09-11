<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
        foreach ($posts as $post){
            $post->image = explode('|', $post->image);
        }
        return view('posts.index', compact('posts', 'suggest_users'));

    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $image = array();
        if($files = $request->file('image')){
            foreach ($files as $file) {
                $image_name = md5(rand(1000, 10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                $upload_path = 'public/multiple_image/';
                $image_url = $upload_path.$image_full_name;
                $file->move($upload_path, $image_full_name);
                $image[] = $image_url;
            }
        }
        Post::insert([
            'image' => implode('|', $image),
            'caption' => $request->caption,
            'user_id' => auth()->id()
        ]);
        return redirect(route('profile.index',auth()->user()->username));
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
        foreach ($posts as $post) {
        $post->image = explode('|', $post->image);
        $post->firstImage = $post->image[0];
        }

        return view('posts.explore', compact('posts'));
    }

    public function show(Post $post)
    {
        $posts = $post->user->posts->except('$post->id');
        $post->image = explode('|', $post->image);
        $post->firstImage = $post->image[0];
        return view('posts.show',compact('post','posts'));
    }

    public function updatelikes(Request $request, $post)
    {
    $post = Post::where('id', $post)->first();
    if (!$post) {
        App::abort(404);
    }
    if ($request->update == "1") {
        // add 1 like
        $post->likes = $post->likes + 1;
        $post->save();
    } else if ($request->update == "0" && $post->likes != 0) {
        // take 1 like
        $post->likes = $post->likes - 1;
        $post->save();
        return Redirect::to(route('post.index'));
    }}
}
