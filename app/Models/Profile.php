<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getProfileImage(){
        return ($this->image) ? "storage/$this->image" : "img/default.png";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function indexProfile($user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->profile) : false;
        foreach ($user->posts as $userPost){
            $userPost->image = explode('|', $userPost->image);
            $userPost->firstImage = $userPost->image[0];
        }
        return $follows;
    }

    public function updateProfile($request, $user)
    {
        $imagePath = "";
        if(request('image')) {
            $imagePath = request('image')->store('/profile', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(300,300);
            $image->save();
        }
        auth()->user()->profile->update([
            'website' => $request->website,
            'bio' => $request->bio,
            'image' => $imagePath ? $imagePath : $user->profile->image
        ]);
        auth()->user()->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email
        ]);
    }

    public function searchProfile($request)
    {
        $q = $request->input('q');
        if($q == ""){
            $user = [];
        } else {
        $user = User::where('username', 'LIKE', '%' . $q . '%')->orWhere('email', 'LIKE', '%' . $q . '%')->get();
        }
        $data = [
            'user' => $user,
            'q' => $q
        ];
        return $data;
    }
}
