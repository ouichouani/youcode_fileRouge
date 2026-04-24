<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\UpdateLikeRequest;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LikeController extends Controller
{

    public function save(StoreLikeRequest $request)
    {
        $validated = $request->validated();
        $like = Like::where('user_id', Auth::id())->where('post_id', $validated['post_id'])->first();

        if ($like) {
            $like->delete();
            return redirect()->back() ;
            // return redirect()->route('posts.show', $validated['post_id'])->with('message', 'Like removed');
        }

        Like::create([
            'user_id' => Auth::id(),
            'post_id' => $validated['post_id'],
        ]);

        return redirect()->back() ;
        // return redirect()->route('posts.show', $validated['post_id'])->with('message', 'Post liked successfully');
    }

}
