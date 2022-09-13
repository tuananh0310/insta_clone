<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function indexPost()
    {
        $users_id = auth()->user()->following()->pluck('profiles.user_id');
        $suggest_users = User::all()->reject(function ($user) {
            $users_id = auth()->user()->following()->pluck('profiles.user_id')->toArray();
            return $user->id == Auth::id() || in_array($user->id, $users_id);
        });
        $users_id = $users_id->push(auth()->user()->id);

        $posts = Post::whereIn('user_id', $users_id)->with('user')->latest()->paginate(100)->getCollection();
        foreach ($posts as $post){
            $post->image = explode('|', $post->image);
        }
        $data = [
            'posts' => $posts,
            'suggest_users' => $suggest_users
        ];;
        return $data;
    }

    public function storePost($request)
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
    }

    public function explorePost()
    {
        $posts = Post::all()->except(Auth::id())->shuffle();
        foreach ($posts as $post) {
            $post->image = explode('|', $post->image);
            $post->firstImage = $post->image[0];
        }
        return $posts;
    }

    public function showPost($post)
    {
        $posts = $post->user->posts->except('$post->id');
        $post->image = explode('|', $post->image);
        $post->firstImage = $post->image[0];
        return $posts;
    }

    public function updateLikePost($request,$post)
    {
        $post = Post::where('id', $post)->first();
        if (!$post) {
            App::abort(404);
        }
        if ($request->update == "1") {
            $post->likes = $post->likes + 1;
            $post->save();
        } else if ($request->update == "0" && $post->likes != 0) {
            $post->likes = $post->likes - 1;
            $post->save();
            return Redirect::to(route('post.index'));
        }
    }
}
