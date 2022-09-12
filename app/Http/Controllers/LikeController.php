<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LikeController extends Controller
{
    protected $like;
    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    /**
     * @param $id
     * @return Application|RedirectResponse|Redirector
     * update like
     */
    public function update($id)
    {
        $this->like->updateLike($id);
        return redirect(route('post.index'));
    }
}
