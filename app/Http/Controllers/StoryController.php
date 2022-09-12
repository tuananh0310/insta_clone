<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class StoryController extends Controller
{
    private $story;
    public function __construct(Story $story)
    {
        $this->story = $story;
    }

    public function create()
    {
        return view('stories.create');
    }

    public function store()
    {
        $this->story->storeStory();
        return redirect(route('profile.index',auth()->user()->username));
    }

    public function show(User $user)
    {
        $stories = $user->stories;
        return view('stories.show', compact('stories', 'user'));
    }
}
