<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->profile) : false;
        foreach ($user->posts as $userPost){
            $userPost->image = explode('|', $userPost->image);
            $userPost->firstImage = $userPost->image[0];
        }
        return view('profiles.index', compact('user', 'follows'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        return view('profiles.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user->profile);
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

        return redirect(route('profile.index',auth()->user()->username));
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        $user = User::where('username', 'LIKE', '%' . $q . '%')->orWhere('email', 'LIKE', '%' . $q . '%')->get();
        if (count($user) > 0)
            return view('profiles.search')->withDetails($user)->withQuery($q);
        return view('profiles.search')->withMessage('No results found.');
    }
}
