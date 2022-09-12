<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    private $user;
    private $profile;
    public function __construct(User $user, Profile $profile)
    {
        $this->profile = $profile;
        $this->user= $user;
    }

    /**
     * @param User $user
     * index profile
     */
    public function index(User $user)
    {
        $follows = $this->profile->indexProfile($user);
        return view('profiles.index', compact('user', 'follows'));
    }

    /**
     * @param User $user
     * @return Application|Factory|View
     * @throws AuthorizationException
     * edit profile
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        return view('profiles.edit', compact('user'));
    }

    /**
     * @param ProfileUpdateRequest $request
     * @param User $user
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     * update profile
     */
    public function update(ProfileUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user->profile);
        $this->profile->updateProfile($request, $user);
        return redirect(route('profile.index',auth()->user()->username));
    }

    /**
     * @param Request $request
     * @return mixed
     * search users
     */
    public function search(Request $request)
    {
        $data = $this->profile->searchProfile($request);
        if (count($data['user']) > 0)
            return view('profiles.search')->withDetails($data['user'])->withQuery($data['q']);
        return view('profiles.search')->withMessage('No results found.');
    }
}
